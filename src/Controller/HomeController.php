<?php


namespace App\Controller;

use App\Entity\AdminUser;
use App\Entity\Category;
use App\Form\AdminUserType;
use App\Repository\AdminUserRepository;
use App\Repository\AdvertRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class HomeController extends AbstractController
{

    /**
     * @Route("/category", name="category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/category/{id}", name="category_show", methods={"GET"})
     */
    public function show(Category $category, AdvertRepository $advertRepository): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
            'adverts' => $advertRepository->findBy(['category'=>$category->getId()]),
        ]);
    }
}