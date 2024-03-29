<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserFormType;
use App\Form\EditUserType;
use App\Form\UserType;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="users")
     */
    public function index(): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Access Denied');

        $usersRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $usersRepository->findAll();

        if ( $users )
        {
            return $this->render('user/index.html.twig', [
                    "users" => $users]
            );
        }
        else
            return $this->render('user/index.html.twig', [
                    'users' => null,
                ]
            );
    }

    /**
     * @Route("/users/{id}/profile", name="show_user", requirements={"id"="\d+"})
     */
    public function showUserBack(int $id)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Access Denied');

        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->find($id);
        if ($user)
        {
            return $this->render('user/show_user.html.twig', ["user"=>$user]
            );
        }
        else
            return $this->render('user/show_user.html.twig', [
                    'user' => null]
            );
    }



    /**
     * @Route("/users/{id}/edit", name="edit_user")
     */
    public function editUser(int $id, Request $request)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Access Denied');

        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->find($id);
        $form = $this->createForm(EditUserFormType::class, $users);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $users = $form->getData();
            if ($posterFile = $form['avatar']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $posterFile->guessExtension();

                try {
                    $projectDir = $this->getParameter('kernel.project_dir');
                    $posterFile->move($projectDir . '/public/img/avatars', $filename);
                    $users->setAvatar($filename);
                } catch (FileException $e) {
                    $this->addFlash(
                        'danger',
                        $e->getMessage()
                    );
                    return $this->redirectToRoute('home');
                }
            }

            $users->setModifiedAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($users);
            $entityManager->flush();
            $this->addFlash('success', "User " . $users->getUsername() . " has been successfully edited!");

            //LOGGER

            $logger = new Logger('User');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('User ' . $users->getUsername() . ' has been edited successfully');

            return $this->redirectToRoute('home');
        }
        return $this->render('user/edit_user.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     *@Route("/users/{id}/delete", name="delete_user", requirements={"id"="\d+"})
     */
    public function delete(int $id)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Access Denied');

        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->find($id);

        return $this->render('user/delete_user.html.twig', ["user" => $user]);

    }

    /**
     *@Route("/users/{id}/destroy", name="destroy_user", requirements={"id"="\d+"})
     */
    public function destroy(int $id)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Access Denied');


        $entityManager =$this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->find($id);

        if ($user) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', "User " . $user->getUsername() . " has been deleted correctly!");

            //LOGGER

            $logger = new Logger('usuario');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info("User " . $user->getUsername() . " has been deleted");

            return $this->redirectToRoute('home');
        }
        return $this->render('user/create_category.html.twig');
    }

}
