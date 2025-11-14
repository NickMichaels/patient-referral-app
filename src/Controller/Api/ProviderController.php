<?php

namespace App\Controller\Api;

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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProviderController extends AbstractController
{
    #[Route('/api/providers', methods: ["GET"])]
    public function index(EntityManagerInterface $em,
                          SerializerInterface $serializer): JsonResponse
    {
        $providers = $em->getRepository(Provider::class)->findAll();

        $json_content = $serializer->serialize($providers, "json",[
            ObjectNormalizer::IGNORED_ATTRIBUTES => ["id"]
        ]);
        return JsonResponse::fromJsonString($json_content);
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
            $error_messages = [];

            foreach ($errors as $error) {
                $error_messages[$error->getPropertyPath()][] = $error->getMessage();
            }

            return $this->json(["errors" => $error_messages], 422);
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

       // die(var_dump($json));

        if (!property_exists($json, 'patient_id')) {
            return $this->json("No patient id passed in request", 404);
        } else if (!property_exists($json, 'receiving_provider_id')) {
            return $this->json("No receiving provider id passed in request", 404);
        }

        $patient = $em->getRepository(Patient::class)->findOneBy(
            ['id' => $json->patient_id]
        );
        $receiving_provider = $em->getRepository(Provider::class)->findOneBy(
            ['id' => $json->receiving_provider_id]
        );



        if (!$patient instanceof Patient) {
            return $this->json("Patient does not exist", 404);
        } elseif (!$receiving_provider instanceof Provider) {
            return $this->json("Receiving provider does not exist", 404);
        }

        $patientReferral = new PatientReferral;
        $patientReferral->setPatient($patient);
        $patientReferral->setSendingProvider($provider);
        $patientReferral->setReceivingProvider($receiving_provider);
        $patientReferral->setStatus(PatientReferralStatus::Pending);

        $em->persist($patientReferral);
        $em->flush();
        return $this->json("Patient referral sent", 200);
    }
}
