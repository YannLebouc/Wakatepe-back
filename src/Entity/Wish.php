<?php

namespace App\Entity;

use App\Repository\WishRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Date;
use OpenApi\Annotations as OA;

/**
 * @ORM\Entity(repositoryClass=WishRepository::class)
 * 
 * @ORM\HasLifecycleCallbacks()
 */
class Wish
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"wish_browse"})
     * @Groups({"wish_read"})
     * @Groups({"current_user_wishes"})
     * @Groups({"mainCategory_category_browse"})
     * @Groups({"current_user_inactive_ads"})
     * @Groups({"mainCategory_categories_advertisements"})
     * @Groups({"category_wishes"})
     * @Groups({"category_advertisements"})
     * @Groups({"user_ads_browse"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     * 
     * @Groups({"wish_browse"})
     * @Groups({"wish_read"})
     * @Groups({"current_user_wishes"})
     * @Groups({"mainCategory_categories_advertisements"})
     * @Groups({"mainCategory_category_browse"})
     * @Groups({"category_wishes"})
     * @Groups({"category_advertisements"})
     * @Groups({"nelmio_add_wish"})
     * @Groups({"current_user_inactive_ads"})
     * @Groups({"user_ads_browse"})
     * 
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=16)
     * 
     * @Groups({"wish_browse"})
     * @Groups({"wish_read"})
     * @Groups({"current_user_wishes"})
     * @Groups({"category_wishes"})
     * @Groups({"category_advertisements"})
     * @Groups({"nelmio_add_wish"})
     * @Groups({"current_user_inactive_ads"})
     * @Groups({"user_ads_browse"})
     * 
     * 
     * @Assert\Length(max=5)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Groups({"wish_browse"})
     * @Groups({"wish_read"})
     * @Groups({"current_user_wishes"})
     * @Groups({"category_advertisement_browse"})
     * @Groups({"current_user_inactive_ads"})
     * @Groups({"category_wishes"})
     * @Groups({"category_advertisements"})
     * @Groups({"user_ads_browse"})
     * 
     */
    private $picture;

    /**
     * @ORM\Column(type="text", nullable=true)
     * 
     * @Groups({"wish_browse"})
     * @Groups({"wish_read"})
     * @Groups({"current_user_wishes"})
     * @Groups({"category_wishes"})
     * @Groups({"category_advertisements"})
     * @Groups({"nelmio_add_wish"})
     * @Groups({"current_user_inactive_ads"})
     * @Groups({"user_ads_browse"})
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     * 
     * @Groups({"nelmio_add_wish"})
     * @Groups({"current_user_inactive_ads"})
     * @Groups({"current_user_wishes"})
     * 
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=16)
     * 
     * @Groups({"wish_browse"})
     * @Groups({"wish_read"})
     * @Groups({"current_user_wishes"})
     * @Groups({"category_wishes"})
     * @Groups({"category_advertisements"})
     * @Groups({"nelmio_add_wish"})
     * @Groups({"current_user_inactive_ads"})
     * @Groups({"user_ads_browse"})
     */
    private $type;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * 
     * @Groups({"wish_read"})
     * @Groups({"category_wishes"})
     * @Groups({"category_advertisements"})
     * @Groups({"user_ads_browse"})
     */
    private $isReported;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Groups({"wish_browse"})
     * @Groups({"wish_read"})
     * @Groups({"current_user_wishes"})
     * @Groups({"category_advertisement_browse"})
     * @Groups({"current_user_inactive_ads"})
     * @Groups({"category_wishes"})
     * @Groups({"category_advertisements"})
     * @Groups({"wish_browse"})
     * @Groups({"user_ads_browse"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 
     * @Groups({"wish_browse"})
     * @Groups({"wish_read"})
     * @Groups({"current_user_wishes"})
     * @Groups({"category_advertisement_browse"})
     * @Groups({"current_user_inactive_ads"})
     * @Groups({"category_wishes"})
     * @Groups({"category_advertisements"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="wish")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Groups({"wish_read"})
     * @Groups({"category_wishes"})
     * @Groups({"category_advertisements"})
     * @Groups({"wish_browse"})
     * @Groups({"current_user_wishes"})
     * 
     * 
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, mappedBy="wish")
     *     
     * @Groups({"wish_browse"})
     * @Groups({"wish_read"})
     * @Groups({"current_user_wishes"})
     * @Groups({"nelmio_add_wish"})
     * @Groups({"category_advertisements"})
     * @Groups({"user_ads_browse"})
     * @OA\Property(type="array", @OA\Items(type="integer"))
     */
    private $categories;

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime();
    }

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->isActive = true;
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

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
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
            $category->addWish($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeWish($this);
        }

        return $this;
    }
}
