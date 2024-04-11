<?php
// src/Controller/BlogController.php
namespace App\Controller;
use App\Entity\Article;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
// Allow to link to a route
use Symfony\Component\Routing\Attribute\Route;
// Allow additionnal methods like rendering template, redirect, generate url...
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class ApiController extends AbstractController
{
    #[Route('/api/articles', name: 'api_articles', methods: ['GET'])]
    // Get parameter from request
    public function api_articles(EntityManagerInterface $entityManager): Response
    {
        try {
            // Get articles from DB
            $articles = $entityManager->getRepository(Article::class)->findAll();
            $data = [];
            // Determine info we want our api to return
            foreach ($articles as $article) {
                // append to article_data
                $data["article_".$article->getId()] = ["id" => $article->getId(),
                "title" => $article->getTitle(), 
                "date" => $article->getDate(),
                "content" => $article->getContent(),
                "user" => $article->getUser()->getUsername()];
            } // Format to json
            $json = json_encode($data);
            // Send response
            return new Response($json, 200, [
            'Content-Type' => 'application/json'
            ]);
        } catch(e) {
            // Case error
            return new Response('[]', 500, [
            'Content-Type' => 'application/json'
            ]);
        }
    }

    
    #[Route(path:"/api/article/{id}", name:"api_article", methods: ['GET'])]
    public function api_article(int $id, EntityManagerInterface $entityManager): Response {
        try {
            // Get list of articles
            $article = $entityManager->getRepository(Article::class)->find($id);
            $data = [];
            // Determine data to send on API
            if($article) {
                $data = [
                        'title' => $article->getTitle(),
                        'content' => $article->getContent(),
                        'date' => $article->getDate(),
                        'id' => $article->getId(),
                    ];
            } else {
                $data = [
                    'message' => 'Article not found'
                ];
            }
            
            // Format to json
            $json = json_encode($data);
            // Send response (data, status, header)
            return new Response($json, 200, ['Content-Type'=> 'application/json']);
        }catch(e) {
            // Case error
            return new Response('{}', 500, ['Content-Type'=> 'application/json']);
        }
    }
    #[Route(path:"/api/article/user/{id}", name:"api_article_user", methods: ['GET'])]
    public function api_article_user(int $id, EntityManagerInterface $entityManager): Response {
        try {

            // Get list of articles
            $articles = $entityManager->getRepository(Article::class)->findBy(['user' => $id]);
            $data = [];
            // Determine data to send on API
            foreach($articles as $article) {
                $data[] = [
                        'title' => $article->getTitle(),
                        'content' => $article->getContent(),
                        'date' => $article->getDate(),
                        'id' => $article->getId(),
                    ];
            }

            // Format to json
            $json = json_encode($data);
            // Send response (data, status, header)
            return new Response($json, 200, ['Content-Type'=> 'application/json']);
        }catch(e) {
            // Case error
            return new Response('[]', 500, ['Content-Type'=> 'application/json']);
        }
    }
}