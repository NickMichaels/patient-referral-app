<?php

namespace App\Controller\Api;

use App\Entity\PatientReferral;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PatientReferralController extends AbstractController
{
    
    #[Route("/api/patientreferrals/{id}", methods: ["GET"])]
    public function show(
        PatientReferral $patientReferral,
        SerializerInterface $serializer
    ): JsonResponse {
        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId(); // Return the ID instead of the full object
            },
        ];
        $jsonContent = $serializer->serialize($patientReferral, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);
    }

    #[Route("/api/patientreferrals/{id}", methods: ["PUT", "PATCH"])]
    public function update(
        Request $request,
        PatientReferral $patientReferral,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ): JsonResponse {
        $serializer->deserialize(
            $request->getContent(),
            PatientReferral::class,
            "json",
            ["object_to_populate" => $patientReferral]
        );

        $em->flush();

        return $this->json($patientReferral);
    }

    #[Route("/api/patientreferrals/{id}", methods: ["DELETE"])]
    public function delete(
        EntityManagerInterface $em,
        PatientReferral $patientReferral
    ): JsonResponse {
        $em->remove($patientReferral);

        $em->flush();

        return $this->json(null, 204);
    }
}
