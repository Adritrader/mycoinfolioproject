<?php

namespace App\Controller;

use App\Entity\Analysis;
use App\Entity\Category;
use App\Entity\Comment;
use App\Form\CategoryType;
use App\Form\CommentType;
use App\Form\EditCommentType;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{

    /**
     * @Route("/comments", name="comments")
     */
    public function index(): Response
    {
        $commentsRepository = $this->getDoctrine()->getRepository(Comment::class);
        $comments = $commentsRepository->findAll();


        if ( $comments )
        {
            return $this->render('comments/index.html.twig', [
                    "comments" => $comments]
            );
        }
        else
            return $this->render('comments/index.html.twig', [
                    'comments' => null,
                ]
            );
    }
    /**
     * @Route("/analisys/comment/{id}/create", name="create_comment", requirements={"id"="\d+"})
     */
    public function create(Request $request, int $id)
    {
        $comment = new Comment();

        $analysisRepository = $this->getDoctrine()->getRepository(Analysis::class);
        $analysis = $analysisRepository->find($id);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();

            if ($posterFile = $form['picture']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $posterFile->guessExtension();

                try {
                    $projectDir = $this->getParameter('kernel.project_dir');
                    $posterFile->move($projectDir . '/public/img/analysis', $filename);
                    $comment->setPicture($filename);
                } catch (FileException $e) {
                    $this->addFlash('danger', $e->getMessage());
                    return $this->redirectToRoute('index');
                }
            }

            $comment->setDate(new \DateTime());

            //Obtaining the app user

            $user = $this->getUser();
            $comment->setUser($user);

            $comment->setAnalysis($analysis);

            // Save category on BD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            //Logger

            $logger = new Logger('Comment');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('Comment ' . $comment->getId() . ' successfully registered on ' . date("Y-m-d H:i:s", time()));

            // Flash message

            $this->addFlash('success', "Category has been created succesfully");


            return $this->redirectToRoute('show_analysis', array('id' => $id));
        }

        return $this->render('comments/create_comment.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/comment/{id}/edit", name="edit_comment")
     */
    public function editAnalysis(int $id, Request $request)
    {

        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $comment = $commentRepository->find($id);
        $form = $this->createForm(EditCommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            if ($posterFile = $form['image']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $posterFile->guessExtension();

                try {
                    $projectDir = $this->getParameter('kernel.project_dir');
                    $posterFile->move($projectDir . '/public/img/analysis', $filename);
                    $comment->setPicture($filename);
                } catch (FileException $e) {
                    $this->addFlash(
                        'danger',
                        $e->getMessage()
                    );
                    return $this->redirectToRoute('home');

                }
            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', "Analysis number " . $comment->getId() . " has been edited successfully!");

            //LOGGER

            $logger = new Logger('Analysis');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('Analysis ' . $comment->getId() . ' successfully edited on ' . date("Y-m-d H:i:s", time()));

            return $this->redirectToRoute('home');
        }
        return $this->render('comment/edit_analysis.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     *@Route("/comments/{id}/destroy", name="destroy_comment", requirements={"id"="\d+"})
     */
    public function destroy(int $id)
    {

        /*
        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Acceso restringido a administradores');*/


        $entityManager =$this->getDoctrine()->getManager();
        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $comment = $commentRepository->find($id);

        if ($comment) {
            $entityManager->remove($comment);
            $entityManager->flush();
            $this->addFlash('success', "Comment " . $comment->getId() . " has been deleted!");

            //LOGGER

            $logger = new Logger('Comment');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info("Comment " . $comment->getId() . " has been deleted");

            return $this->redirectToRoute('analysis');
        }
        return $this->render('analysis/index.html.twig');
    }
}
