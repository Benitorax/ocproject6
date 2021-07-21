<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ImageRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private Uuid $id;

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
     * @Assert\NotBlank
     * @ORM\Column(type="blob")
     * @var string|resource
     */
    private $data;

    /**
     * @ORM\ManyToOne(targetEntity=SnowboardTrick::class, inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?SnowboardTrick $snowboardTrick = null;

    /**
     * Used only for ImageType.
     * @var mixed
     */
    private $file;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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
        return stream_get_contents($this->data); /* @phpstan-ignore-line */
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

    public function setSnowboardTrick(SnowboardTrick $snowboardTrick): self
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
}
