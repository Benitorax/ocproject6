<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\SnowboardTrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SnowboardTrickRepository::class)
 */
class SnowboardTrick
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private Uuid $id;

    /**
     * @Assert\Length(
     *      min = 3,
     *      max = 150,
     *      minMessage = "The name must be at least {{ limit }} characters long.",
     *      maxMessage = "The name cannot be longer than {{ limit }} characters."
     * )
     * @ORM\Column(type="string", length=150)
     */
    private string $name;

    /**
     * @Assert\Length(
     *      min = 10,
     *      max = 255,
     *      minMessage = "The name must be at least {{ limit }} characters long.",
     *      maxMessage = "The name cannot be longer than {{ limit }} characters."
     * )
     * @ORM\Column(type="string", length=255)
     */
    private string $description;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $updatedAt;

    /**
     * @var null|Image
     * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"})
     */
    private $illustration;

    /**
     * @ORM\OneToMany(
     *     targetEntity=Image::class,
     *     mappedBy="snowboardTrick",
     *     orphanRemoval=true,
     *     cascade={"persist", "remove"}
     * )
     */
    private Collection $images;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="snowboardTrick", orphanRemoval=true)
     */
    private Collection $videos;

    /**
     * @Assert\Range(
     *      min = 1,
     *      max = 9,
     *      notInRangeMessage = "A category must be selected",
     * )
     * @ORM\Column(type="integer")
     */
    private string $category;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->images = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getIllustration(): ?Image
    {
        return $this->illustration;
    }

    public function setIllustration(?Image $illustration): self
    {
        if (!$illustration instanceof Image) {
            return $this;
        }

        $this->illustration = $illustration;

        if (null === $illustration->getSnowboardTrick()) {
            $illustration->setSnowboardTrick($this);
        }

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setSnowboardTrick($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // if ($image->getSnowboardTrick() === $this) {
            //     $image->setSnowboardTrick(null);
            // }
        }

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setSnowboardTrick($this);
        }

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }
}
