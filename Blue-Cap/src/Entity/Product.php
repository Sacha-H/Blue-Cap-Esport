<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
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
    private $nameProduct;

    /**
     * @ORM\Column(type="text")
     */
    private $descriptionProduct;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceProduct;

    /**
     * @ORM\Column(type="integer")
     */
    private $stockProduct;

    /**
     * @ORM\ManyToOne(targetEntity=CategoryProduct::class, inversedBy="product")
     */
    private $categoryProduct;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageProduct;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameProduct(): ?string
    {
        return $this->nameProduct;
    }

    public function setNameProduct(string $nameProduct): self
    {
        $this->nameProduct = $nameProduct;

        return $this;
    }

    public function getDescriptionProduct(): ?string
    {
        return $this->descriptionProduct;
    }

    public function setDescriptionProduct(string $descriptionProduct): self
    {
        $this->descriptionProduct = $descriptionProduct;

        return $this;
    }

    public function getPriceProduct(): ?int
    {
        return $this->priceProduct;
    }

    public function setPriceProduct(int $priceProduct): self
    {
        $this->priceProduct = $priceProduct;

        return $this;
    }

    public function getStockProduct(): ?int
    {
        return $this->stockProduct;
    }

    public function setStockProduct(int $stockProduct): self
    {
        $this->stockProduct = $stockProduct;

        return $this;
    }

    public function getCategoryProduct(): ?CategoryProduct
    {
        return $this->categoryProduct;
    }

    public function setCategoryProduct(?CategoryProduct $categoryProduct): self
    {
        $this->categoryProduct = $categoryProduct;

        return $this;
    }

    public function getImageProduct(): ?string
    {
        return $this->imageProduct;
    }

    public function setImageProduct(string $imageProduct): self
    {
        $this->imageProduct = $imageProduct;

        return $this;
    }

}
