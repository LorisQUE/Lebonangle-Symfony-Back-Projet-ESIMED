<?php


namespace App\Twig;


use App\Entity\Advert;
use App\Entity\Picture;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FilterAdvertExtension extends AbstractExtension
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('nbPhotos', [$this, 'getNbPhotos']),
        ];
    }

    public function getNbPhotos(Advert $advert)
    {
        return $this->entityManager->getRepository(Picture::class)->count(["advert"=>$advert]);

    }
}