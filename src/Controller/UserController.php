<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Panier;
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
     * @Route("profile/compte", name="compte")
     */
    public function compte(): Response
    {
        return $this->render('compte/index.html.twig', [
            'connexionEtat' => 'connect',
        ]);
    }



    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new Admin();
        $panier = new Panier();
        $form = $this->createForm(UserInscriptionFormType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if($form->get('password')->getData('first-option') == $form->get('password')->getData('second-option')) {
                $user->setRoles($user->getRoles());
                $plainPassword = $form->get('password')->getData('first-option');
                $encoded = $encoder->encodePassword($user, $plainPassword);
                $user->setPassword($encoded);
                $panier->setAdmin($user);
                $entityManager->persist($user);
                $entityManager->flush();
                $entityManager->persist($panier);
                $entityManager->flush();
                return $this->redirectToRoute('home', ["username" => $user->getUsername()]);
            } else {
                return $this->redirectToRoute('inscription', ["error" => "password pas identique"]);
            }
    
        }
        return $this->render('user/inscription.html.twig', [
            'form_user' => $form->createView(),
            "connexionEtat" => ""
        ]);
    }
}
