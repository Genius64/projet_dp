<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/profile/update', name: 'app_profile_update', methods: ['POST'])]
    public function updateProfile(Request $request): Response
    {
        $user = $this->getUser();

        $username = $request->request->get('username');
        $email = $request->request->get('email');
        $description = $request->request->get('description');
        $profilePictureFile = $request->files->get('profile_picture');

        // Mettez à jour les propriétés de l'utilisateur avec les nouvelles valeurs
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setDescription($description);

        // Vérifiez si un nouveau fichier de profile_picture a été téléchargé
        if ($profilePictureFile) {
            // Gérez le téléchargement du fichier et l'enregistrement dans votre système de fichiers
            // Génération d'un nom de fichier unique en utilisant la date et l'heure actuelles
            $newFilename = '../../public/assets/images/profile_picture'.date('Ymd_His') . '_' . uniqid() . '.' . $profilePictureFile->guessExtension();// Générez un nom de fichier unique
            // Code pour déplacer le fichier téléchargé vers votre emplacement de stockage et enregistrer le chemin d'accès dans la base de données
            $user->setProfilePicture($newFilename);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_profile');
    }
}
