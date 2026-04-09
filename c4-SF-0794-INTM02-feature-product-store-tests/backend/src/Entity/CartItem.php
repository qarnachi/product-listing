<?php

namespace App\Entity;

use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['cart:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'cartItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cart $cart = null;

    #[ORM\Column(type: 'integer')]
    #[Groups(['cart:read'])]
    private int $productId;

    #[ORM\Column(type: 'string')]
    #[Groups(['cart:read'])]
    private string $title;

    #[ORM\Column(type: 'float')]
    #[Groups(['cart:read'])]
    private float $price;

    #[ORM\Column(type: 'string')]
    #[Groups(['cart:read'])]
    private string $image;

    #[ORM\Column(type: 'integer')]
    #[Groups(['cart:read'])]
    private int $quantity = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCart(): ?Cart 
    { 
        return $this->cart; 
    }

    public function setCart(?Cart $cart): static 
    { 
        $this->cart = $cart; 
        return $this; 
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): self
    {
        $this->productId = $productId;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }
}
