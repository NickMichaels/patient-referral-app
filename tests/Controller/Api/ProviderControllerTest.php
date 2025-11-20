<?php

namespace App\Tests\Controller\Api;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProviderControllerTest extends WebTestCase
{
    private $client;
    private $jwtEncoder;
    private $authToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->jwtEncoder = static::getContainer()->get(JWTEncoderInterface::class);

        // Generate a token for a test user
        $payload = ['username' => 'testuser@example.com', 'roles' => ['ROLE_USER']];
        $this->authToken = $this->jwtEncoder->encode($payload);
    }

    public function testSomething(): void
    {
        $crawler = $this->client->request(
            'GET', 
            '/api/providers',
            [],
            [],
            ['HTTP_Authorization' => 'Bearer ' . $this->authToken]
        );

        $this->assertResponseIsSuccessful();
    }
}
