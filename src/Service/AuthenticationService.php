<?php
namespace App\Service;
use App\Entity\User;
namespace App\Service;
use Doctrine\ORM\EntityManagerInterface;

class AuthenticationService
{
    public function AuthenticateJwt($jwt, EntityManagerInterface $em, $target_role)
    {
        # Should check the JWT token against a secure token
        # if the jwt token is valid return true:
        #    - the token is valid,
        #    - the token contains the right claims (e.g. user is admin)
        $user = $em->getRepository(User::class)->findAll()[0];

        if($jwt == "Bearer REALLY_SECURED_TOKEN" &&
            $user->getRoles() == $target_role)
        {
            return true;
        }

        return false;
    }
}