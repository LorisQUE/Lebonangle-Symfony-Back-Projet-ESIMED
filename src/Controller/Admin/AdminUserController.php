<?php

namespace App\Controller\Admin;

use App\Entity\AdminUser;
use App\Form\AdminUserType;
use App\Repository\AdminUserRepository;
use Doctrine\DBAL\Types\StringType;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 */
class AdminUserController extends AbstractController
{
    /**
     * @Route("/", name="admin_user_index", methods={"GET"})
     */
    public function index(AdminUserRepository $adminUserRepository): Response
    {
        return $this->render('admin_user/index.html.twig', [
            'admin_users' => $adminUserRepository->findAll(),
            'current_user_id' => $this->getUser()->getId(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AdminUser $adminUser): Response
    {
        $form = $this->createForm(AdminUserType::class, $adminUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Utiliser pour trigger le listener preUpdate (si on modifie que le champs plainPassword,
            // le listener ne sera pas appelé, car le champs n'est pas mappé)
            $form->getData()->setPassword('');
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'L\'administrateur a bien été modifié.');
            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin_user/edit.html.twig', [
            'admin_user' => $adminUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AdminUser $adminUser): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adminUser->getId(), $request->request->get('_token'))) {
            if($this->getUser()->getId() != $adminUser->getId()){
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($adminUser);
                $entityManager->flush();
                $this->addFlash('success', 'L\'administrateur a bien été supprimé.');
            } else {
                $this->addFlash('danger', 'Vous ne pouvez pas vous supprimez.');
            }
        }
        return $this->redirectToRoute('admin_user_index');
    }
}
