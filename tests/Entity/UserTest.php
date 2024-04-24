<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class UserTest extends TypeTestCase
{
    public function testInvalidType()
    {
        $validator = Validation::createValidatorBuilder()
        ->addMethodMapping('loadValidatorMetadata')
        ->getValidator();

        $data = [
            // Case username valid but password invalid
            ['username' => 'admin', 'password' => 'add'],
            // Case username invalid but password valid
            ['username' => 'a', 'password' => 'P@ssw0rd'],
            // Case username valid but password invalid
            ['username' => 'admin', 'password' => '<>desd^fesfAb'],
            // Case username invalid but password invalid
            ['username' => 'a', 'password' => '<>desd^fesfAb'],
            // Adding this return failure because it is valid
            //['username' => 'admin', 'password' => 'P@ssw0rd'],
        ];
        // Loop over data
        foreach ($data as $value) {
            $user = new User();
            $user->setUsername($value['username']);
            $user->setPassword($value['password']);
            $errors = $validator->validate($user);
            // check if at least one error
            $this->assertNotEquals(0, count($errors));

        }
    }
}