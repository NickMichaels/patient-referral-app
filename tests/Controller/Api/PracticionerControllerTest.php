<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use App\Entity\Practicioner;
use Doctrine\ORM\EntityManagerInterface;

class PracticionerControllerTest extends WebTestCase
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
        // Clean up any practicioners created during tests
        $practicionerRepository = $this->em->getRepository(Practicioner::class);
        $testpracticioners = $practicionerRepository->findBy(['email' => 'test@example.com']);
        foreach ($testpracticioners as $practicioner) {
            $this->em->remove($practicioner);
        }
        $this->em->flush();
        parent::tearDown();
    }

    public function testCreatePracticionerSuccess(): void
    {
        $practicionerData = [
            "name" => "Bilbo Baggins",
            "jobTitle" => "Tester Supreme",
            "licenseNumber" => "Rx964239",
            "email" => "test@example.com",
            "phone" => "412-415-9876"
        ];

        $this->client->request(
            'POST',
            '/api/practicioners',
            [],
            [],
            [
                'HTTP_Authorization' => 'Bearer ' . $this->authToken,
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($practicionerData)
        );

        $this->assertResponseStatusCodeSame(201);
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $responseContent);
        $this->assertEquals($practicionerData['name'], $responseContent['name']);
        $this->assertEquals($practicionerData['email'], $responseContent['email']);
        $this->assertEquals($practicionerData['phone'], $responseContent['phone']);
        $this->assertEquals($practicionerData['licenseNumber'], $responseContent['licenseNumber']);
        $this->assertEquals($practicionerData['jobTitle'], $responseContent['jobTitle']);
        $this->assertArrayHasKey('createdAt', $responseContent);
    }

    public function testCreatePracticionerWithOptionalFields(): void
    {
        $practicionerData = [
            "name" => "Marty McFly",
            "jobTitle" => "Tester Supreme",
            "licenseNumber" => "Rx964249",
            "email" => "test@example.com",
            "phone" => "412-415-9876",
            "specialty" => "Cardiology"
        ];

        $this->client->request(
            'POST',
            '/api/practicioners',
            [],
            [],
            [
                'HTTP_Authorization' => 'Bearer ' . $this->authToken,
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($practicionerData)
        );

        $this->assertResponseStatusCodeSame(201);
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals($practicionerData['specialty'], $responseContent['specialty']);
        $this->assertEquals($practicionerData['name'], $responseContent['name']);
    }
}
