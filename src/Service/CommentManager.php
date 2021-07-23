<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\SnowboardTrick;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CommentManager
{
    private EntityManagerInterface $entityManager;
    private CommentRepository $repository;
    private Security $security;

    public function __construct(
        EntityManagerInterface $entityManager,
        CommentRepository $repository,
        Security $security
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->security = $security;
    }

    /**
     * Save new comment in database.
     */
    public function saveNewComment(Comment $comment, SnowboardTrick $trick): void
    {
        $comment->setSnowboardTrick($trick);
        /** @var User */
        $user = $this->security->getUser();
        $comment->setUser($user);
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

    /**
     * Return Paginator.
     */
    public function getPagination(SnowboardTrick $trick, int $page): Paginator
    {
        $query = $this->repository->findBySnowboardTrickQuery($trick);
        return (new Paginator())->paginate($query, $page, 10);
    }
}
