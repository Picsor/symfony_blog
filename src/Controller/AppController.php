<?php
// src/Controller/BlogController.php
namespace App\Controller;
use App\Entity\Article;
use App\Entity\Menu;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
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

// Update {article_num} by {id} will say to Symfony fetch using primary key of Entity
    #[Route('/blog/{id}', name: 'article', methods: ['GET'], requirements: ['id' => '\d+'])]
    // Get parameter from request
    
    public function article(Article $article = null, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $article_data = [];
        if ($article) {
            $article_data = ["title" => $article->getTitle(), "date" => $article->getDate(), "content" => $article->getContent()];
            $logger->info('Article data', $article_data);
        }
        // Articles
        $articles = $entityManager->getRepository(Article::class)->findAll();
        $articles_data = [];
        foreach ($articles as $article) {
            // append to article_data
            $articles_data[] = ["id" => $article->getId(), "title" => $article->getTitle(), "date" => $article->getDate(), "content" => $article->getContent()];
        } 

        return $this->render('blog_article.html.twig', [
        'article' => $article_data,
        'articles' => $articles_data
        ]);
    }

    // Route to link with and name to identify it
    #[Route('/menus', name: 'blog')]
    // Get parameter from request
    public function blog(EntityManagerInterface $entityManager): Response
    {
        // Articles
        $menus = $entityManager->getRepository(Menu::class)->findAll();
        $menus_data = [];
        foreach ($menus as $menu) {
            // append to article_data
            $menus_data[] = ['id'=> $menu->getId(),
                'name'=> $menu->getName(),
                'startDish'=> $menu->getStartDish(),
                'mainDish'=> $menu->getMainDish(),
                'desert'=> $menu->getDesert(),
            ];
        } 
        return $this->render('menus.html.twig', ['menus' => $menus_data]);

    }

    
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Case already logged in, redirect to dashboard
        if ($this->getUser()) {
            return $this->redirectToRoute('admin_dashboard');
        }
        // Else show login form
        
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    #[Route('reservation', name:'reservation', methods:["GET", "POST"])]
    public function makeReservation(EntityManagerInterface $entityManager, Request $request, LoggerInterface $loggerInterface) : Response {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $day = $reservation->getDate()->format("D");
            $time = (integer) $reservation->getDate()->format("H");
            if($this->getUser()){
                $reservation->setUser($this->getUser());
            }
            if($day == "Sun"){
                return new Response("Mauvais jour", 403);
            }
            if($time > 10 && $time < 15){
                $entityManager->persist($reservation);
                $entityManager->flush();
                return $this->redirectToRoute('home');
            }
            else if ($time > 17 && $time < 21){
                $entityManager->persist($reservation);
                $entityManager->flush();
                return $this->redirectToRoute('home');
            }
            $loggerInterface->info($reservation->getName());

            
            
        }
        return $this->render("reservation.html.twig", [
            "form"=> $form 
        ]);
    }

    #[Route('/reservations', name: 'my_reservations', methods:["GET"])]
    // Get parameter from request
    public function reservations(EntityManagerInterface $entityManager): Response
    {
        // Articles
        $user = $this->getUser();
        $reservations = $entityManager->getRepository(Reservation::class)->findBy([
            "user"=> $user,
        ]);
        $reservations_data = [];
        foreach ($reservations as $reservation) {
            $day = $reservation->getDate()->format("Y-m-d");
            $time = $reservation->getDate()->format("H");
            $minutes = $reservation->getDate()->format("i");
            $reservations_data[] = ['id'=> $reservation->getId(),
                'name'=> $reservation->getName(),
                'firsName'=> $reservation->getFirsName(),
                'email'=> $reservation->getEmail(),
                'date'=> $day." ".$time."h".$minutes,
            ];
        } 
        return $this->render('reservations.html.twig', ['reservations' => $reservations_data]);

    }
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}