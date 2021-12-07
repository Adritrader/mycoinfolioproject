<?php

namespace App\Controller;

use App\Entity\Analysis;
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

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Access Denied');

        $usersRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $usersRepository->lastUsers();

        $analysisRepository = $this->getDoctrine()->getRepository(Analysis::class);
        $analysis = $analysisRepository->lastAnalysis();


        if ($users) {
            return $this->render('home/index.html.twig', [
                    "users" => $users,
                    "analysis" => $analysis]
            );
        } else
            return $this->render('home/index.html.twig', [
                    'users' => null,
                    'analysis' => null
                ]
            );
    }
}
