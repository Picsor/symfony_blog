<?php
// src/Controller/AdminController.php
namespace App\Controller;
// Allow to send a response
use Symfony\Component\HttpFoundation\Response;
// Allow to link to a route
use Symfony\Component\Routing\Attribute\Route;
// Allow additionnal methods like rendering template, redirect, generate url...
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserProfileController extends AbstractController
{
    // Route to link with and name to identify it
    #[Route('/profile', name: 'profile', methods: ['GET'])]
    // Get parameter from request
    public function show_profile(): Response
    {
        // $this->getUser()->getUserIdentifier();
        // Return a response
        return $this->render('profile.html.twig');
    }

}