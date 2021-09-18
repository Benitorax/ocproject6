<?php

namespace App\Entity;

use App\Entity\IdentifierTrait;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ImageRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
{
    use IdentifierTrait;

    /**
     * @Assert\Choice({
     *    "image/bmp",
     *    "image/gif",
     *    "image/jpeg",
     *    "image/png"})
     * @ORM\Column(type="string", length=10)
     */
    private string $format;

    /**
     * @ORM\Column(type="blob")
     * @var string|resource
     */
    private $data = null;

    /**
     * @ORM\ManyToOne(targetEntity=SnowboardTrick::class, inversedBy="images")
     */
    private ?SnowboardTrick $snowboardTrick = null;

    /**
     * Used only for ImageType.
     * @var mixed
     * @Assert\NotNull(message="An image should be selected.")
     */
    private $file = null;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->id = 0;
        $this->uuid = Uuid::v4();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return string|resource
     */
    public function getData()
    {
        if (is_string($this->data)) {
            return $this->data;
        }

        return stream_get_contents($this->data, -1, 0); /* @phpstan-ignore-line */
    }

    /**
     * @param string|resource $data
     */
    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getSnowboardTrick(): ?SnowboardTrick
    {
        return $this->snowboardTrick;
    }

    public function setSnowboardTrick(?SnowboardTrick $snowboardTrick): self
    {
        $this->snowboardTrick = $snowboardTrick;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function __clone()
    {
        $this->uuid = Uuid::v4();
        $this->format = $this->format;
        $this->data = $this->data;
        $this->file = $this->file;
        $this->createdAt = new \DateTimeImmutable('now');
        $this->snowboardTrick = null;
    }
}
