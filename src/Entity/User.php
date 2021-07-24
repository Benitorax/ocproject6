<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *     "username",
 *     message = "This username is already used."
 * )
 * @UniqueEntity(
 *     "email",
 *     message = "This email is already used."
 * )
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use IdentifierTrait;

    /**
     * @Assert\Length(
     *      min = 3,
     *      max = 60,
     *      minMessage = "Your username must be at least {{ limit }} characters long.",
     *      maxMessage = "Your username cannot be longer than {{ limit }} characters."
     * )
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private string $username;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Your email cannot be longer than {{ limit }} characters."
     * )
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $email;

    /**
     * @Assert\Length(
     *      min = 6,
     *      max = 100,
     *      minMessage = "Your password must be at least {{ limit }} characters long.",
     *      maxMessage = "Your password cannot be longer than {{ limit }} characters."
     * )
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isActivated = false;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity=UserToken::class, mappedBy="user", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $tokens;

    /**
     * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"}, fetch="EAGER")
     * @JoinColumn(onDelete="CASCADE")
     */
    private ?Image $avatar = null;

    public function __construct()
    {
        $this->uuid = Uuid::v4();
        $this->tokens = new ArrayCollection();
    }

    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getIsActivated(): ?bool
    {
        return $this->isActivated;
    }

    public function setIsActivated(bool $isActivated): self
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    /**
     * @return Collection|UserToken[]
     */
    public function getTokens(): Collection
    {
        return $this->tokens;
    }

    public function getAvatar(): ?Image
    {
        return $this->avatar;
    }

    public function setAvatar(?Image $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }
}
