<?php
namespace App\Service;
use App\Entity\User;

class AuthenticationService
{
    public function AuthenticateJwt($jwt, User $user, $target_role)
    {
        # Should check the JWT token against a secure token
        # if the jwt token is valid return true:
        #    - the token is valid,
        #    - the token contains the right claims (e.g. user is admin)
        if($jwt == "REALLY_SECURED_TOKEN" &&
            $user->getRoles() == $target_role)
        {
            return true;
        }

        return false;
    }
}