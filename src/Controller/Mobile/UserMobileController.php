<?php
namespace App\Controller\Mobile;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/mobile/user")
 */
class UserMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        if ($users) {
            return new JsonResponse($users, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"GET"})
     */
    public function add(Request $request, UserPasswordHasherInterface $userPasswordHasher): JsonResponse
    {
        $user = new User();

        return $this->manage($user, $request, false, $userPasswordHasher);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $userRepository->find((int)$request->get("id"));

        if (!$user) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($user, $request, true, $userPasswordHasher);
    }
    use Symfony\Component\HttpFoundation\Response;

    public function manage($user, $request, $isEdit, $userPasswordHasher): JsonResponse
{
    if (!$isEdit) {
        $checkEmail = $this->getDoctrine()->getRepository(User::class)
            ->findOneBy(["email" => $request->get("email")]);

        if ($checkEmail) {
            return new JsonResponse("Email already exist", 203);
        }
    }

    if (!$isEdit) {
        $email = $user->getEmail();

        $user->setPassword($userPasswordHasher->hashPassword($user, $request->get("password")));
    }

    $user->setNom($request->get("nom"));
    $user->setPrenom($request->get("prenom"));
    $user->setEmail($request->get("email"));
    $user->setNumTel($request->get("numTel"));
    $user->setDateNaissc(DateTime::createFromFormat("d-m-Y", $request->get("dateNaissance")));
    $user->setAdresse($request->get("adresse"));
    $user->setSexe($request->get("sexe"));
    $user->setRole($request->get("role"));

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($user);
    $entityManager->flush();

    return new JsonResponse($user, 200);
}


    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find((int)$request->get("id"));

        if (!$user) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/verif", methods={"POST"})
     */
    public function verif(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $userRepository->findOneBy(["email" => $request->get("email")]);

        if ($user) {
            if ($userPasswordHasher->isPasswordValid($user, $request->get("password"))) {
                return new JsonResponse($user, 200);
            } else {
                return new JsonResponse("user found but pass wrong", 203);
            }
        } else {
            return new JsonResponse([], 204);
        }
    }


}
