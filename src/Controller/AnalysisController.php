<?php

namespace App\Controller;

use App\Entity\Analysis;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\AnalysisType;
use App\Form\EditAnalysisType;
use App\Repository\AnalysisRepository;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AnalysisController extends AbstractController
{
    /**
     * @Route("/analysis", name="analysis")
     */
    public function index(): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Access Denied');

        $analysisRepository = $this->getDoctrine()->getRepository(Analysis::class);
        $analysis = $analysisRepository->findAll();


        if ( $analysis )
        {
            return $this->render('analysis/index.html.twig', [
                    "analysis" => $analysis]
            );
        }
        else
            return $this->render('analysis/index.html.twig', [
                    'analysis' => null,
                ]
            );
    }

    /**
     * @Route("analysis/create", name="create_analysis")
     */
    public function create(Request $request)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Access Denied');

        $analysis = new Analysis();

        $form = $this->createForm(AnalysisType::class, $analysis);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $analysis = $form->getData();
            if ($posterFile = $form['image']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $posterFile->guessExtension();

                try {
                    $projectDir = $this->getParameter('kernel.project_dir');
                    $posterFile->move($projectDir . '/public/img/analysis', $filename);

                    if(empty($filename)){

                        $analysis->setImage("nofoto.jpg");

                    } else {

                        $analysis->setImage($filename);

                    }
                } catch (FileException $e) {
                    $this->addFlash('danger', $e->getMessage());
                    return $this->redirectToRoute('analysis');
                }
            }



            // Assign the date of analysis

            $user = $this->getUser();
            $analysis->setDate(new \DateTime());
            $analysis->setUser($user);

            // Save analysis on BD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($analysis);
            $entityManager->flush();

            //Logger

            $logger = new Logger('Analysis');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('Analysis ' . $analysis->getId() . ' successfully registered on ' . date("Y-m-d H:i:s", time()));

            // Flash message

            $this->addFlash('success', "User '". $analysis->getUser() ."' has been created analysis with ID: '" . $analysis->getId()."'.");


            return $this->redirectToRoute('home');
        }
        return $this->render('analysis/create_analysis.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/analysis/{id}/edit", name="edit_analysis")
     */
    public function editAnalysis(int $id, Request $request)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Access Denied');

        $analysisRepository = $this->getDoctrine()->getRepository(Analysis::class);
        $analysis = $analysisRepository->find($id);
        $form = $this->createForm(EditAnalysisType::class, $analysis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $analysis = $form->getData();
            if ($posterFile = $form['image']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $posterFile->guessExtension();

                try {
                    $projectDir = $this->getParameter('kernel.project_dir');
                    $posterFile->move($projectDir . '/public/img/analysis', $filename);
                    $analysis->setImage($filename);
                } catch (FileException $e) {
                    $this->addFlash(
                        'danger',
                        $e->getMessage()
                    );
                    return $this->redirectToRoute('home');

                }
            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($analysis);
            $entityManager->flush();
            $this->addFlash('success', "Analysis number " . $analysis->getId() . " has been edited successfully!");

            //LOGGER

            $logger = new Logger('Analysis');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('Analysis ' . $analysis->getId() . ' successfully edited on ' . date("Y-m-d H:i:s", time()));

            return $this->redirectToRoute('home');
        }
        return $this->render('analysis/edit_analysis.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     *@Route("/analysis/{id}/show", name="show_analysis", requirements={"id"="\d+"})
     */
    public function show(int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Access Denied');

        $analysisRepository = $this->getDoctrine()->getRepository(Analysis::class);
        $analysis = $analysisRepository->find($id);

        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $comment = $commentRepository->findBy(array('analysis' => $id),array('id' => 'ASC'),20 ,0);

        if ($analysis) {
            return $this->render('analysis/show_analysis.html.twig', ["analysis" => $analysis, "comment" => $comment]
            );
        } else
            return $this->render('analysis/show_analysis.html.twig', [
                    'analysis' => null,
                    "comment" => null]
            );
    }

    /**
     *@Route("/analysis/{id}/delete", name="delete_analysis", requirements={"id"="\d+"})
     */
    public function delete(int $id)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Access Denied');

        $analysisRepository = $this->getDoctrine()->getRepository(Analysis::class);
        $analysis = $analysisRepository->find($id);

        return $this->render('analysis/delete_analysis.html.twig', ["analysis" => $analysis]);

    }

    /**
     *@Route("/analysis/{id}/destroy", name="destroy_analysis", requirements={"id"="\d+"})
     */
    public function destroy(int $id)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Access Denied');

        $entityManager =$this->getDoctrine()->getManager();
        $analysisRepository = $this->getDoctrine()->getRepository(Analysis::class);
        $analysis = $analysisRepository->find($id);

        if ($analysis) {
            $entityManager->remove($analysis);
            $entityManager->flush();
            $this->addFlash('success', "Analysis " . $analysis->getId() . " has been deleted!");

            //LOGGER

            $logger = new Logger('Analysis');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info("Analysis " . $analysis->getId() . " has been deleted");

            return $this->redirectToRoute('analysis');
        }
        return $this->render('analysis/index.html.twig');
    }

}
