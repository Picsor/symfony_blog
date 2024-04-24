<?php
// src/Controller/BlogController.php
namespace App\Controller;
use App\Entity\Menu;
use App\Entity\Reservation;
use App\Entity\User;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use SebastianBergmann\Environment\Console;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// Allow to link to a route
use Symfony\Component\Routing\Attribute\Route;
// Allow additionnal methods like rendering template, redirect, generate url...
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AppController extends AbstractController
{
    // Route to link with and name to identify it
    #[Route('/', name: 'home')]
    // Get parameter from request
    public function home(LoggerInterface $logger): Response
    {
        return $this->render('home.html.twig');
    }

    // Route to link with and name to identify it
    #[Route('/menus', name: 'menus')]
    // Get parameter from request
    public function menus(EntityManagerInterface $entityManager): Response
    {
        // Articles
        $menus = $entityManager->getRepository(Menu::class)->findAll();
        $menus_data = [];
        foreach ($menus as $menu) {
            // append to article_data
            $menus_data[] = ['id'=> $menu->getId(),
                'name'=> $menu->getName(),
                'starter'=> $menu->getStarter(),
                'dish'=> $menu->getDish(),
                'dessert'=> $menu->getDessert(),
                'price'=> $menu->getPrice()
            ];
        } 
        return $this->render('menus.html.twig', ['menus' => $menus_data]);
    }

    #[Route('/reservations', name: 'reservations')]
    public function reservations(EntityManagerInterface $entityManager): Response
    {
        $user =  $this->getUser();
        if(!$user) {
            return $this->redirectToRoute('app_login');
        }
        // Articles
        $reservations = $entityManager->getRepository(Reservation::class)->findBy(['user'=>$user]);
        $reservations_data = [];
        foreach ($reservations as $reserv) {
            // append to article_data
            $reservations_data[] = ['id'=> $reserv->getId(),
                'day' => $reserv->getDate()->format('Y-m-d'),
                'time' => $reserv->getDate()->format("H:i"),
                'firstname'=> $reserv->getFirstname(),
                'lastname'=> $reserv->getLastname(),
                'email' => $reserv->getEmail()
            ];
        } 
        return $this->render('user_reservation/index.html.twig', ['reservations' => $reservations_data]);

    }

    #[Route('/reserve', name: 'make_reservation')]
    // Get parameter from request
    public function reserve(Request $request,
        EntityManagerInterface $entityManager,
        LoggerInterface $lgi): Response
    {
        // Reservations
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $day = $reservation->getDate()->format('D');
            $time = (Integer) $reservation->getDate()->format('H');
            
            if ($day == "Sun") {
                $lgi->critical("WRONG DAY".$day);
                return new Response("Can't be sunday", 403);
            }
            if (!(($time >= 11 && $time <= 14) || ($time >= 18 && $time <= 20))) {
                $lgi->critical("WRONG TIME".$time);
                return new Response("Available times are: 11-14 18-20", 403);
            }
            
            if($this->getUser()){
                $lgi->critical("NO USR");
                $reservation->setUser($this->getUser());
            }
            
            $entityManager->persist($reservation);
            $entityManager->flush();
            $lgi->critical("SUCCESS redirecting...");
            return $this->redirectToRoute('reservations', [], Response::HTTP_SEE_OTHER);
        }

        
        
        return $this->render('user_reservation/new.html.twig', ['form'=> $form]);

    }

    
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $user = $this->getUser();
        // Case already logged in, redirect to dashboard
        if ($this->getUser() && in_array("ROLE_ADMIN", $user->getRoles())) {
            return $this->redirectToRoute('admin_dashboard');
        }
        // Else show login form
        
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}