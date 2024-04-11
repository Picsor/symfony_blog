<?php
// src/Controller/AdminController.php
namespace App\Controller;
// Allow to send a response
use App\Entity\Article;
use App\Entity\User;
use App\Form\ChangePasswordType;
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
    public function show_profile(): Response
    {
         $this->getUser()->getUserIdentifier();

        // Return a response
        return $this->render('profile.html.twig');
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