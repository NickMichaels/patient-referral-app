<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

class ProviderControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private JWTEncoderInterface $jwtEncoder;
    private string $authToken;

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
