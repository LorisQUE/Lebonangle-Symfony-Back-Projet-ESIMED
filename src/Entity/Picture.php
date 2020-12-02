<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PictureRepository;
use App\Controller\Api\CreatePictureAction;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;


/**
 * @ApiResource(
 *     iri="http://schema.org/MediaObject",
 *     normalizationContext={
 *         "groups"={"picture_read"}
 *     },
 *     itemOperations={"get"},
 *     collectionOperations={
 *       "get",
 *       "post"={
 *             "controller"=CreatePictureAction::class,
 *             "deserialize"=false,
 *             "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
 *             "validation_groups"={"Default", "picture_create"},
 *             "openapi_context"={
 *                 "requestBody"={
 *                     "content"={
 *                         "multipart/form-data"={
 *                             "schema"={
 *                                 "type"="object",
 *                                 "properties"={
 *                                     "file"={
 *                                         "type"="string",
 *                                         "format"="binary"
 *                                     },
 *                                      "advert"={
                                            "type":"int",
 *                                          "format":"int"
 *                                      }
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 }
 *             }
 *         }
 *     }
 * )
 * @ORM\Entity(repositoryClass=PictureRepository::class)
 * @Vich\Uploadable
 */
class Picture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ApiProperty(iri="http://schema.org/contentUrl")
     * @Groups({"picture_read"})
     */
    public $contentUrl;

    /**
     * @Assert\NotNull(groups={"picture_create"})
     * @Vich\UploadableField(mapping="picture",fileNameProperty="path")
     */
    private ?File $file = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Advert::class, cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(groups={"picture_create"})
     * @ApiFilter(SearchFilter::class, properties={"advert.id": "iexact"})
     * @Groups("read")
     */
    private Advert $advert;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAdvert(): Advert
    {
        return $this->advert;
    }

    public function setAdvert(Advert $advert): self
    {
        $this->advert = $advert;

        return $this;
    }
}
