<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{

    /**
     *@Route("/category/create", name="create_category")
     */
    public function create(Request $request)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();


            // Save category on BD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            //Logger

            $logger = new Logger('Category');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('Category ' . $category->getName() . ' successfully registered on ' . date("Y-m-d H:i:s", time()));

            // Flash message

            $this->addFlash('success', "Category has been created succesfully");


            return $this->redirectToRoute('index');
        }


        return $this->render('category/create_category.html.twig', array(
            'form' => $form->createView()));
    }
}
