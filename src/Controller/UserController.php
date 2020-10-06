<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\RegisterFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="regisiter")
     *
     * @param EntityManagerInterface $em
     * @param Request $request
     *
     * @return Response
     */
    public function registerAction(EntityManagerInterface $em, Request $request): Response
    {
        $error = '';
        $form = $this->createForm(RegisterFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userExists = $em->getRepository(User::class)
                ->findOneBy(['username' => $form->getData()['username']]);
            if (!$userExists) {
                $data = $form->getData();
                $user = new User();
                $user
                    ->setUsername($data['username'])
                    ->setEmail($data['email'])
                    ->setPassword($data['password']);
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('home');
            } else {
                $error = 'User exists already.';
            }
        }

        return $this->render('register.html.twig', [
            'error' => $error,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users", name="show_users")
     *
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function showUsers(EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->findAll();
        return $this->render('user/show.html.twig', [
            'users' => $user
        ]);
    }

    /**
     * @Route("users/profile", name="user_profile")
     * @return Response
     */
    public function showProfile(): Response
    {
        return $this->render('user/profile.html.twig');
    }

    /**
     * @Route("/", name="home")
     *
     * @param RequestStack $stack
     *
     * @return Response
     */
    public function homeAction(RequestStack $stack): Response
    {

        return $this->render('home.html.twig');
    }
}