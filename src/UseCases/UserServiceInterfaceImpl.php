<?php

namespace Khafidprayoga\PhpMicrosite\UseCases;

use DI\Attribute\Inject;
use DI\Attribute\Injectable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Khafidprayoga\PhpMicrosite\Models\DTO\UserDTO;
use Khafidprayoga\PhpMicrosite\Models\Entities\User;
use Khafidprayoga\PhpMicrosite\Services\PostServiceInterface;
use Khafidprayoga\PhpMicrosite\Services\ServiceMediatorInterface;
use Khafidprayoga\PhpMicrosite\Services\UserServiceInterface;

#[Injectable(lazy: true)]
class UserServiceInterfaceImpl extends InitUseCase implements UserServiceInterface
{
    private EntityRepository $repo;
    #[Inject]
    private PostServiceInterface $postService;

    public function __construct(ServiceMediatorInterface $mediator)
    {
        $db = $mediator->get(Connection::class);
        $entityManager = $mediator->get(EntityManager::class);
        parent::__construct($db, $entityManager);

        $this->repo = $this->entityManager->getRepository(User::class);
    }

    public function createUser(UserDTO $request): array
    {

        $hashedPassword = password_hash(
            $request->password,
            PASSWORD_BCRYPT,
            ['cost' => PASSWORD_BCRYPT_DEFAULT_COST],
        );

        $dql = <<<SQL
    INSERT 
        INTO users (full_name, username, password)
        VALUES (:fullName, :username, :password)
SQL;


        $query = $this->entityManager->getConnection()->prepare($dql);
        $query->bindValue(':fullName', $request->fullName);
        $query->bindValue(':username', $request->username);
        $query->bindValue(':password', $hashedPassword);

        $query->executeQuery();
        return $this->getUserByUsername($request->username);
    }

    public function getUserById(int $userId): array
    {
        return [];
    }

    public function getUserByUsername(string $username): array
    {
        return $this->repo->findBy(['username' => $username]);

    }

    public function getPosts(int $userId): ?array
    {
        return [];
    }

    public function getLikedPosts(int $userId): ?array
    {
        return [];
    }


}
