<?php

class AuthenticationService
{
    public function AuthenticateJwt($jwt)
    {
        if($jwt == "REALLY_SECURED_TOKEN")
        {
            return true;
        }

        return false;
    }
}