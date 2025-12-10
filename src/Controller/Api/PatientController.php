<?php

namespace App\Controller\Api;

use App\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PatientController extends AbstractController
{
    #[Route('/api/patients', methods: ["GET"])]
    public function index(
        EntityManagerInterface $em,
        SerializerInterface $serializer
    ): JsonResponse {
        $patients = $em->getRepository(Patient::class)->findAll();

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId(); // Return the ID instead of the full object
            }
        ];
        $jsonContent = $serializer->serialize($patients, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);
    }


    #[Route("/api/patients/{id}", methods: ["GET"])]
    public function show(
        Patient $patient,
        SerializerInterface $serializer
    ): JsonResponse {
        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId(); // Return the ID instead of the full object
            },
        ];
        $jsonContent = $serializer->serialize($patient, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);
    }

    #[Route("/api/patients", methods: ["POST"])]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        $content = $request->getContent();

        $patient = $serializer->deserialize($content, Patient::class, "json");

        $errors = $validator->validate($patient);

        if (count($errors) > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }

            return $this->json(["errors" => $errorMessages], 422);
        }

        $em->persist($patient);
        $em->flush();

        return $this->json($patient, 201);
    }

    #[Route("/api/patients/{id}", methods: ["PUT", "PATCH"])]
    public function update(
        Request $request,
        Patient $patient,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ): JsonResponse {
        $serializer->deserialize(
            $request->getContent(),
            Patient::class,
            "json",
            ["object_to_populate" => $patient]
        );

        $em->flush();

        return $this->json($patient);
    }

    #[Route("/api/patients/{id}", methods: ["DELETE"])]
    public function delete(
        EntityManagerInterface $em,
        Patient $patient
    ): JsonResponse {
        $em->remove($patient);

        $em->flush();

        return $this->json(null, 204);
    }
}
