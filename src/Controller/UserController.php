<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{
    private $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/profile", name="profile")
     * @isGranted("ROLE_USER")
     */
    public function profile(Request $request) {

        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $concert = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($concert);
            $manager->flush();

            return $this->redirectToRoute("home");
        }

        return $this->render("user/form.twig", ["form" => $form->createView()]);
    }

    /**
    * @Route("/admin/user",name="user_admin")
     * @isGranted("ROLE_ADMIN")
    */
    public function adminAction(UserRepository $repository) {
        $users = $repository->findAll();

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                "email" => $user->getEmail(),
                "firstName" => $user->getFirstName(),
                "lastName" => $user->getLastName(),
                "is_admin" => in_array(User::ROLE_ADMIN,$user->getRoles())
            ];
        }
        return $this->render("user/admin.twig", ["users" => $data]);
    }

    /**
    * @Route("/admin/user/admin",name="user_admin_role", methods={"POST"})
    */
    public function adminRole(Request $request, EntityManagerInterface $manager, UserRepository $repository): Response
    {
        $body = json_decode($request->getContent());
        $user = $repository->findOneBy(["email" => $body->email]);
        $roles = [
            User::ROLE_USER
        ];
        if ($body->is_admin) {
            $roles[] = User::ROLE_ADMIN;
        }

        $user->setRoles($roles);

        $manager->persist($user);
        $manager->flush();

        return new Response();
    }

    /**
    * @Route("/pwdverif",name="verify_password", methods={"POST"})
    */
    public function verifyPassword(Request $request, EntityManagerInterface $manager, UserRepository $repository)
    {
        $body = $request->getContent();
        $params = explode("&",$body);
        $passwords = [];
        foreach ($params as $elt) {
            $passwords[] = explode("=",$elt)[1];
        }
        var_dump($passwords);
        if ($passwords[0] === $passwords[1]) {
            $user = $repository->findOneBy(["email" => $this->getUser()->getUsername()]);
            $user->setPassword($this->hasher->hashPassword($user,$passwords[0]));
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute("profile");
        } else {
            return $this->render("user/password_change.twig", ["error" => "les mots de passes ne correspondent pas"]);
        }

    }

    /**
     * @Route("/password",name="change_password", methods={"GET"})
     */
    public function changePassword(): Response
    {
        return $this->render("user/password_change.twig");
    }
}
