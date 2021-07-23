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
    public function deleteTrick(SnowboardTrick $trick): void
    {
        $repository = $this->entityManager->getRepository(Comment::class);
        $comments = $repository->findBy(['snowboardTrick' => $trick]);

        foreach ($comments as $comment) {
            $this->entityManager->remove($comment);
        }
        $this->flushAndClear();

        $repository = $this->entityManager->getRepository(Image::class);
        $images = $repository->findBy(['snowboardTrick' => $trick]);
        foreach ($images as $image) {
            $trick->removeImage($image);
            $this->entityManager->remove($image);
        }
        $this->flushAndClear();

        // $image = $trick->getIllustration();
        // $image->setSnowboardTrick(null);
        // $trick->setIllustration(null);
        // $this->entityManager->remove($image);
        $this->entityManager->remove($trick);
        $this->entityManager->flush();
    }

    private function flushAndClear(): void
    {
        $this->entityManager->flush();
        $this->entityManager->clear();
    }
}
