<?php

namespace App\Entity;

use App\Repository\MainCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass=MainCategoryRepository::class)
 * 
 * @ORM\HasLifecycleCallbacks()
 */
class MainCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"offer_browse"})
     * @Groups({"mainCategories_categories"})
     * @Groups({"mainCategory_category_browse"})
     * @Groups({"category_advertisements"})
     * @Groups({"wish_browse"})
     * @Groups({"maincat_categories"})
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * 
     * @Groups({"mainCategories_categories"})
     * @Groups({"mainCategory_category_browse"})
     * @Groups({"category_advertisements"})
     * @Groups({"offer_browse"})
     * @Groups({"wish_browse"})
     * @Groups({"maincat_categories"})
     * 
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * 
     * @Groups({"mainCategories_categories"})
     * @Groups({"mainCategory_category_browse"})
     * @Groups({"maincat_categories"})
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="mainCategory")
     * 
     * @Groups({"mainCategories_categories"})
     * @Groups({"mainCategory_categories_advertisements"})
     * @Groups({"maincat_categories"})
     * 
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
            $category->setMainCategory($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getMainCategory() === $this) {
                $category->setMainCategory(null);
            }
        }

        return $this;
    }
}
