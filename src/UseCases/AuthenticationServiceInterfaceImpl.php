<?php

namespace Khafidprayoga\PhpMicrosite\UseCases;

use DI\Attribute\Inject;
use DI\Attribute\Injectable;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Firebase\JWT\JWT;
use Khafidprayoga\PhpMicrosite\Models\DTO\TokenDTO;
use Khafidprayoga\PhpMicrosite\Services\AuthServiceInterface;
use Khafidprayoga\PhpMicrosite\Services\ServiceMediatorInterface;
use Khafidprayoga\PhpMicrosite\Services\UserServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Khafidprayoga\PhpMicrosite\Commons\HttpException;

#[Injectable(lazy: true)]
class AuthenticationServiceInterfaceImpl extends InitUseCase implements AuthServiceInterface
{
    #[Inject]
    private readonly UserServiceInterface $userService;

    public function __construct(ServiceMediatorInterface $mediator)
    {
        $db = $mediator->get(Connection::class);
        $entityManager = $mediator->get(EntityManager::class);
        parent::__construct($db, $entityManager);
    }

    /**
     * @throws HttpException
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

    private function generateJwtToken(array $user): TokenDTO
    {
        $jwtSecret = APP_CONFIG->providers->jwt->secretKey;

        $now = Carbon::now();
        $accessToken = JWT::encode(
            payload: [
                'iat' => $now->timestamp,
                'sub' => $user['id'],
                'exp' => $now->addHours(5)->timestamp,
                'name' => $user['fullName']
            ],
            key: $jwtSecret,
            alg: 'HS256'
        );

        $refreshToken = JWT::encode(
            payload: [
                'iat' => $now->timestamp,
                'sub' => $user['id'],
                'exp' => $now->addDays(7)->timestamp,
                'jti' => bin2hex(random_bytes(16)),
            ],
            key: $jwtSecret,
            alg: 'HS256'
        );

        return new TokenDTO($accessToken, $refreshToken);
    }

    public function refresh(string $refreshToken): string
    {

        // TODO: Implement refresh() method.
        return '';
    }
}
