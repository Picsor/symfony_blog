<?php
namespace App\Service;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

namespace App\Service;
class AuthenticationService
{
    public function AuthenticateJwt($jwt, EntityManager $em, $target_role)
    {
        # Should check the JWT token against a secure token
        # if the jwt token is valid return true:
        #    - the token is valid,
        #    - the token contains the right claims (e.g. user is admin)
        $user = $em->getRepository(User::class)->findAll()[0];

        if($jwt == "REALLY_SECURED_TOKEN" &&
            $user->getRoles() == $target_role)
        {
            return true;
        }

        return false;
    }
}