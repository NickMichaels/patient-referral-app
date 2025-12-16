<?php

namespace App\Controller\Api;

use App\Entity\PatientReferral;
use App\Entity\Patient;
use App\Entity\Practitioner;
use App\Entity\Provider;
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

        $data = json_decode($request->getContent(), true);
        $fkMap = [
            'patient_id' => "App\Entity\Patient",
            'sending_provider_id' => "App\Entity\Provider",
            'receiving_provider_id' => "App\Entity\Provider",
            'sending_practitioner_id' => "App\Entity\Practitioner",
            'receiving_practitioner_id' => "App\Entity\Practitioner",
        ];

        foreach ($fkMap as $field => $className) {
            if (isset($data[$field])) {
                $fkEntityId = $data[$field];
                $fkEntity = $em->getRepository($className)->find($fkEntityId);
                if (!$fkEntity) {
                    continue;
                }
                // this converts snakeCase to camelCase, and gets rid of 'id' from each field
                $fncName = 'set' . str_replace('Id','', str_replace(' ', '', ucwords(str_replace('_', ' ', $field))));

                $patientReferral->$fncName($fkEntity);

            }
        }

        $em->flush();

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId(); // Return the ID instead of the full object
            },
        ];
        $jsonContent = $serializer->serialize($patientReferral, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);
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
