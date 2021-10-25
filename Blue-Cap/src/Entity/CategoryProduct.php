<?php

namespace App\Entity;

use App\Repository\CategoryProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryProductRepository::class)
 */
class CategoryProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nameCategoryProduct;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="categoryProduct")
     */
    private $product;


    public function __construct()
    {
        $this->product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameCategoryProduct(): ?string
    {
        return $this->nameCategoryProduct;
    }

    public function setNameCategoryProduct(string $nameCategoryProduct): self
    {
        $this->nameCategoryProduct = $nameCategoryProduct;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }


}
