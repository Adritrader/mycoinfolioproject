<?php

namespace App\Controller;

use App\Entity\Portfolio;
use App\Entity\User;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PortfolioController extends AbstractController
{
    /**
     * @Route("/portfolios", name="portfolios")
     */
    public function index(): Response
    {

        $portfoliosRepository = $this->getDoctrine()->getRepository(Portfolio::class);
        $portfolios = $portfoliosRepository->findAll();


        if ( $portfolios )
        {
            return $this->render('portfolio/index.html.twig', [
                    "portfolios" => $portfolios]
            );
        }
        else
            return $this->render('portfolio/index.html.twig', [
                    'portfolios' => null,
                ]
            );
    }

    /**
     * @Route("portfolios/{id}/create", name="create_portfolios", requirements={"id"="\d+"})
     */
    public function create(Request $request, int $id)
    {
        $portfolio = new Portfolio();

        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->find($id);


        // Assign the date of analysis

        $portfolio->setCreatedAt(new \DateTimeImmutable());
        $portfolio->setUser($user);

        // Save analysis on BD

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($portfolio);
        $entityManager->flush();

        //Logger

        $logger = new Logger('Portfolio');
        $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
        $logger->info('Portfolio ' . $portfolio->getId() . ' successfully registered on ' . date("Y-m-d H:i:s", time()));

        // Flash message

        $this->addFlash('success', "Portfolio has been created succesfully");


        return $this->redirectToRoute('portfolios');


    }

    /**
     * @Route("/portfolio/{id}/show", name="show_portfolio", requirements={"id"="\d+"})
     */
    public function show(int $id)
    {

        /*$this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Acceso restringido a administradores');*/
        $portfolioRepository = $this->getDoctrine()->getRepository(Portfolio::class);
        $portfolio = $portfolioRepository->find($id);
        if ($portfolio)
        {
            return $this->render('portfolio/show_portfolio.html.twig', ["portfolio"=>$portfolio]
            );
        }
        else
            return $this->render('portfolio/show_portfolio.html.twig', [
                    'portfolio' => null]
            );
    }
}
