<?php

namespace App\Tests\Service;

use App\Service\Paginator;
use App\Entity\SnowboardTrick;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PaginatorTest extends KernelTestCase
{
    private CommentRepository $repository;

    public function setUp(): void
    {
        $this->repository = static::getContainer()->get(CommentRepository::class);
    }

    public function testPaginate(): void
    {
        $trick = new SnowboardTrick();
        $reflection = new \ReflectionClass($trick);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($trick, 1);

        $paginator = new Paginator();
        $query = $this->repository->findBySnowboardTrickQuery($trick);
        $result = $paginator->paginate($query);
        $this->assertInstanceOf(Paginator::class, $result);
        $this->assertSame($paginator, $result);
    }
}
