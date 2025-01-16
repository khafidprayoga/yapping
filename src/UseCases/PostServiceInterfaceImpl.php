<?php

namespace Khafidprayoga\PhpMicrosite\UseCases;

use DI\Attribute\Injectable;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Khafidprayoga\PhpMicrosite\Models\Entities\Post;
use Khafidprayoga\PhpMicrosite\Services\PostServiceInterface;
use Khafidprayoga\PhpMicrosite\Services\ServiceMediatorInterface;
use Khafidprayoga\PhpMicrosite\Services\UserServiceInterface;

#[Injectable]
class PostServiceInterfaceImpl extends InitUseCase implements PostServiceInterface
{
    private EntityRepository $repo;
    private UserServiceInterface $userService;

    public function __construct(ServiceMediatorInterface $mediator)
    {
        $db = $mediator->get(Connection::class);
        $entityManager = $mediator->get(EntityManager::class);

        parent::__construct($db, $entityManager);

        $this->repo = $this->entityManager->getRepository(Post::class);

        $this->userService = $mediator->get(UserServiceInterface::class);
    }

    public function createNewPost(): Post
    {
        // TODO: Implement createNewPost() method.
        throw new \Exception("Not implemented");
    }

    public function getPostById(int $id): Post
    {
        $post = $this->repo->createQueryBuilder("posts")
            ->addSelect("users")
            ->leftJoin("posts.author", "users")
            ->where("posts.id = :postId")
            ->setParameter("postId", $id)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$post) {
            throw new \Exception("Feed with id {$id} not found");
        }
        //        TODO LOAD AUTHOR DATA WITH JOIN LIKE PRELOAD
        $post->getAuthor();
        return $post;
    }
}
