<?php

namespace App\Controller\Api;

use DateTime;
use App\Entity\Patient;
use App\Entity\Provider;
use App\Entity\Practicioner;
use App\Entity\PatientReferral;
use App\Enum\PatientReferralStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProviderController extends AbstractController
{
    #[Route('/api/providers', methods: ["GET"])]
    public function index(EntityManagerInterface $em,
                          SerializerInterface $serializer): JsonResponse
    {
        $providers = $em->getRepository(Provider::class)->findAll();

        $jsonContent = $serializer->serialize($providers, "json",[
            ObjectNormalizer::IGNORED_ATTRIBUTES => ["id"]
        ]);
        return JsonResponse::fromJsonString($jsonContent);
    }


    #[Route("/api/providers/{id}", methods: ["GET"])]
    public function show(Provider $provider): JsonResponse
    {
        return $this->json($provider);
    }

    #[Route("/api/providers", methods: ["POST"])]
    public function create(Request $request,
                           SerializerInterface $serializer,
                           EntityManagerInterface $em,
                           ValidatorInterface $validator): JsonResponse
    {
        $content = $request->getContent();

        $provider = $serializer->deserialize($content, Provider::class, "json");

        $errors = $validator->validate($provider);
        
        if (count($errors) > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }

            return $this->json(["errors" => $errorMessages], 422);
        }

        $em->persist($provider);
        $em->flush();

        return $this->json($provider, 201);
    }

    #[Route("/api/providers/{id}", methods: ["PUT", "PATCH"])]
    public function update(Request $request,
                           Provider $provider,
                           SerializerInterface $serializer,
                           EntityManagerInterface $em): JsonResponse
    {
        $serializer->deserialize($request->getContent(),
                                 Provider::class,
                                 "json",
                                ["object_to_populate" => $provider]);

        $em->flush();

        return $this->json($provider);
    }

    #[Route("/api/providers/{id}", methods: ["DELETE"])]
    public function delete(EntityManagerInterface $em,
                           Provider $provider): JsonResponse
    {
        $em->remove($provider);

        $em->flush();

        return $this->json(null, 204);
    }

    #[Route("/api/providers/{id}/add_practicioner", methods: ["POST"])]
    public function add_practioner(Request $request,
                                   EntityManagerInterface $em,
                                   Provider $provider): JsonResponse
    {
        $content = $request->getContent();
        $json = json_decode($content);

        if (!property_exists($json, 'practicioner_id')) {
            return $this->json("No practicioner id passed in request", 404);
        }
        $practicioner = $em->getRepository(Practicioner::class)->findOneBy(
            ['id' => $json->practicioner_id]
        );

        if (!$practicioner instanceof Practicioner) {
            return $this->json("Practicioner does not exist", 404);
        }

        $provider->addPracticioner($practicioner);
        $em->flush();
        return $this->json("Practicioner added to provider", 200);
    }

    #[Route("/api/providers/{id}/send_referral", methods: ["POST"])]
    public function send_referral(Request $request,
                                  EntityManagerInterface $em,
                                  Provider $provider): JsonResponse
    {
        $content = $request->getContent();
        $json = json_decode($content);

        if (!property_exists($json, 'patient_id')) {
            return $this->json("No patient id passed in request", 404);
        } else if (!property_exists($json, 'receiving_provider_id')) {
            return $this->json("No receiving provider id passed in request", 404);
        }

        $patient = $em->getRepository(Patient::class)->findOneBy(
            ['id' => $json->patient_id]
        );
        $receivingProvider = $em->getRepository(Provider::class)->findOneBy(
            ['id' => $json->receiving_provider_id]
        );

        if (!$patient instanceof Patient) {
            return $this->json("Patient does not exist", 404);
        } elseif (!$receivingProvider instanceof Provider) {
            return $this->json("Receiving provider does not exist", 404);
        }

        $patientReferral = new PatientReferral;
        $patientReferral->setPatient($patient);
        $patientReferral->setSendingProvider($provider);
        $patientReferral->setReceivingProvider($receivingProvider);
        $patientReferral->setStatus(PatientReferralStatus::Pending);
        $patientReferral->setDateSent(new DateTime());

        // Handle optional practicioner cases here
        if (property_exists($json, 'sending_practicioner_id')) {
            $sendingPracticioner = $em->getRepository(Practicioner::class)->findOneBy(
                ['id' => $json->sending_practicioner_id]
            );

            if ($sendingPracticioner instanceof Practicioner) {
                $patientReferral->setSendingPracticioner($sendingPracticioner);
            }
        }

        if (property_exists($json, 'receiving_practicioner_id')) {
            $receivingPracticioner = $em->getRepository(Practicioner::class)->findOneBy(
                ['id' => $json->receiving_practicioner_id]
            );

            if ($receivingPracticioner instanceof Practicioner) {
                $patientReferral->setReceivingPracticioner($receivingPracticioner);
            }
        }

        $em->persist($patientReferral);
        $em->flush();
        return $this->json("Patient referral sent", 200);
    }

    #[Route("/api/providers/{id}/referrals_sent", methods: ["GET"])]
    public function get_referrals_sent(EntityManagerInterface $em,
                                       Provider $provider,
                                       SerializerInterface $serializer): JsonResponse
    {
        $referralsSent = $provider->getPatientReferralsSent();

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId(); // Return the ID instead of the full object
            },
        ];
        $jsonContent = $serializer->serialize($referralsSent, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);

    }

    #[Route("/api/providers/{id}/referrals_received", methods: ["GET"])]
    public function get_referrals_received(EntityManagerInterface $em,
                                           Provider $provider,
                                           SerializerInterface $serializer): JsonResponse
    {
        $referralsReceived = $provider->getPatientReferralsReceived();

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId(); // Return the ID instead of the full object
            },
        ];
        $jsonContent = $serializer->serialize($referralsReceived, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);

    }
}
