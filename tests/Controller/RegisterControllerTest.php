<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
class RegisterControllerTest extends WebTestCase
{
    public function checkRequest(KernelBrowser $client, 
                                string $path, 
                                int $expectedStatusCode): void {
        $client->request("GET", $path);
        $this->assertResponseStatusCodeSame($expectedStatusCode);
    }

    public function testRegister(): void
    {
        $client = static::createClient();
        // allow to access dependency
        $container = $client->getContainer();

        // Case 1 : valid data but already one user
        $this->checkRequest($client, "/register/admin/P@ssw0rd", 403);

        // Clear database
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $users = $entityManager->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        // Case 2 : valid data and no user
        $this->checkRequest($client, "/register/admin/P@ssw0rd", 200);
    }
}
