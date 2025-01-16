<?php

namespace Khafidprayoga\PhpMicrosite\UseCases;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Khafidprayoga\PhpMicrosite\Models\Entities\Post;
use Khafidprayoga\PhpMicrosite\Services\PostService;

class PostServiceImpl extends InitUseCase implements PostService
{
    private EntityRepository $repo;

    public function __construct(Connection $db, EntityManager $entityManager)
    {
        parent::__construct($db, $entityManager);

        $this->repo = $this->entityManager->getRepository(Post::class);
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
