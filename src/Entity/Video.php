<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VideoRepository::class)
 */
class Video
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="text")
     */
    private string $url;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $source;

    /**
     * @ORM\ManyToOne(targetEntity=SnowboardTrick::class, inversedBy="videos")
     * @ORM\JoinColumn(nullable=false)
     */
    private SnowboardTrick $snowboardTrick;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

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
