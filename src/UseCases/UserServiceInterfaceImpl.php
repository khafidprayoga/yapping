<?php

namespace Khafidprayoga\PhpMicrosite\UseCases;

use DI\Attribute\Inject;
use DI\Attribute\Injectable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use http\QueryString;
use Khafidprayoga\PhpMicrosite\Models\DTO\UserDTO;
use Khafidprayoga\PhpMicrosite\Models\Entities\User;
use Khafidprayoga\PhpMicrosite\Services\PostServiceInterface;
use Khafidprayoga\PhpMicrosite\Services\ServiceMediatorInterface;
use Khafidprayoga\PhpMicrosite\Services\UserServiceInterface;
use Khafidprayoga\PhpMicrosite\Utils\Pagination;

#[Injectable(lazy: true)]
class UserServiceInterfaceImpl extends InitUseCase implements UserServiceInterface
{
    private EntityRepository $repo;
    private PostServiceInterface $postService;

    public function __construct(ServiceMediatorInterface $mediator)
    {
        $db = $mediator->get(Connection::class);
        $entityManager = $mediator->get(EntityManager::class);
        parent::__construct($db, $entityManager);

        $this->postService = $mediator->get(PostServiceInterface::class);
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
        return $this->repo->createQueryBuilder("users")
            ->where("users.id = :userId")
            ->setParameter("userId", $userId)
            ->getQuery()
            ->getArrayResult();
    }

    public function getUserByUsername(string $username): array
    {
        return $this->repo->createQueryBuilder("users")
            ->where("users.username = :username")
            ->setParameter("username", $username)
            ->getQuery()
            ->getArrayResult();
    }

    public function getPosts(int $userId): ?array
    {
        return [];
    }

    public function getLikedPosts(int $userId): ?array
    {
        return [];
    }


    public function getUsers(Pagination $pagination, bool $showSearch): array
    {
        $query = $this->repo->createQueryBuilder("users");

        if ($pagination->isContainsSearch() && $showSearch) {
            $search = $pagination->getSearch();

            $query
                ->andWhere("users.fullName  LIKE :search OR users.username LIKE :search")
                ->setParameter("search", "%$search%");
        }

        return $query
            ->orderBy("users.fullName", "ASC")
            ->getQuery()->getArrayResult();
    }
}
