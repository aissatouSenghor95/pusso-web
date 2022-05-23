<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GuestController extends AbstractController
{
    #[Route('/guest/politique-confidentialite', name: 'guest_politique_confidentialite')]
    public function index(): Response
    {
        return $this->render('guest/politique-confidentialite.html.twig', [
        ]);
    }
}
