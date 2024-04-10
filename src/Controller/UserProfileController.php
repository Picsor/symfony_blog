<?php
// src/Controller/AdminController.php
namespace App\Controller;
// Allow to send a response
use App\Entity\User;
use App\Form\ResetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// Allow to link to a route
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
// Allow additionnal methods like rendering template, redirect, generate url...
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserProfileController extends AbstractController
{
    // Route to link with and name to identify it
    #[Route('/profile', name: 'profile', methods: ['GET'])]
    // Get parameter from request
    public function show_profile(Request $request,
    EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $hasher): Response
    {
        if($this->getUser() == null){
            return $this->redirectToRoute('app_login');
        }
        $user_username = $this->getUser()->getUserIdentifier();

        $form = $this->createForm(ChangePasswordType::class);
      
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $user_username]);
            if ($user) {
                $user->setPassword($hasher->hashPassword($user, $data->getPassword()));                

                $entityManager->persist($user);
                $entityManager->flush();
            }

        }
        

        $user = $entityManager->getRepository(User::class)
            ->findOneBy(['username' => $user_username]);

        $visits = $entityManager->getRepository(Visit::class)->findBy(
            [
                'userId'=> $user->getId(),
                ]
        );

        $article_id = [];

        foreach($visits as $visit){
            $article_id[] = $visit->getArticleId();
        }

        $articles = $entityManager->getRepository(Article::class)
                                  ->findArticleByIdList($article_id);

        // Return a response
        return $this->render('profile.html.twig', [
            'articles' => $articles,
            'form' => $form]
        );
    }

    #[Route('/profile/reset', name: 'profile_edit', methods: ['GET', 'POST'])]
    public function resetPasswordWithQuestion(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, ValidatorInterface $validator, Request $request): Response
    {
        $form = $this->createForm(ResetType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $data['username']]);
            $securityQuestion = $user->getSecurityQuestion();
            $securityAnswer = $user->getSecurityAnswer();
            if ($data['question'] == $securityQuestion && $data['reponse'] == $securityAnswer) {
                $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
                $entityManager->persist($user);
                $entityManager->flush();
            }
        }
        return $this->render('reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}