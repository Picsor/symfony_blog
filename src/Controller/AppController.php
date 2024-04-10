<?php
// src/Controller/BlogController.php
namespace App\Controller;
use App\Entity\Article;
use App\Entity\Visit;
use Doctrine\ORM\EntityManagerInterface;
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


        $visit = new Visit();
        $visit->setArticleId($article->getId());
        $visit->setUserId($this->getUser()->getId());
        $entityManager->persist($visit);
        $entityManager->flush();

        return $this->render('blog_article.html.twig', [
        'article' => $article_data,
        'articles' => $articles_data
        ]);
    }

    // Route to link with and name to identify it
    #[Route('/blog', name: 'blog')]
    // Get parameter from request
    public function blog(EntityManagerInterface $entityManager): Response
    {
        // Articles
        $articles = $entityManager->getRepository(Article::class)->findAll();
        $articles_data = [];
        foreach ($articles as $article) {
            // append to article_data
            $articles_data[] = ["id" => $article->getId(), "title" => $article->getTitle(), "date" => $article->getDate(), "content" => $article->getContent()];
        } 
        return $this->render('blog.html.twig', ['articles' => $articles_data]);

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

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}