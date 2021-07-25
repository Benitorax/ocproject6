<?php

namespace App\Tests\Service;

use App\Entity\SnowboardTrick;
use App\Repository\CommentRepository;
use App\Service\SnowboardTrickManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SnowboardTrickRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SnowboardTrickManagerTest extends KernelTestCase
{
    private SnowboardTrickRepository $repository;
    private EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        $this->repository = static::getContainer()->get(SnowboardTrickRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->manager = new SnowboardTrickManager($this->repository, $this->entityManager);
    }

    public function testsaveNewTrick(): void
    {
        $this->entityManager->expects($this->once())->method('persist');
        $this->entityManager->expects($this->once())->method('flush');
        $this->manager->saveNewTrick(new SnowboardTrick());
    }

    public function testSaveEditedTrick(): void
    {
        $this->entityManager->expects($this->once())->method('persist');
        $this->entityManager->expects($this->once())->method('flush');
        $this->manager->saveEditedTrick(new SnowboardTrick());
    }

    public function testDeleteTrickBySlug(): void
    {
        $repository = $this->createMock(SnowboardTrickRepository::class);
        $repository->expects($this->once())->method('findOneWithRelation')->willReturn(new SnowboardTrick());

        $this->entityManager->expects($this->once())
                            ->method('getRepository')
                            ->willReturn(static::getContainer()->get(CommentRepository::class));
        $this->entityManager->expects($this->any())->method('persist');
        $this->entityManager->expects($this->exactly(2))->method('flush');

        $manager = new SnowboardTrickManager($repository, $this->entityManager);
        $manager->deleteTrickBySlug('trick-slug');
    }
}
