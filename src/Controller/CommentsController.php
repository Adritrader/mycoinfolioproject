<?php

namespace App\Controller;

use App\Entity\Analysis;
use App\Entity\Category;
use App\Entity\Comment;
use App\Form\CategoryType;
use App\Form\CommentType;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            return $this->render('analysis/index.html.twig', [
                    "comments" => $comments]
            );
        }
        else
            return $this->render('analysis/index.html.twig', [
                    'comments' => null,
                ]
            );
    }
    /**
     * @Route("/analisys/comment/{id}/create", name="create_comment", requirements={"id"="\d+"})
     */
    public function create(Request $request)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();

            $comment->setDate(new \DateTime());

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


            return $this->redirectToRoute('index');
        }


        return $this->render('comments/create_comment.html.twig', array(
            'form' => $form->createView()));
    }
}
