<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // Crée une nouvelle instance de l'entité User
        $user = new User();

        // Crée le formulaire d'inscription en utilisant la classe RegistrationFormType et l'entité User
        $form = $this->createForm(RegistrationFormType::class, $user);

        // Traite la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode le mot de passe en utilisant le UserPasswordHasherInterface
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Persiste l'utilisateur en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirige vers la page de connexion après une inscription réussie
            return $this->redirectToRoute('app_login');
        }

        // Affiche la vue Twig 'registration/register.html.twig' avec le formulaire de registre
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}