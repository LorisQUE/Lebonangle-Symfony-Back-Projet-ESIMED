<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AdvertRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;

/**
 * @ORM\Entity(repositoryClass=AdvertRepository::class)
 * @ApiResource(
 *     itemOperations={"get"},
 *     collectionOperations={"get","post"}
 * )
 * @ApiFilter(OrderFilter::class, properties={"publishedAt", "price"})
 * @ApiFilter(RangeFilter::class, properties={"price"})
 */
class Advert
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 3,
     *      max = 100,
     *      minMessage = "Le titre doit avoir au moins {{ limit }} caractères.",
     *      maxMessage = "Le titre ne doit pas dépasser {{ limit }} caractères.",
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *      max = 1200,
     *      maxMessage = "Le contenu ne doit pas dépasser {{ limit }} caractères.",
     * )
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     * @ORM\JoinColumn(nullable=false)
     * @ApiFilter(SearchFilter::class, properties={"category.id": "iexact"})
     */
    private $category;

    /**
     * @ORM\Column(type="float")
     * @Assert\Range(
     *      min = 1,
     *      max = 1000000,
     *      notInRangeMessage = "Le prix doit être compris entre {{ min }}€ et {{ max }}€.",
     * )
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $state;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedAt;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

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

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }
}
