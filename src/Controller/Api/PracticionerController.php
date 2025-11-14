<?php

namespace App\Controller\Api;

use App\Entity\Practicioner;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PracticionerController extends AbstractController
{
    #[Route('/api/practicioners', methods: ["GET"])]
    public function index(EntityManagerInterface $em,
                          SerializerInterface $serializer): JsonResponse
    {
        $practicioners = $em->getRepository(Practicioner::class)->findAll();

        $json_content = $serializer->serialize($practicioners, "json",[
            ObjectNormalizer::IGNORED_ATTRIBUTES => ["id"]
        ]);
        return JsonResponse::fromJsonString($json_content);
    }


    #[Route("/api/practicioners/{id}", methods: ["GET"])]
    public function show(Practicioner $practicioner): JsonResponse
    {
        return $this->json($practicioner);
    }

    #[Route("/api/practicioners", methods: ["POST"])]
    public function create(Request $request,
                           SerializerInterface $serializer,
                           EntityManagerInterface $em,
                           ValidatorInterface $validator): JsonResponse
    {
        $content = $request->getContent();

        $practicioner = $serializer->deserialize($content, Practicioner::class, "json");

        $errors = $validator->validate($practicioner);
        
        if (count($errors) > 0) {
            $error_messages = [];

            foreach ($errors as $error) {
                $error_messages[$error->getPropertyPath()][] = $error->getMessage();
            }

            return $this->json(["errors" => $error_messages], 422);
        }

        $em->persist($practicioner);
        $em->flush();

        return $this->json($practicioner, 201);
    }

    #[Route("/api/practicioners/{id}", methods: ["PUT", "PATCH"])]
    public function update(Request $request,
                           Practicioner $practicioner,
                           SerializerInterface $serializer,
                           EntityManagerInterface $em): JsonResponse
    {
        $serializer->deserialize($request->getContent(),
                                 Practicioner::class,
                                 "json",
                                ["object_to_populate" => $practicioner]);

        $em->flush();

        return $this->json($practicioner);
    }

    #[Route("/api/practicioners/{id}", methods: ["DELETE"])]
    public function delete(EntityManagerInterface $em,
                           Practicioner $practicioner): JsonResponse
    {
        $em->remove($practicioner);

        $em->flush();

        return $this->json(null, 204);
    }
}
