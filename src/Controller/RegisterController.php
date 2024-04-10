<?php
// src/Controller/RegisterController.php
namespace App\Controller;
// Allow to send a response
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
// Allow to link to a route
use Symfony\Component\Routing\Attribute\Route;
// Allow additionnal methods like rendering template, redirect, generate url...
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// Import Entity
use App\Entity\User;
// Allow some actions on Entity
use Doctrine\ORM\EntityManagerInterface;
// Allow hashing password
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Form\UserRegistrationType;
class RegisterController extends AbstractController
{
// Route to link with and name to identify it
    #[Route('/register/{username}/{password}', name: 'register_admin', methods:
    ['GET'])]
    // Get parameter from request
    public function register_admin(string $username, string $password, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, ValidatorInterface $validator): Response
    {
        // Reset users
        $users = $entityManager->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        // Check if already one user at least
        $users = $entityManager->getRepository(User::class)->findAll();
        if (count($users) != 0) {
         return new Response('User already exists');
        } 

        // Case no user, create one
        $admin = new User();
        $admin->setSecurityQuestion('default');
        $admin->setSecurityAnswer('default');
        $admin->setUsername($username);
        $admin->setPassword($password);

        // Check if valid after setting values and before hash
        $errors = $validator->validate($admin);
        if (count($errors) > 0) {
        return new Response((string) $errors, 400);
        }
        $hashedPassword = $passwordHasher->hashPassword(
        $admin,
        $password
        );
        
        $admin->setPassword($hashedPassword);
        $admin->setRoles(['ROLE_ADMIN']);
        // tell Doctrine you want to (eventually) save the article (no queries yet)
        $entityManager->persist($admin);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        // Return a response with the article data
        return new Response('Registered');
    }

    #[Route('/register/user', name:'user_register', methods:["GET", "POST"])]
    public function registerUser(EntityManagerInterface $entityManager, 
    UserPasswordHasherInterface $passwordHasher, 
    ValidatorInterface $validator, 
    Request $request,
    LoggerInterface $loggerInterface): Response{
        $form = $this->createForm(UserRegistrationType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $loggerInterface->info("Tentative de création");
            $data = $form->getData();
            $username = $data["username"];
            $password = $data["password"];
            $question = $data["question"];
            $answer = $data["reponse"];

            $toCreate = new User();

            $toCreate->setUsername($username);
            $toCreate->setPassword($password);
            $toCreate->setRoles(["ROLE_USER"]);
            $toCreate->setSecurityQuestion($question);
            $toCreate->setSecurityAnswer($answer);
            

            $errors = $validator->validate($toCreate);
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                $loggerInterface->error($errorsString);
                return new Response(
                    '<html><body>'.$errorsString.'</body></html>'
                );
            }
            $hashedPassword = $passwordHasher->hashPassword($toCreate, $password);
            $toCreate->setPassword($hashedPassword);
            $users = $entityManager->getRepository(User::class)->findAll();
            $userExist = false;
            foreach ($users as $user) {
                if ($user->getUsername() === $username) {
                    $userExist = true;
                }
            }

            $msg = "";
            if (!$userExist) {
                $loggerInterface->info($toCreate->getUsername());
                $loggerInterface->info($password);
                $loggerInterface->info($question);
                $loggerInterface->info($answer);
                $entityManager->persist($toCreate);
                $entityManager->flush();
                $msg = "Compte Créé";
            }else{
                $msg = "L'utilisateur existe déjà : échec de la création";
            }
            $loggerInterface->info($msg);
            return new Response("<html>".$msg."</html>");
            
        }
        return $this->render("register.html.twig", [
            "form"=> $form 
        ]);
    }
}