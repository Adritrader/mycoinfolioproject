<?php


namespace App\Controller;

use App\Entity\Analysis;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Contain;
use App\Entity\Crypto;
use App\Entity\Portfolio;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\AnalysisRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\ContainRepository;
use App\Repository\CryptoRepository;
use App\Repository\PortfolioRepository;
use App\Repository\UserRepository;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/api/v1")
 */
class ApiController extends AbstractController
{
    //Users Endpoints

    /**
     * @Route("/users", name="api_users", methods={"GET"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function users(Request $request, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->findAll();

        return new JsonResponse($user, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/user", name="api_user_show", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function showUser(Request $request,  ?User $user): JsonResponse
    {

        if (!empty($user))
            return new JsonResponse($user, Response::HTTP_OK);

        else
            return new JsonResponse("error", Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/register", name="api_user_register", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher): JsonResponse
    {
        $user = new User();
        $data = [];
        if ($content = $request->getContent()) {
            $data = json_decode($content, true);
        }
        try {
            $user->setUsername($data["username"]);
            $user->setEmail($data["email"]);
            $user->setAvatar($data["avatar"]);
            $user->setNewsletter($data["newsletter"]);
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $data["password"]
                )
            );
            // Assigning update date, its the creation time by default.
            $user->setCreatedAt(new \DateTimeImmutable());
            /*
            // Update date, null default

            $updated = date("Y-m-d H:i:s", time());
            $user->setModifiedAt($updated);
            */
        } catch (\Exception $e) {
            $error["code"] = $e->getCode();
            $error["message"] = $e->getMessage();
            return new JsonResponse($error, Response::HTTP_BAD_REQUEST);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return new JsonResponse($user, Response::HTTP_CREATED);

        //{"id": 5, "username": "alberto","password": "1234",
        // "email" : "alberto@gmail.com", "avatar": "nophoto.jpg", "newsletter": 0}
    }



    /**
     * @Route("/edit/{id}/user", name="api_update_user", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse {

        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->find($id);
        //$user = $this->userRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['username']) ? true : $user->setUsername($data['username']);
        empty($data['email']) ? true : $user->setEmail($data['email']);
        empty($data['avatar']) ? true : $user->setAvatar($data['avatar']);
        empty($data['newsletter']) ? true : $user->setAvatar($data['newsletter']);

        //Assigning modified time

        $user->setAvatar($data['avatar']);

        return "";

    }


    //Analysis Endpoints

    /**
     * @Route("/analysis", name="api_analysis", methods={"GET"})
     * @param Request $request
     * @param AnalysisRepository $analysisRepository
     * @return JsonResponse
     */
    public function analysis(Request $request, AnalysisRepository $analysisRepository): JsonResponse
    {
        $analysis = $analysisRepository->findAll();

        return new JsonResponse($analysis, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/analysis", name="api_analysis_show", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function showAnalysis(Request $request,  ?Analysis $analysis): JsonResponse
    {

        if (!empty($analysis))
            return new JsonResponse($analysis, Response::HTTP_OK);

        else
            return new JsonResponse("error", Response::HTTP_NOT_FOUND);
    }



    public function createAnalysis(Request $request): JsonResponse
    {
        $analysis = new Analysis();
        $data = [];
        if ($content = $request->getContent()) {
            $data = json_decode($content, true);
        }

        try {
            $analysis->setTitle($data["title"]);
            $analysis->setOverview($data["overview"]);
            $analysis->setTagline($data["tagline"]);
            $analysis->setPoster($data["poster"]);
            $analysis->setReleaseDate(new \DateTime($data["release_date"]));

        } catch (\Exception $e) {
            $error["code"] = $e->getCode();
            $error["message"] = $e->getMessage();
            return new JsonResponse($error, Response::HTTP_BAD_REQUEST);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($analysis);
        $em->flush();

        return new JsonResponse($analysis, Response::HTTP_CREATED);
    }

    //Comments Endpoint


    /**
     * @Route("/comments", name="api_comments", methods={"GET"})
     * @param Request $request
     * @param CommentRepository $commentRepository
     * @return JsonResponse
     */
    public function comments(Request $request, CommentRepository $commentRepository): JsonResponse
    {
        $comments = $commentRepository->findAll();

        return new JsonResponse($comments, Response::HTTP_OK);

    }

    /**
     * @Route("/{id}/comment", name="api_comment_show", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function showComment(Request $request,  ?Comment $comment): JsonResponse
    {

        if (!empty($comment))
            return new JsonResponse($comment, Response::HTTP_OK);

        else
            return new JsonResponse("error", Response::HTTP_NOT_FOUND);
    }

    //Contain Endpoints

    /**
     * @Route("/contains", name="api_contain", methods={"GET"})
     * @param Request $request
     * @param ContainRepository $containRepository
     * @return JsonResponse
     */
    public function contains(Request $request, ContainRepository $containRepository): JsonResponse
    {
        $contains = $containRepository->findAll();

        return new JsonResponse($contains, Response::HTTP_OK);

    }

    /**
     * @Route("/{id}/contain", name="api_contain_show", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function showContain(Request $request,  ?Contain $contain): JsonResponse
    {

        if (!empty($contain))
            return new JsonResponse($contain, Response::HTTP_OK);

        else
            return new JsonResponse("error", Response::HTTP_NOT_FOUND);
    }

    //Crypto Endpoint

    /**
     * @Route("/cryptos", name="api_crypto", methods={"GET"})
     * @param Request $request
     * @param CryptoRepository $cryptoRepository
     * @return JsonResponse
     */
    public function cryptos(Request $request, CryptoRepository $cryptoRepository): JsonResponse
    {
        $cryptos = $cryptoRepository->findAll();

        return new JsonResponse($cryptos, Response::HTTP_OK);

    }

    /**
     * @Route("/{id}/crypto", name="api_crypto_show", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function showCrypto(Request $request,  ?Crypto $crypto): JsonResponse
    {

        if (!empty($crypto))
            return new JsonResponse($crypto, Response::HTTP_OK);

        else
            return new JsonResponse("error", Response::HTTP_NOT_FOUND);
    }

    //Portfolio Endpoints

    /**
     * @Route("/portfolios", name="api_portolios", methods={"GET"})
     * @param Request $request
     * @param PortfolioRepository $portfolioRepository
     * @return JsonResponse
     */
    public function portfolios(Request $request, PortfolioRepository $portfolioRepository): JsonResponse
    {
        $portfolios = $portfolioRepository->findAll();

        return new JsonResponse($portfolios, Response::HTTP_OK);

    }

    /**
     * @Route("/{id}/portfolio", name="api_portfolio_show", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function showPortfolio(Request $request,  ?Portfolio $portfolio): JsonResponse
    {

        if (!empty($portfolio))
            return new JsonResponse($portfolio, Response::HTTP_OK);

        else
            return new JsonResponse("error", Response::HTTP_NOT_FOUND);
    }

    //Category Endpoints

    /**
     * @Route("/category", name="api_categories", methods={"GET"})
     * @param Request $request
     * @param CategoryRepository $categoriesRepository
     * @return JsonResponse
     */
    public function categories(Request $request, CategoryController $categoriesRepository): JsonResponse
    {
        $categories = $categoriesRepository->findAll();

        return new JsonResponse($categories, Response::HTTP_OK);

    }

    /**
     * @Route("/{id}/category", name="api_portfolio_show", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function showCategories(Request $request,  ?Category $category): JsonResponse
    {

        if (!empty($category))
            return new JsonResponse($category, Response::HTTP_OK);

        else
            return new JsonResponse("error", Response::HTTP_NOT_FOUND);
    }

}