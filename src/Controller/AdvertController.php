<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Repository\AdvertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

/**
 * @Route("/")
 */
class AdvertController extends AbstractController
{
    /**
     * @Route("/", name="advert_index")
     */
    public function index(AdvertRepository $advertRepository): Response
    {
        if($this->getUser() === null){
        $adverts = $advertRepository->findBy(['state'=>'published']);
        } else{
        $adverts = $advertRepository->findAll();
        }
        return $this->render('advert/index.html.twig', [
            'adverts' => $adverts,
        ]);
    }

    /**
     * @Route("/state/{id}/{transition}", name="advert_state", methods={"GET"})
     */
    public function changeState(Advert $advert, string $transition, WorkflowInterface $advertStateMachine, EntityManagerInterface $manager): Response
    {
        if ($advertStateMachine->can($advert, $transition)) {
            $advertStateMachine->apply($advert, $transition);
            $manager->flush();
            $this->addFlash('success', sprintf('Transition "%s" appliqué avec succès', $transition));
        } else {
            $this->addFlash('danger', sprintf('La transition "%s" n\'a pas pu être appliqué à l\'annonce "%s"', $transition, $advert->getId()));
        }

        return $this->redirectToRoute('advert_index');
    }
}
