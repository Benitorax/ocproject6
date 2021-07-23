<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\SnowboardTrick;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SnowboardTrickRepository;

class SnowboardTrickManager
{
    private SnowboardTrickRepository $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        SnowboardTrickRepository $repository,
        EntityManagerInterface $entityManager
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * Save a new trick.
     */
    public function saveNewTrick(SnowboardTrick $trick): void
    {
        $datetime = new \DateTimeImmutable('now');
        $trick->setCreatedAt($datetime)
            ->setUpdatedAt($datetime);

        $this->entityManager->persist($trick);
        $this->entityManager->flush();
    }

    /**
     * Delete the given trick.
     */
    public function deleteTrickBySlug(string $slug): void
    {
        $trick = $this->repository->findOneWithRelation($slug);
        $repository = $this->entityManager->getRepository(Comment::class);
        $comments = $repository->findBy(['snowboardTrick' => $trick]);

        foreach ($comments as $comment) {
            $this->entityManager->remove($comment);
        }

        $this->entityManager->remove($trick->getIllustration()); /** @phpstan-ignore-line */
        $this->entityManager->remove($trick);
        $this->entityManager->flush();
    }
}
