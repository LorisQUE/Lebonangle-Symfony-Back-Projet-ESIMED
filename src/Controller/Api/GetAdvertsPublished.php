<?php
namespace App\Controller\Api;

use App\Entity\Advert;
use App\Entity\Picture;
use App\Repository\AdvertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class GetAdvertsPublished
{
    private AdvertRepository $advertRepository;
    public function __construct(AdvertRepository $advertRepository)
    {
        $this->advertRepository = $advertRepository;
    }
    public function __invoke(Request $request)
    {
        return $this->advertRepository->findBy(["state"=>"published"]);
    }
}