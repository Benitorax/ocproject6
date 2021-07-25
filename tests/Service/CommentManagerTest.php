<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Entity\Comment;
use Doctrine\ORM\Query;
use App\Service\Paginator;
use App\Entity\SnowboardTrick;
use App\Service\CommentManager;
use Doctrine\ORM\Configuration;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommentManagerTest extends KernelTestCase
{
    private CommentManager $manager;
    private EntityManagerInterface $entityManager;
    private CommentRepository $repository;
    private Security $security;

    public function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repository = static::getContainer()->get(CommentRepository::class);
        $this->security = $this->createMock(Security::class);

        $this->manager = new CommentManager($this->entityManager, $this->repository, $this->security);
    }

    public function testsaveNewComment(): void
    {
        $comment = new Comment();
        $this->entityManager->expects($this->once())->method('persist')->with($comment);
        $this->entityManager->expects($this->once())->method('flush');
        $this->security->expects($this->once())->method('getUser')->willReturn(new User());

        $this->manager->saveNewComment($comment, new SnowboardTrick());
    }

    public function testGetPagination(): void
    {
        $trick = new SnowboardTrick();
        $reflection = new \ReflectionClass($trick);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($trick, 1);

        $pagination = $this->manager->getPagination($trick, 1);
        $this->assertInstanceOf(Paginator::class, $pagination);
    }
}
