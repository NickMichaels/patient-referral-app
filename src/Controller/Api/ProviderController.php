<?php

namespace App\Controller\Api;

use DateTime;
use App\Entity\Patient;
use App\Entity\Provider;
use App\Entity\Appointment;
use App\Cache\ProviderCache;
use App\Entity\Practitioner;
use App\Entity\PatientReferral;
use App\Enum\AppointmentStatus;
use App\Enum\PatientReferralStatus;
use App\Entity\PractitionerSchedule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PractitionerScheduleRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProviderController extends AbstractController
{
    #[Route('/api/providers', methods: ["GET"])]
    public function index(
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        ProviderCache $providerCache
    ): JsonResponse {
        //$providers = $em->getRepository(Provider::class)->findAll();
        $providers = $providerCache->findAll();

        $jsonContent = $serializer->serialize($providers, 'json');

        return JsonResponse::fromJsonString($jsonContent);
    }

    #[Route("/api/providers/{id}", methods: ["GET"])]
    public function show(
        Provider $provider,
        SerializerInterface $serializer
    ): JsonResponse {
        $jsonContent = $serializer->serialize($provider, 'json');

        return JsonResponse::fromJsonString($jsonContent);
    }

    #[Route("/api/providers", methods: ["POST"])]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
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
    public function update(
        Request $request,
        Provider $provider,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ): JsonResponse {
        $serializer->deserialize(
            $request->getContent(),
            Provider::class,
            "json",
            ["object_to_populate" => $provider]
        );

        $em->flush();

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId(); // Return the ID instead of the full object
            },
        ];
        $jsonContent = $serializer->serialize($provider, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);
    }

    #[Route("/api/providers/{id}", methods: ["DELETE"])]
    public function delete(
        EntityManagerInterface $em,
        Provider $provider
    ): JsonResponse {
        $em->remove($provider);

        $em->flush();

        return $this->json(null, 204);
    }

    #[Route("/api/providers/{id}/add_practitioner", methods: ["POST"])]
    public function addPractitioner(
        Request $request,
        EntityManagerInterface $em,
        Provider $provider
    ): JsonResponse {
        $content = $request->getContent();
        $json = json_decode($content);

        if (!property_exists($json, 'practitioner_id')) {
            return $this->json("No practitioner id passed in request", 404);
        }
        $practitioner = $em->getRepository(Practitioner::class)->findOneBy(
            ['id' => $json->practitioner_id]
        );

        if (!$practitioner instanceof Practitioner) {
            return $this->json("Practitioner does not exist", 404);
        }

        $provider->addPractitioner($practitioner);
        $em->flush();
        return $this->json("Practitioner added to provider", 200);
    }

    #[Route("/api/providers/{id}/remove_practitioner", methods: ["POST"])]
    public function removePractitioner(
        Request $request,
        EntityManagerInterface $em,
        Provider $provider
    ): JsonResponse {
        $content = $request->getContent();
        $json = json_decode($content);

        if (!property_exists($json, 'practitioner_id')) {
            return $this->json("No practitioner id passed in request", 404);
        }
        $practitioner = $em->getRepository(Practitioner::class)->findOneBy(
            ['id' => $json->practitioner_id]
        );

        if (!$practitioner instanceof Practitioner) {
            return $this->json("Practitioner does not exist", 404);
        }

        $provider->removePractitioner($practitioner);
        $em->flush();
        return $this->json("Practitioner removed from provider", 200);
    }

    #[Route("/api/providers/{id}/send_referral", methods: ["POST"])]
    public function sendPatientReferral(
        Request $request,
        EntityManagerInterface $em,
        Provider $provider
    ): JsonResponse {
        $content = $request->getContent();
        $json = json_decode($content);

        if (!property_exists($json, 'patient_id')) {
            return $this->json("No patient id passed in request", 404);
        } elseif (!property_exists($json, 'receiving_provider_id')) {
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

        $patientReferral = new PatientReferral();
        $patientReferral->setPatient($patient);
        $patientReferral->setSendingProvider($provider);
        $patientReferral->setReceivingProvider($receivingProvider);
        $patientReferral->setStatus(PatientReferralStatus::Pending);
        $patientReferral->setDateSent(new DateTime());

        // Handle optional practitioner cases here
        if (property_exists($json, 'sending_practitioner_id')) {
            $sendingPractitioner = $em->getRepository(Practitioner::class)->findOneBy(
                ['id' => $json->sending_practitioner_id]
            );

            if ($sendingPractitioner instanceof Practitioner) {
                $patientReferral->setSendingPractitioner($sendingPractitioner);
            }
        }

        if (property_exists($json, 'receiving_practitioner_id')) {
            $receivingPractitioner = $em->getRepository(Practitioner::class)->findOneBy(
                ['id' => $json->receiving_practitioner_id]
            );

            if ($receivingPractitioner instanceof Practitioner) {
                $patientReferral->setReceivingPractitioner($receivingPractitioner);
            }
        }

        $em->persist($patientReferral);
        $em->flush();
        return $this->json("Patient referral sent", 200);
    }

    #[Route("/api/providers/{id}/schedule_patient", methods: ["POST"])]
    public function schedulePatient(
        Request $request,
        EntityManagerInterface $em,
        Provider $provider
    ): JsonResponse {
        $content = $request->getContent();
        $json = json_decode($content);

        // If the patient_id isnt set, we can infer it from the patient_referral_id
        if (!property_exists($json, 'patient_id')) {
            if (property_exists($json, 'patient_referral_id')) {
                $referral = $em->getRepository(PatientReferral::class)->findOneBy(
                    ['id' => $json->patient_referral_id]
                );
                $patient = $referral->getPatient();

                if (!$patient instanceof Patient) {
                    return $this->json("Patient does not exist", 404);
                }
            } else {
                // If neither patient nor patient referral are set, then we cant schedule
                return $this->json("No patient_id or patient_referral_id sent in request", 404);
            }
        } else {
            $patient = $em->getRepository(Patient::class)->findOneBy(
                ['id' => $json->patient_id]
            );
            if (!$patient instanceof Patient) {
                return $this->json("Patient does not exist", 404);
            }
            // However the patient id set should match the patient id in the patient referral
            if (property_exists($json, 'patient_referral_id')) {
                $referral = $em->getRepository(PatientReferral::class)->findOneBy(
                    ['id' => $json->patient_referral_id]
                );
                $ref_patient_id = $referral->getPatient()->getId();

                if ($json->patient_id !== $ref_patient_id) {
                    return $this->json("Patient passed and patient referral passed do not match", 404);
                }
            }
        }

        $practitioner = $em->getRepository(Practitioner::class)->findOneBy(
            ['id' => $json->practitioner_id]
        );

        if (!$practitioner instanceof Practitioner) {
            return $this->json("Practitioner does not exist", 404);
        }

        // Check and see if the requested times fall with the practitioners schedule
        $practId = $json->practitioner_id;
        // Now we need to parse the times
        $startDate = new DateTime($json->start_time);
        $endDate = new DateTime($json->end_time);
        $dayOfWeek = $startDate->format('l');
        $startTime = $startDate->format('H:i');
        $endTime = $endDate->format('H:i');

        // For the sake of simplicity, lets assume that start and end
        // times are always going to be on the same day
        $onSchedule = $em->getRepository(PractitionerSchedule::class)
            ->findByPractitionerId(
                $practId,
                $startTime,
                $endTime
            );

        if (empty($onSchedule)) {
            // Technically they aren't scheduled but we dont need to share that
            return $this->json("Practitioner is not available at the requested times", 404);
        }

        // Check and see if the practitioner already has something scheduled
        $appts = $em->getRepository(Appointment::class)
            ->findPractitionerAppointments(
                $practId,
                $startDate->format('Y-m-d H:i:s'),
                $endDate->format('Y-m-d H:i:s'),
            );

        if ($appts[0]['appointment_no'] > 0) {
            return $this->json("Practitioner is not available at the requested times", 404);
        }

        // Ok now we can create the appointment
        $appointment = new Appointment();
        $appointment->setPatient($patient);
        $appointment->setProvider($provider);
        $appointment->setPractitioner($practitioner);
        $appointment->setStartTime($startDate);
        $appointment->setEndTime($endDate);
        $appointment->setStatus(AppointmentStatus::Scheduled);

        if (property_exists($json, 'description')) {
            $appointment->setDescription($json->description);
        }

        if (property_exists($json, 'cancellation_reason')) {
            $appointment->setCancellationReason($json->cancellation_reason);
        }

        $em->persist($appointment);
        $em->flush();
        return $this->json("Patient scheduled", 200);
    }

    #[Route("/api/providers/{id}/practitioners", methods: ["GET"])]
    public function getAssociatedPractitioners(
        EntityManagerInterface $em,
        Provider $provider,
        SerializerInterface $serializer
    ): JsonResponse {
        $practitioners = $provider->getPractitioners();

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId(); // Return the ID instead of the full object
            },
        ];
        $jsonContent = $serializer->serialize($practitioners, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);
    }

    #[Route("/api/providers/{id}/referrals_sent", methods: ["GET"])]
    public function getReferralsSent(
        EntityManagerInterface $em,
        Provider $provider,
        SerializerInterface $serializer
    ): JsonResponse {
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
    public function getReferralsReceived(
        EntityManagerInterface $em,
        Provider $provider,
        SerializerInterface $serializer
    ): JsonResponse {
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
