<?php

namespace Khafidprayoga\PhpMicrosite\UseCases;

use DI\Attribute\Inject;
use DI\Attribute\Injectable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;
use Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Firebase\JWT\JWT;
use Khafidprayoga\PhpMicrosite\Models\DTO\TokenDTO;
use Khafidprayoga\PhpMicrosite\Models\Entities;
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
    private const int refreshTokenExpiresInDays = 7;
    #[Inject]
    private readonly UserServiceInterface $userService;

    private readonly EntityRepository $repo;
    private readonly string $jwtSecret;

    public function __construct(ServiceMediatorInterface $mediator)
    {
        $db = $mediator->get(Connection::class);
        $entityManager = $mediator->get(EntityManager::class);
        parent::__construct($db, $entityManager);

        $this->repo = $this->entityManager->getRepository(Entities\Session::class);

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

    public function logout(string $jwtToken): bool
    {
        // TODO: Implement logout() method.
        return false;
    }

    /**
     * @throws HttpException|Exception|DBALException
     */
    private function generateJwtToken(array $user): TokenDTO
    {
        try {
            $now = Carbon::now();

            $accessToken = $this->generateAccessToken(
                now: $now,
                userId: $user['id'],
                fullName: $user['fullName'],
            );

            $jti = bin2hex(random_bytes(16));

            $expiresAt = $now->addDays(self::refreshTokenExpiresInDays);
            $refreshToken = $this->generateRefreshToken(
                now: $now,
                userId: $user['id'],
                jti: $jti,
            );

            // save refresh token
            $dql = <<<SQL
        INSERT 
            INTO sessions (jti,refresh_token,created_at, expires_at) 
        VALUES (:jti, :refresh_token, :created_at, :expires_at)
SQL;

            $query = $this->entityManager->getConnection()->prepare($dql);
            $query->bindValue('jti', $jti);
            $query->bindValue('refresh_token', $refreshToken);
            $query->bindValue('created_at', $now);
            $query->bindValue('expires_at', $expiresAt);

            $query->executeQuery();
            return new TokenDTO($accessToken, $refreshToken);

        } catch (Exception $err) {
            $this->log->error('failed to insert jwt refresh token session', $err->getMessage());
            throw new HttpException(message: 'failed to save token session', code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function generateAccessToken(Carbon $now, int $userId, string $fullName): string
    {

        return JWT::encode(
            payload: [
                'iat' => $now->timestamp,
                'sub' => $userId,
                'exp' => $now->addHours(self::accessTokenExpiresInHours)->timestamp,
                'name' => $fullName,
            ],
            key: $this->jwtSecret,
            alg: 'HS256'
        );
    }

    private function generateRefreshToken(Carbon $now, int $userId, string $jti): string
    {

        return JWT::encode(
            payload: [
                'iat' => $now->timestamp,
                'sub' => $userId,
                'exp' => $now->addDays(self::refreshTokenExpiresInDays)->timestamp,
                'jti' => $jti,
            ],
            key: $this->jwtSecret,
            alg: 'HS256'
        );

    }

    public function refresh(string $refreshToken): string
    {

        // TODO: Implement refresh() method.
        return '';
    }

    public function revalidate(string $token): void
    {

    }
}
