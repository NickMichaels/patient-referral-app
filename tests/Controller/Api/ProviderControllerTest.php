<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use App\Entity\Provider;
use Doctrine\ORM\EntityManagerInterface;

class ProviderControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private JWTEncoderInterface $jwtEncoder;
    private string $authToken;
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->jwtEncoder = static::getContainer()->get(JWTEncoderInterface::class);
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        // Generate a token for a test user
        $payload = ['username' => 'testuser@example.com', 'roles' => ['ROLE_USER']];
        $this->authToken = $this->jwtEncoder->encode($payload);
    }

    protected function tearDown(): void
    {
        // Clean up any providers created during tests
        $providerRepository = $this->em->getRepository(Provider::class);
        $testProviders = $providerRepository->findBy(['email' => 'test@example.com']);
        foreach ($testProviders as $provider) {
            $this->em->remove($provider);
        }
        $this->em->flush();
        parent::tearDown();
    }

    public function testCreateProviderSuccess(): void
    {
        $providerData = [
            "name" => "Jambos Bajambo Health Clinic",
            "addressLine1" => "123 Main St",
            "city" => "Longmont",
            "state" => "CO",
            "zip" => 80501,
            "email" => "test@example.com",
            "phone" => "123-415-9876"
        ];

        $this->client->request(
            'POST',
            '/api/providers',
            [],
            [],
            [
                'HTTP_Authorization' => 'Bearer ' . $this->authToken,
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($providerData)
        );

        $this->assertResponseStatusCodeSame(201);
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $responseContent);
        $this->assertEquals($providerData['name'], $responseContent['name']);
        $this->assertEquals($providerData['email'], $responseContent['email']);
        $this->assertEquals($providerData['phone'], $responseContent['phone']);
        $this->assertArrayHasKey('createdAt', $responseContent);
    }

    public function testCreateProviderWithOptionalFields(): void
    {
        $providerData = [
            "name" => "Complete Health Clinic",
            "addressLine1" => "456 Oak Ave",
            "addressLine2" => "Suite 200",
            "city" => "Denver",
            "state" => "CO",
            "zip" => 80202,
            "email" => "test@example.com",
            "phone" => "303-555-1234",
            "specialty" => "Cardiology"
        ];

        $this->client->request(
            'POST',
            '/api/providers',
            [],
            [],
            [
                'HTTP_Authorization' => 'Bearer ' . $this->authToken,
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($providerData)
        );

        $this->assertResponseStatusCodeSame(201);
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals($providerData['specialty'], $responseContent['specialty']);
        $this->assertEquals($providerData['addressLine2'], $responseContent['addressLine2']);
    }
}
