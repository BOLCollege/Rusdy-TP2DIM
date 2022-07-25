<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(): Response
    {
        $data = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'list' => $data

        ]);
    }

    #[Route('create', name: 'create')]
    public function create(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice', 'Created !!');

            return $this->redirectToRoute('main');

        }

        return $this->render('main/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
