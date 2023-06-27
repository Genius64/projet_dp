<?php
namespace App\Controller;

use App\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function register(Request $request)
    {
        $form = $this->createForm(RegistrationFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Les données du formulaire sont valides, vous pouvez les traiter ici
            $data = $form->getData();

            // Faites quelque chose avec les données (par exemple, les enregistrer en base de données)

            // Redirigez l'utilisateur vers une autre page ou affichez un message de réussite
            return $this->redirectToRoute('success');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/inscription/success", name="success")
     */
    public function success()
    {
        return $this->render('registration/success.html.twig');
    }
}