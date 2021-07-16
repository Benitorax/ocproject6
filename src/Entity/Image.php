<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private string $format;

    /**
     * @ORM\Column(type="blob")
     */
    private string $data;

    /**
     * @ORM\ManyToOne(targetEntity=SnowboardTrick::class, inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private SnowboardTrick $snowboardTrick;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getSnowboardTrick(): SnowboardTrick
    {
        return $this->snowboardTrick;
    }

    public function setSnowboardTrick(SnowboardTrick $snowboardTrick): self
    {
        $this->snowboardTrick = $snowboardTrick;

        return $this;
    }
}
