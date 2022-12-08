<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"wish_read"})
     * @Groups({"offer_browse"})
     * @Groups({"offer_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"offer_browse"})
     * @Groups({"offer_read"})
     * @Groups({"wish_read"})
     * @Groups({"wish_browse"})
     * @Groups({"current_user_offers"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=128, nullable=true, unique=true)
     * @Groups({"offer_browse"})
     * @Groups({"offer_read"})
     * @Groups({"wish_read"})
     * @Groups({"wish_browse"})
     * 
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $picture;

    /**
     * @ORM\Column(type="boolean")
     * 
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     * 
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Offer::class, inversedBy="categories")
     */
    private $offer;

    /**
     * @ORM\ManyToMany(targetEntity=Wish::class, inversedBy="categories")
     */
    private $wish;

    /**
     * @ORM\ManyToOne(targetEntity=MainCategory::class, inversedBy="categories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mainCategory;

    public function __construct()
    {
        $this->offer = new ArrayCollection();
        $this->wish = new ArrayCollection();
        $this->isActive = true;
    }

    public function getId(): ?int
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

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

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

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
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        $this->offer->removeElement($offer);

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
        }

        return $this;
    }

    public function removeWish(Wish $wish): self
    {
        $this->wish->removeElement($wish);

        return $this;
    }

    public function getMainCategory(): ?MainCategory
    {
        return $this->mainCategory;
    }

    public function setMainCategory(?MainCategory $mainCategory): self
    {
        $this->mainCategory = $mainCategory;

        return $this;
    }
}
