<?php
// src/Controller/AdminController.php
namespace App\Controller;
// Allow to send a response
use App\Entity\VisitorIP;
use Doctrine\DBAL\Schema\Visitor\Visitor;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
// Allow to link to a route
use Symfony\Component\Routing\Attribute\Route;
// Allow additionnal methods like rendering template, redirect, generate url...
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// Add prefix to all routes inside this controller
#[Route(path: '/admin/monitoring')]
class MonitoringController extends AbstractController
{

    // Route to link with and name to identify it
    #[Route('/', name: 'monitoring_dashboard', methods: ['GET'])]
    // Get parameter from request
    public function admin_dashboard(EntityManagerInterface $em): Response
    {
    // Return a response
        $ips = $em->getRepository(VisitorIP::class)->findAll();
        return $this->render('admin/monitoring.html.twig', [
            'ips'=> $ips
        ]);
    }

}