<?php

namespace Khafidprayoga\PhpMicrosite\UseCases;

use DI\Attribute\Inject;
use DI\Attribute\Injectable;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Khafidprayoga\PhpMicrosite\Commons\HttpException;
use Khafidprayoga\PhpMicrosite\Models\DTO\FeedsRequestDTO;
use Khafidprayoga\PhpMicrosite\Models\DTO\PostingRequestDTO;
use Khafidprayoga\PhpMicrosite\Models\Entities\Post;
use Khafidprayoga\PhpMicrosite\Models\Entities\Session;
use Khafidprayoga\PhpMicrosite\Services\PostServiceInterface;
use Khafidprayoga\PhpMicrosite\Services\ServiceMediatorInterface;
use Khafidprayoga\PhpMicrosite\Services\UserServiceInterface;
use Exception;
use Khafidprayoga\PhpMicrosite\Utils\Pagination;
use Symfony\Component\HttpFoundation\Response;
use function DI\string;

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
            ->andWhere('posts.isDeleted = :isDeleted')
            ->setParameter("isDeleted", false)
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
    function getPosts(FeedsRequestDTO $pagination): array
    {
        $query = $this->repo
            ->createQueryBuilder("posts")
            ->addSelect("users")
            ->innerJoin("posts.author", "users")
            ->where("posts.isDeleted = :isDeleted");

        $queryCount = $this->repo
            ->createQueryBuilder("posts")
            ->select('COUNT(posts.id)')
            ->where("posts.isDeleted = :isDeleted")
            ->setParameter("isDeleted", false);


        if ($pagination->isContainsSearch()) {
            $search = $pagination->getSearch();

            $query
                ->andWhere("posts.title LIKE :search OR posts.content LIKE :search")
                ->setParameter("search", "%$search%");

            $queryCount->andWhere("posts.title LIKE :search OR posts.content LIKE :search")
                ->setParameter("search", "%$search%");
        }

        if ($pagination->getAuthorId() > 0) {
            $query->andWhere("posts.userId = :authorId")
                ->setParameter("authorId", $pagination->getAuthorId());

            $queryCount->andWhere("posts.userId = :authorId")
                ->setParameter("authorId", $pagination->getAuthorId());
        }


        if (!is_null($pagination->getStartDate()) && !is_null($pagination->getEndDate())) {
            $query->andWhere('posts.createdAt BETWEEN :startDate AND :endDate')
                ->setParameter('startDate', $pagination->getStartDate())
                ->setParameter('endDate', $pagination->getEndDate());

            $queryCount->andWhere("posts.createdAt BETWEEN :startDate AND :endDate")
                ->setParameter("startDate", $pagination->getStartDate())
                ->setParameter("endDate", $pagination->getEndDate());
        }

        $countPages = $queryCount->getQuery()->getSingleScalarResult();

        $queryList = $query
            ->setParameter("isDeleted", false)
            ->orderBy("posts.id", "DESC")
            ->setFirstResult($pagination->getOffset())
            ->setMaxResults($pagination->getPageSize())
            ->getQuery();

        if (!$pagination->isNeedCountOnDB()) {
            return [
                'data' => $queryList->getArrayResult(),
            ];
        }

        $this->log->debug('counting on db');

        $totalItems = (int)$countPages;
        $totalPages = (int)ceil($totalItems / $pagination->getPageSize());


        return [
            'data' => $queryList->getArrayResult(),
            'total_pages' => $totalPages,
            'total_items' => $totalItems,
        ];
    }

    /**
     * @throws HttpException
     */
    public function deletePostById(int $userId, int $postId): void
    {
        try {
            $setRevokeQuery = $this->repo->createQueryBuilder('posts')
                ->update(Post::class, 'p')
                ->set('p.isDeleted', true)
                ->where('p.id = :postId')
                ->andWhere('p.userId = :userId')
                ->setParameter('postId', $postId)
                ->setParameter('userId', $userId)
                ->getQuery();

            $setRevokeQuery->execute();
        } catch (Exception $e) {
            throw new HttpException($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
