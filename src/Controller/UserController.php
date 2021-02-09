<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\User;
use App\Form\LoginFormType;
use App\Form\UserInscriptionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

     /**
      * @Route("/connexion", name="connexion")
      */
     public function connexion(Request $request): Response
     {
         $form = $this->createForm(LoginFormType::class);
         $form->handleRequest($request);
         if($form->isSubmitted() && $form->isValid()) {
             $user = $this->getUser($form->get('pseudo')->getData());
             print_r($user->getPassword());
             if($user->getPassword() == $form->get('password')->getData()) {
                 return $this->redirectToRoute('home', [
                     'connexionEtat' => 'connect',
                     'role' => 'user',
                     'pseudo' => $user->getPseudo()
                 ]);
             }

         }
         return $this->render('user/login.html.twig', [
             'form_login' => $form->createView(),
                     ]);
     }

 

    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new Admin();
        $form = $this->createForm(UserInscriptionFormType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if($form->get('password')->getData('first-option') == $form->get('password')->getData('second-option')) {
                $user->setRoles($user->getRoles());
                $plainPassword = $form->get('password')->getData('first-option');
                $encoded = $encoder->encodePassword($user, $plainPassword);
                $user->setPassword($encoded);
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('home', ["username" => $user->getUsername()]);
            } else {
                return $this->redirectToRoute('inscription', ["error" => "password pas identique"]);
            }
    
        }
        return $this->render('user/inscription.html.twig', [
            'form_user' => $form->createView(),
        ]);
    }
}
