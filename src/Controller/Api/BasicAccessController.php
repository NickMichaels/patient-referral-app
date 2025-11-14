<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class BasicAccessController extends AbstractController
{
    #[Route('/api/admin', methods: ["GET"])]
    public function admin(): JsonResponse
    {
        return $this->json([
            'message' => 'This is an admin-only resource!',
            'path' => 'src/Controller/Api/BasicAccessController.php',
        ]);
    }

    #[Route('/api/user', methods: ["GET"])]
    public function user(): JsonResponse
    {
        return $this->json([
            'message' => 'This is an user-only resource!!',
            'path' => 'src/Controller/Api/BasicAccessController.php',
        ]);
    }
}
