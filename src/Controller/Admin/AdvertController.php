<?php


namespace App\Controller\Admin;


use App\Entity\Advert;
use App\Repository\AdvertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

/**
 * @Route("/admin/advert")
 */
class AdvertController extends AbstractController
{
    /**
     * @Route("/", name="advert_index")
     */
    public function index(AdvertRepository $advertRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $donnees = $advertRepository->findAll();

        $adverts = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('advert/index.html.twig', [
            'adverts' => $adverts,
        ]);
    }

    /**
     * @Route("/show/{id}", name="advert_show", methods={"GET"})
     */
    public function show(Advert $advert): Response
    {
        return $this->render('advert/show.html.twig', [
            'advert' => $advert,
            'pictures' => ["picture1", "picture2"],
        ]);
    }

    /**
     * @Route("state/{id}/{transition}", name="advert_state", methods={"GET"})
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