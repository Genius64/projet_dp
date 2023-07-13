<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        // Affiche la vue Twig 'profile/index.html.twig' avec le nom du contrôleur
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/profile/update', name: 'app_profile_update', methods: ['POST'])]
    public function updateProfile(Request $request): Response
    {
        // Obtient l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Récupère les nouvelles valeurs du formulaire de mise à jour du profil
        $username = $request->request->get('username');
        $email = $request->request->get('email');
        $description = $request->request->get('description');
        $profilePictureFile = $request->files->get('profile_picture');

        // Met à jour les propriétés de l'utilisateur avec les nouvelles valeurs
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setDescription($description);

        // Vérifie si un nouveau fichier de profile_picture a été téléchargé
        if ($profilePictureFile) {
            $profilePicture = file_get_contents($profilePictureFile->getPathname());
            $user->setProfilePicture($profilePicture);
        }

        // Persiste les modifications de l'utilisateur en base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Redirige vers la page de profil
        return $this->redirectToRoute('app_profile');
    }

    #[Route('/profile/picture/{id}', name: 'app_profile_picture')]
    public function showProfilePicture(User $user): Response
    {
        // Récupère l'image de profil de l'utilisateur
        $profilePicture = $user->getProfilePicture();

        // Crée une réponse HTTP avec le contenu de l'image
        $response = new Response($profilePicture);
        $response->headers->set('Content-Type', 'image/*');

        // Retourne la réponse HTTP contenant l'image
        return $response;
    }
}