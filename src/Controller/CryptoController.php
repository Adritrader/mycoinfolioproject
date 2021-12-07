<?php

namespace App\Controller;

use App\Entity\Contain;
use App\Entity\Crypto;
use App\Entity\Portfolio;
use App\Entity\User;
use App\Form\CryptoType;
use App\Form\EditCryptoType;
use App\Form\EditUserType;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CryptoController extends AbstractController
{
    /**
     * @Route("/portfolio/{id}/show/crypto", name="create_crypto", requirements={"id"="\d+"})
     */
    public function create(Request $request, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Access Denied');

        $crypto = new Crypto();
        $contain = new Contain();

        $portfolioRepository = $this->getDoctrine()->getRepository(Portfolio::class);
        $portfolio = $portfolioRepository->find($id);

        $form = $this->createForm(CryptoType::class, $crypto);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $crypto = $form->getData();
            $contain->setCrypto($crypto);
            $contain->setPortfolio($portfolio);

            // Save category on BD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($crypto);
            $entityManager->persist($contain);
            $entityManager->flush();

            //Logger

            $logger = new Logger('Crypto');
            $logger2 = new Logger('Contain');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('Crypto ' . $crypto->getName() . ' successfully registered on ' . date("Y-m-d H:i:s", time()));
            $logger2->info('Contain ' . $crypto->getId() . ' successfully registered on ' . date("Y-m-d H:i:s", time()));

            // Flash message

            $this->addFlash('success', "Crypto has been added succesfully");


            return $this->redirectToRoute('home');
        }


        return $this->render('crypto/index.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/portfolio/{id}/show/edit/{id_crypto}/crypto", name="edit_crypto")
     */
    public function edit(int $id, int $id_crypto, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Access Denied');

        $cryptoRepository = $this->getDoctrine()->getRepository(Crypto::class);
        $crypto = $cryptoRepository->find($id_crypto);

        $form = $this->createForm(EditCryptoType::class, $crypto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $crypto = $form->getData();



            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($crypto);
            $entityManager->flush();
            $this->addFlash('success', "Crypto " . $crypto->getName() . " has been edited correctly!");

            //LOGGER

            $logger = new Logger('crypto');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('Crypto with id:' . $crypto->getId() . ', has been edited');

            return $this->redirectToRoute('show_portfolio', array('id' => $id));
        }
        return $this->render('crypto/index.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/portfolio/{id}/crypto/{id_crypto}/show", name="show_crypto", requirements={"id"="\d+"})
     */
    public function show(int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Access Denied');

        $portfolioRepository = $this->getDoctrine()->getRepository(Portfolio::class);
        $portfolio = $portfolioRepository->find($id);

        $containRepository = $this->getDoctrine()->getRepository(Contain::class);
        $contain = $containRepository->findBy(array('portfolio' => $id),array('id' => 'ASC'),1 ,0)[0];


        if ($portfolio)
        {
            return $this->render('portfolio/show_portfolio.html.twig', ["portfolio"=>$portfolio, "contain" => $contain]
            );
        }
        else
            return $this->render('portfolio/show_portfolio.html.twig', [
                    'portfolio' => null,
                    "contain" => null]
            );
    }

}
