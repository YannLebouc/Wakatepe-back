<?php

namespace App\Tests;

use App\Repository\UserRepository;
use App\Tests\CustomWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TestProfileControllerTest extends CustomWebTestCase
{
    // public function testSomething(): void
    // {
    //     $client = static::createClient();
    //     $crawler = $client->request('GET', '/');

    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains("h1", "O'Troc-Backoffice");
    // }

    public function testManager(): void
    {
        $client = static::createClient();

        /** @var UserRepository $userRepository permet à vscode de savoir quelle type de classe est une variable */
        $userRepository = static::getContainer()->get(UserRepository::class);

        $manager = $userRepository->findOneBy(["email" => "manager@role.test"]);

        $client->loginUser($manager);

        $crawler = $client->request('GET', '/backoffice/user/');
        
        $this->assertResponseIsSuccessful();

        $manager = $userRepository->findOneBy(["email" => "user@role.test"]);

        $client->loginUser($manager);

        $crawler = $client->request('GET', '/backoffice/user/');

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        // $this->assertSelectorTextContains('h1', 'Hello World');
    }
}
