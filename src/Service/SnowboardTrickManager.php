<?php

namespace App\Service;

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
}
