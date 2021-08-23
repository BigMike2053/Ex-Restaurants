<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/security/login", name="app_login")
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
     * @Route("security/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/new/{username}/{password}/{role}", name="new_user")
     */
    public function new_user(string $username, string $password, string $role):Response
    {
        $newUser= new User();
        $newUser->setUsername($username);
        $newUser->setPassword($password);
        $newUser->setRoles([$role]);
        
        $this->getDoctrine()->getManager()->persist($newUser);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute("restaurants");
    }

    /**
     * @Route("/restaurants/register", name="register")
     */

    public function register(Request $request):Response
    {
        $form =$this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user=$form->getData();
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('restaurants');
            
        } else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash("danger","Vous n'êtes pas enregistré");
        }

        return $this->render('security/register.html.twig', ['form'=>$form->createView()
        ]);
    }
}
