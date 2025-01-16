<?php

namespace Khafidprayoga\PhpMicrosite\UseCases;

use DI\Attribute\Inject;
use DI\Attribute\Injectable;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Khafidprayoga\PhpMicrosite\Models\DTO\UserDTO;
use Khafidprayoga\PhpMicrosite\Models\Entities\User;
use Khafidprayoga\PhpMicrosite\Services\PostServiceInterface;
use Khafidprayoga\PhpMicrosite\Services\ServiceMediatorInterface;
use Khafidprayoga\PhpMicrosite\Services\UserServiceInterface;
use UserService;

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

    public function createUser(UserDTO $request): User
    {
        // TODO: Implement createUser() method.
    }

    public function getUserById(int $userId): User
    {
        // TODO: Implement getUserById() method.
    }

    public function getUserByUsername(string $username): User
    {
        // TODO: Implement getUserByUsername() method.
    }

    public function getPosts(int $userId): ?array
    {
        // TODO: Implement getPosts() method.
    }

    public function getLikedPosts(int $userId): ?array
    {
        // TODO: Implement getLikedPosts() method.
    }


}
