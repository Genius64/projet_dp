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
            $profilePicture = file_get_contents($profilePictureFile->getPathname());
            $user->setProfilePicture($profilePicture);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_profile');
    }

    #[Route('/profile/picture/{id}', name: 'app_profile_picture')]
    public function showProfilePicture(User $user): Response
    {
        $response = new Response($user->getProfilePicture());
        $response->headers->set('Content-Type', 'image/*');

        return $response;
    }
}