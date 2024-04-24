<?php
// src/Controller/AdminController.php
namespace App\Controller;
// Allow to send a response
use Symfony\Component\HttpFoundation\Response;
// Allow to link to a route
use Symfony\Component\Routing\Attribute\Route;
// Allow additionnal methods like rendering template, redirect, generate url...
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Add prefix to all routes inside this controller
#[Route(path: '/admin')]
class AdminController extends AbstractController
{

    // Route to link with and name to identify it
    #[Route('/dashboard', name: 'admin_dashboard', methods: ['GET'])]
    // Get parameter from request
    public function admin_dashboard(): Response
    {
    // Return a response
        return $this->render('admin/dashboard.html.twig');
    }

}