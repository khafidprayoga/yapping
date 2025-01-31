<?php

namespace Khafidprayoga\PhpMicrosite\UseCases;

use DI\Attribute\Inject;
use DI\Attribute\Injectable;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Khafidprayoga\PhpMicrosite\Commons\HttpException;
use Khafidprayoga\PhpMicrosite\Models\DTO\PostingRequestDTO;
use Khafidprayoga\PhpMicrosite\Models\Entities\Post;
use Khafidprayoga\PhpMicrosite\Services\PostServiceInterface;
use Khafidprayoga\PhpMicrosite\Services\ServiceMediatorInterface;
use Khafidprayoga\PhpMicrosite\Services\UserServiceInterface;
use Exception;
use Khafidprayoga\PhpMicrosite\Utils\Pagination;
use Symfony\Component\HttpFoundation\Response;

#[Injectable(lazy: true)]
class PostServiceInterfaceImpl extends InitUseCase implements PostServiceInterface
{
    private EntityRepository $repo;
    #[Inject]
    private UserServiceInterface $userService;

    public function __construct(ServiceMediatorInterface $mediator)
    {
        $db = $mediator->get(Connection::class);
        $entityManager = $mediator->get(EntityManager::class);

        parent::__construct($db, $entityManager);


        $this->repo = $this->entityManager->getRepository(Post::class);
    }

    /**
     * @throws Exception
     */
    public function createNewPost(PostingRequestDTO $request): array
    {
        $dql = <<<SQL
    INSERT 
        INTO posts (title, content, user_id)
        VALUES (:title, :content, :user_id)
    RETURNING id
SQL;


        $query = $this->entityManager->getConnection()->prepare($dql);
        $query->bindValue(':title', $request->title);
        $query->bindValue(':content', $request->content);
        $query->bindValue(':user_id', $request->userId);

        $query->executeQuery();
        $id = $this->entityManager->getConnection()->lastInsertId();

        return $this->getPostById($id);
    }

    /**
     * @throws HttpException
     */
    public function getPostById(Pagination $paginator, int $id): array
    {
        $post = $this->repo->createQueryBuilder("posts")
            ->addSelect("users")
            ->leftJoin("posts.author", "users")
            ->where("posts.id = :postId")
            ->setParameter("postId", $id)
            ->orderBy('posts.id', 'DESC')
            ->getQuery()
            ->getArrayResult();

        if (!$post) {
            throw  new HttpException("Feed with id $id not found or has been deleted", Response::HTTP_NOT_FOUND);
        }

        if ($paginator->isContainsSearch()) {
            return [
                'post' => $post[0],
                'previousPostId' => null,
                'nextPostId' => null,
            ];
        }

        // Get previous and next post (for not filetered feeds)
        $actionNextPostId = $this->repo->createQueryBuilder('posts')
            ->select("posts.id")
            ->where("posts.id < :postId")
            ->setParameter("postId", $id)
            ->orderBy('posts.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $actionPrevPostId = $this->repo->createQueryBuilder('posts')
            ->select("posts.id")
            ->where('posts.id > :postId')
            ->setParameter("postId", $id)
            ->orderBy('posts.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $prevPostId = $actionPrevPostId['id'] ?? null;
        $nextPostId = $actionNextPostId['id'] ?? null;

        return [
            'post' => $post[0],
            'previousPostId' => $prevPostId,
            'nextPostId' => $nextPostId,
        ];
    }


    public
    function getPosts(Pagination $pagination): array
    {
        $query = $this->repo
            ->createQueryBuilder("posts")
            ->addSelect("users")
            ->innerJoin("posts.author", "users")
            ->where("posts.isDeleted = :isDeleted");

        if ($pagination->isContainsSearch()) {
            $search = $pagination->getSearch();
            $query
                ->andWhere("posts.title LIKE :search OR posts.content LIKE :search")
                ->setParameter("search", "%$search%");
        }

        $query = $query
            ->setParameter("isDeleted", false)
            ->orderBy("posts.id", "DESC")
            ->setFirstResult($pagination->getOffset())
            ->setMaxResults($pagination->getPageSize())
            ->getQuery();

        return $query->getArrayResult();
    }
}
