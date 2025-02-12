<?php

namespace Khafidprayoga\PhpMicrosite\UseCases;

use DI\Attribute\Inject;
use DI\Attribute\Injectable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Khafidprayoga\PhpMicrosite\Commons\AppMode;
use Khafidprayoga\PhpMicrosite\Models\DTO\JwtClaimsDTO;
use Khafidprayoga\PhpMicrosite\Models\DTO\RefreshSessionRequestDTO;
use Khafidprayoga\PhpMicrosite\Models\DTO\TokenDTO;
use Khafidprayoga\PhpMicrosite\Models\Entities\Session;
use Khafidprayoga\PhpMicrosite\Services\AuthServiceInterface;
use Khafidprayoga\PhpMicrosite\Services\ServiceMediatorInterface;
use Khafidprayoga\PhpMicrosite\Services\UserServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Khafidprayoga\PhpMicrosite\Commons\HttpException;

#[Injectable(lazy: true)]
class AuthenticationServiceInterfaceImpl extends InitUseCase implements AuthServiceInterface
{
    private const int accessTokenExpiresInHours = 5;
    private const int refreshTokenAllowUsageInHours = 3;
    private const int refreshTokenExpiresInDays = 7;
    private const int resetTokenExpiresInHours = 5;

    private readonly UserServiceInterface $userService;

    private readonly EntityRepository $repo;
    private readonly string $jwtSecret;

    public function __construct(ServiceMediatorInterface $mediator)
    {
        $db = $mediator->get(Connection::class);
        $entityManager = $mediator->get(EntityManager::class);
        parent::__construct($db, $entityManager);

        $this->repo = $this->entityManager->getRepository(Session::class);
        $this->userService = $mediator->get(UserServiceInterface::class);
        $this->jwtSecret = APP_CONFIG->providers->jwt->secretKey;
    }

    /**
     * @throws HttpException|Exception
     * @throws DBALException
     */
    public function login(string $username, string $password): TokenDTO
    {
        $user = $this->userService->getUserByUsername($username);
        if (!$user) {
            throw new HttpException(message: "username or password invalid", code: Response::HTTP_NOT_FOUND);
        }

        $user = $user[0];

        // compare hash password
        $isMatch = password_verify($password, $user['password']);
        if (!$isMatch) {
            $this->log->warning(
                'failed user authentication attempt',
                [
                    'username' => $user['username'],
                ]
            );
            throw new HttpException(message: "username or password invalid", code: Response::HTTP_BAD_REQUEST);
        }

        //  generating jwt token
        return $this->generateJwtToken($user);
    }

    public function logout(string $refreshToken): bool
    {
        $setRevokeQuery = $this->repo->createQueryBuilder('sessions')
            ->update(Session::class, 's')
            ->set('s.isRevoked', true)
            ->where('s.refreshToken = :refreshToken')
            ->setParameter('refreshToken', $refreshToken)
            ->getQuery();

        $setRevokeQuery->execute();
        return true;
    }

    /**
     * @throws HttpException|DBALException
     */
    private function generateJwtToken(array $user): TokenDTO
    {
        try {
            $accessTokenPair = $this->generateAccessToken(
                userId: $user['id'],
                fullName: $user['fullName'],
            );

            $jti = bin2hex(random_bytes(16));

            $refreshTokenPair = $this->generateRefreshToken(
                userId: $user['id'],
                jti: $jti,
            );

            // save refresh token
            $dql = <<<SQL
        INSERT 
            INTO sessions (jti,refresh_token, expires_at) 
        VALUES (:jti, :refresh_token, :expires_at)
SQL;

            $query = $this->entityManager->getConnection()->prepare($dql);
            $query->bindValue('jti', $jti);
            $query->bindValue('refresh_token', $refreshTokenPair['token']);
            $query->bindValue('expires_at', $refreshTokenPair['expiresAt']);

            $query->executeQuery();

            $token = new TokenDTO($accessTokenPair['token'], $refreshTokenPair['token']);

            $accessTokenExpiresAt = Carbon::parse($accessTokenPair['expiresAt']);
            $refreshTokenExpiresAt = Carbon::parse($refreshTokenPair['expiresAt']);

            $token->setAccessTokenExpiresAt($accessTokenExpiresAt->timestamp);
            $token->setRefreshTokenExpiresAt($refreshTokenExpiresAt->timestamp);

            return $token;
        } catch (Exception $err) {
            $this->log->error('failed to insert jwt refresh token session', $err->getMessage());
            throw new HttpException(message: 'failed to save token session', code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function generateAccessToken(int $userId, string $fullName): array
    {
        $expiresAt = Carbon::now()->addHours(self::accessTokenExpiresInHours);
        $encoded = JWT::encode(
            payload: [
                'iat' => Carbon::now()->timestamp,
                'sub' => $userId,
                'exp' => $expiresAt->timestamp,
                'name' => $fullName,
            ],
            key: $this->jwtSecret,
            alg: 'HS256'
        );

        return [
            "token" => $encoded,
            "expiresAt" => $expiresAt,
        ];
    }

    private function generateRefreshToken(int $userId, string $jti): array
    {

        $expiresAt = Carbon::now()->addDays(self::refreshTokenExpiresInDays);

        $payload = [
            'iat' => Carbon::now()->timestamp,
            'sub' => $userId,
            'exp' => $expiresAt->timestamp,
            'jti' => $jti,
        ];

        if (APP_CONFIG->appMode === AppMode::PRODUCTION) {
            $payload['nbf'] = Carbon::now()->addHours(self::refreshTokenAllowUsageInHours)->timestamp;
        }

        $encoded = JWT::encode(
            payload: $payload,
            key: $this->jwtSecret,
            alg: 'HS256'
        );

        return [
            "token" => $encoded,
            "expiresAt" => $expiresAt,
        ];
    }


    public function generateResetToken(string $username): array
    {
        $expiresAt = Carbon::now()->addHours(self::resetTokenExpiresInHours);

        $payload = [
            'iat' => Carbon::now()->timestamp,
            'jti' => $username,
            'exp' => $expiresAt->timestamp,
        ];

        if (APP_CONFIG->appMode === AppMode::PRODUCTION) {
            $payload['nbf'] = Carbon::now()->timestamp;
        }

        $encoded = JWT::encode(
            payload: $payload,
            key: $this->jwtSecret,
            alg: 'HS256'
        );

        return [
            "token" => $encoded,
            "expiresAt" => $expiresAt,
        ];
    }

    /**
     * @throws HttpException|BeforeValidException
     */
    public function refresh(RefreshSessionRequestDTO $request): TokenDTO
    {
        try {
            $claims = $this->decode($request->getRefreshToken());

            // get user data
            $user = $this->userService->getUserById($claims->getUserId());
            if (!$user) {
                throw new HttpException("failed generate access token", code: Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $user = $user[0];
            $accessToken = $this->generateAccessToken($claims->getUserId(), $user['fullName']);

            $token = new TokenDTO(accessToken: $accessToken['token']);

            $accessTokenExpiresAt = Carbon::parse($accessToken['expiresAt']);
            $token->setAccessTokenExpiresAt($accessTokenExpiresAt->timestamp);

            return $token;
        } catch (BeforeValidException $err) {
            throw new HttpException("can not yet use this refresh token", code: Response::HTTP_BAD_REQUEST);
        } catch (ExpiredException $err) {
            $refreshToken = $request->getRefreshToken();

            //  revoke the refresh token
            $setRevokeQuery = $this->repo->createQueryBuilder('sessions')
                ->update(Session::class, 's')
                ->set('s.isRevoked', true)
                ->where('s.refreshToken = :refreshToken')
                ->setParameter('refreshToken', $refreshToken)
                ->getQuery();

            $setRevokeQuery->execute();
            throw new HttpException("can not use this refresh token, token expired", code: Response::HTTP_UNAUTHORIZED);
        } catch (DBALException $err) {
            throw new HttpException(
                'refresh token revoked please do authentication flow again',
                code: Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }

    private function decode(string $token): JwtClaimsDTO
    {
        $decoded = (array)JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
        return new JwtClaimsDTO($decoded);
    }

    /**
     * @throws HttpException
     */
    public function validate(string $jwtToken): JwtClaimsDTO
    {
        try {
            return $this->decode($jwtToken);
        } catch (Exception $err) {
            throw new HttpException($err->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
