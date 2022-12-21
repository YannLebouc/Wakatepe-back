<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * 
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"wish_read"})
     * @Groups({"offer_read"})
     * @Groups({"users_read"})
     * @Groups({"users_browse"})
     * @Groups({"category_advertisement_browse"})
     * @Groups({"current_user_offers"})
     * @Groups({"current_user_wishes"})
     * @Groups({"current_user_inactive_ads"})
     * 
     * 
     * @Groups({"category_offers"})
     * @Groups({"category_wishes"})
     * @Groups({"category_advertisements"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * 
     * @Groups({"wish_read"})
     * @Groups({"offer_read"})
     * @Groups({"user_offer_browse"})
     * @Groups({"users_read"})
     * @Groups({"users_browse"})
     * @Groups({"nelmio_add_user"})
     * @Groups({"nelmio_edit_user"})
     * @Groups({"current_user_offers"})
     * @Groups({"current_user_wishes"})
     * @Groups({"current_user_inactive_ads"})
     * 
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * 
     * @Groups({"wish_read"})
     * @Groups({"offer_read"})
     * @Groups({"users_read"})
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * 
     * @Assert\NotBlank
     * @Groups({"nelmio_add_user"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups({"wish_read"})
     * @Groups({"offer_read"})
     * @Groups({"users_read"})
     * @Groups({"category_advertisement_browse"})
     * @Groups({"current_user_offers"})
     * @Groups({"category_offers"})
     * @Groups({"category_wishes"})
     * @Groups({"category_advertisements"})
     * @Groups({"nelmio_add_user"})
     * @Groups({"nelmio_edit_user"})
     * @Groups({"current_user_wishes"})
     * @Groups({"current_user_inactive_ads"})
     * 
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    private $alias;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     * 
     * @Groups({"wish_read"})
     * @Groups({"offer_read"})
     * @Groups({"users_read"})
     * @Groups({"current_user_offers"})
     * @Groups({"current_user_wishes"})
     * @Groups({"current_user_inactive_ads"})
     * 
     * @Assert\Positive
     * @Groups({"nelmio_add_user"})
     * @Groups({"nelmio_edit_user"})
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=16)
     * 
     * @Groups({"users_read"})
     * @Groups({"current_user_offers"})
     * @Groups({"nelmio_add_user"})
     * @Groups({"nelmio_edit_user"})
     * @Groups({"current_user_wishes"})
     * @Groups({"current_user_inactive_ads"})
     * 
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Positive
     * @Assert\Length(max=5)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=64)
     * 
     * @Groups({"users_read"})
     * @Groups({"current_user_offers"})
     * @Groups({"nelmio_add_user"})
     * @Groups({"nelmio_edit_user"})
     * @Groups({"current_user_wishes"})
     * @Groups({"current_user_inactive_ads"})
     * 
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=128)
     * 
     * @Groups({"users_read"})
     * @Groups({"current_user_wishes"})
     * @Groups({"nelmio_add_user"})
     * @Groups({"nelmio_edit_user"})
     * @Groups({"current_user_inactive_ads"})
     * 
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Groups({"wish_read"})
     * @Groups({"offer_read"})
     * @Groups({"users_read"})
     * @Groups({"nelmio_add_user"})
     * @Groups({"nelmio_edit_user"})
     * @Groups({"current_user_inactive_ads"})
     */
    private $picture;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Groups({"users_read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 
     * @Groups({"users_read"})
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Offer::class, mappedBy="user", orphanRemoval=true)
     * 
     * @Groups({"user_offer_browse"})
     * @Groups({"current_user_offers"})
     * @Groups({"current_user_inactive_ads"})
     * 
     */
    private $offer;

    /**
     * @ORM\OneToMany(targetEntity=Wish::class, mappedBy="user", orphanRemoval=true)
     * @Groups({"current_user_wishes"})
     * @Groups({"current_user_inactive_ads"})
     */
    private $wish;

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime();
    }

    public function __construct()
    {
        $this->offer = new ArrayCollection();
        $this->wish = new ArrayCollection();
        $this->roles = ['ROLE_USER'];
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
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
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getOffer(): Collection
    {
        return $this->offer;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offer->contains($offer)) {
            $this->offer[] = $offer;
            $offer->setUser($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offer->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getUser() === $this) {
                $offer->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Wish>
     */
    public function getWish(): Collection
    {
        return $this->wish;
    }

    public function addWish(Wish $wish): self
    {
        if (!$this->wish->contains($wish)) {
            $this->wish[] = $wish;
            $wish->setUser($this);
        }

        return $this;
    }

    public function removeWish(Wish $wish): self
    {
        if ($this->wish->removeElement($wish)) {
            // set the owning side to null (unless already changed)
            if ($wish->getUser() === $this) {
                $wish->setUser(null);
            }
        }

        return $this;
    }
}
