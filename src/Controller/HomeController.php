<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
        $usersRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $usersRepository->findAll();


        if ($users) {
            return $this->render('home/index.html.twig', [
                    "users" => $users]
            );
        } else
            return $this->render('home/index.html.twig', [
                    'users' => null,
                ]
            );
    }
}
