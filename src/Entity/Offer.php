<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=OfferRepository::class)
 * 
 */
class Offer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"offer_browse"})
     * @Groups({"offer_read"})
     * @Groups({"user_offer_browse"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     * 
     * @Groups({"offer_browse"})
     * @Groups({"offer_read"})
     * @Groups({"user_offer_browse"})
     * @Groups({"current_user_offers"})
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * 
     * @Groups({"offer_browse"})
     * @Groups({"offer_read"})
     * @Groups({"current_user_offers"})
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Groups({"offer_browse"})
     * @Groups({"offer_read"})
     * @Groups({"current_user_offers"})
     */
    private $picture;

    /**
     * @ORM\Column(type="text", nullable=true)
     * 
     * @Groups({"offer_browse"})
     * @Groups({"offer_read"})
     * @Groups({"current_user_offers"})
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="boolean")
     * 
     */
    private $isLended;

    /**
     * @ORM\Column(type="string", length=16)
     * 
     * @Groups({"offer_browse"})
     * @Groups({"offer_read"})
     * @Groups({"current_user_offers"})
     */
    private $type;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * 
     * @Groups({"offer_read"})
     */
    private $isReported;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"current_user_offers"})
     * @Groups({"offer_read"})
     * 
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"current_user_offers"})
     * @Groups({"offer_read"})
     * 
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="offer")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Groups({"offer_read"})
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, mappedBy="offer")
     * 
     * @Groups({"offer_read"})
     * @Groups({"offer_browse"})
     * @Groups({"current_user_offers"})
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->isActive = true;
        $this->isLended = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(int $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isIsLended(): ?bool
    {
        return $this->isLended;
    }

    public function setIsLended(bool $isLended): self
    {
        $this->isLended = $isLended;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isIsReported(): ?bool
    {
        return $this->isReported;
    }

    public function setIsReported(?bool $isReported): self
    {
        $this->isReported = $isReported;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addOffer($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeOffer($this);
        }

        return $this;
    }
}
