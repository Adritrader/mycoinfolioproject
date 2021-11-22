<?php

namespace App\Controller;

use App\Entity\User;
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
     * @Route("users/create", name="create_user")
     */
    public function createBack(Request $request, UserPasswordHasherInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            if ($posterFile = $form['avatar']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $posterFile->guessExtension();

                try {
                    $projectDir = $this->getParameter('kernel.project_dir');
                    $posterFile->move($projectDir . '/public/img/', $filename);

                    if(empty($filename)){

                        $user->setAvatar("nofoto.jpg");

                    } else {

                        $user->setAvatar($filename);

                    }
                } catch (FileException $e) {
                    $this->addFlash('danger', $e->getMessage());
                    return $this->redirectToRoute('index');
                }
            }

            //Encriptamos la contraseña

            $password = $user->getPassword();
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ["cost" => 10]);
            $user->setPassword($hashedPassword);


            // Asignamos la fecha de actualización, por defecto es la fecha de creación del usuario

            $user->setCreatedAt(new \DateTime());


            $updated = date("Y-m-d H:i:s", time());
            $user->setUpdatedAt($updated);

            // Guardamos el usuario en la BD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user );
            $entityManager->flush();

            //Logger

            $logger = new Logger('User');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('User ' . $user->getUsername() . ' successfully registered on ' . date("Y-m-d H:i:s", time()));

            // Flash message

            $this->addFlash('success', "Se ha registrado correctamente");


            return $this->redirectToRoute('users');
        }
        return $this->render('user/create_user.html.twig', array(
            'form' => $form->createView()));
    }


    /**
     * @Route("/users/{id}/profile", name="show_user", requirements={"id"="\d+"})
     */
    public function showUserBack(int $id)
    {

        /*$this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Acceso restringido a administradores');*/
        $usuarioRepository = $this->getDoctrine()->getRepository(User::class);
        $usuario = $usuarioRepository->find($id);
        if ($usuario)
        {
            return $this->render('user/show_user.html.twig', ["user"=>$usuario]
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

        $usuarioRepository = $this->getDoctrine()->getRepository(User::class);
        $usuarios = $usuarioRepository->find($id);
        $form = $this->createForm(EditUserType::class, $usuarios);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usuarios = $form->getData();
            if ($posterFile = $form['avatar']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $posterFile->guessExtension();

                try {
                    $projectDir = $this->getParameter('kernel.project_dir');
                    $posterFile->move($projectDir . '/public/img/', $filename);
                    $usuarios->setAvatar($filename);
                } catch (FileException $e) {
                    $this->addFlash(
                        'danger',
                        $e->getMessage()
                    );
                    return $this->redirectToRoute('index');
                }
            }

            $usuarios->setModifiedAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usuarios);
            $entityManager->flush();
            $this->addFlash('success', "User " . $usuarios->getUsername() . " has been successfully edited!");

            //LOGGER

            $logger = new Logger('usuario');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('User ' . $usuarios->getUsername() . ' has been edited successfully');

            return $this->redirectToRoute('index');
        }
        return $this->render('user/edit_user.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     *@Route("/users/{id}/delete", name="delete_user", requirements={"id"="\d+"})
     */
    public function delete(int $id)
    {




        /*
        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Acceso restringido a administradores');*/

        $usuarioRepository = $this->getDoctrine()->getRepository(User::class);
        $usuario = $usuarioRepository->find($id);

        return $this->render('user/delete_user.html.twig', ["user" => $usuario]);

    }

    /**
     *@Route("/users/{id}/destroy", name="destroy_user", requirements={"id"="\d+"})
     */
    public function destroy(int $id)
    {

        /*
        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Acceso restringido a administradores');*/


        $entityManager =$this->getDoctrine()->getManager();
        $usuarioRepository = $this->getDoctrine()->getRepository(User::class);
        $usuario = $usuarioRepository->find($id);

        if ($usuario) {
            $entityManager->remove($usuario);
            $entityManager->flush();
            $this->addFlash('success', "El usuario " . $usuario->getUsername() . " ha sido eliminado correctamente!");

            //LOGGER

            $logger = new Logger('usuario');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info("El usuario " . $usuario->getUsername() . " ha sido eliminado");

            return $this->redirectToRoute('index');
        }
        return $this->render('user/create_category.html.twig');
    }

}
