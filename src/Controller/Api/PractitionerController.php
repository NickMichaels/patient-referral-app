<?php

namespace App\Controller\Api;

use Exception;
use App\Entity\Practitioner;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PractitionerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\OptionsResolver\PaginatorOptionsResolver;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class PractitionerController extends AbstractController
{
    #[Route('/api/practitioners', methods: ["GET"])]
    public function index(
        EntityManagerInterface $em,
        PractitionerRepository $repository,
        SerializerInterface $serializer,
        Request $request,
        PaginatorOptionsResolver $paginatorOptionsResolver
    ): JsonResponse {
        $practitioners = $em->getRepository(Practitioner::class)->findAll();

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId(); // Return the ID instead of the full object
            },
        ];
        $jsonContent = $serializer->serialize($practitioners, "json", $context);
        return JsonResponse::fromJsonString($jsonContent);
        /*
        try {
            $queryParams = $paginatorOptionsResolver
              ->configurePage()
              ->resolve($request->query->all());

            $practitioners = $repository->findAllWithPagination($queryParams["page"]);

            $context = [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId(); // Return the ID instead of the full object
                },
            ];
            $jsonContent = $serializer->serialize($practitioners, "json", $context);

            return JsonResponse::fromJsonString($jsonContent);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        */
    }


    #[Route("/api/practitioners/{id}", methods: ["GET"])]
    public function show(
        Practitioner $practitioner,
        SerializerInterface $serializer
    ): JsonResponse {
        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId(); // Return the ID instead of the full object
            },
        ];
        $jsonContent = $serializer->serialize($practitioner, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);
    }

    #[Route("/api/practitioners", methods: ["POST"])]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        $content = $request->getContent();

        $practitioner = $serializer->deserialize($content, Practitioner::class, "json");

        $errors = $validator->validate($practitioner);
        if (count($errors) > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }

            return $this->json(["errors" => $errorMessages], 422);
        }

        $em->persist($practitioner);
        $em->flush();

        return $this->json($practitioner, 201);
    }

    #[Route("/api/practitioners/{id}", methods: ["PUT", "PATCH"])]
    public function update(
        Request $request,
        Practitioner $practitioner,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ): JsonResponse {
        $serializer->deserialize(
            $request->getContent(),
            Practitioner::class,
            "json",
            ["object_to_populate" => $practitioner]
        );

        $em->flush();

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId(); // Return the ID instead of the full object
            },
        ];
        $jsonContent = $serializer->serialize($practitioner, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);
    }

    #[Route("/api/practitioners/{id}", methods: ["DELETE"])]
    public function delete(
        EntityManagerInterface $em,
        Practitioner $practitioner
    ): JsonResponse {
        $em->remove($practitioner);

        $em->flush();

        return $this->json(null, 204);
    }

    #[Route("/api/practitioners/{id}/referrals_sent", methods: ["GET"])]
    public function getReferralsSent(
        EntityManagerInterface $em,
        Practitioner $practitioner,
        SerializerInterface $serializer
    ): JsonResponse {
        $referralsSent = $practitioner->getPatientReferralsSent();

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId(); // Return the ID instead of the full object
            },
        ];
        $jsonContent = $serializer->serialize($referralsSent, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);
    }

    #[Route("/api/practitioners/{id}/referrals_received", methods: ["GET"])]
    public function getReferralsReceived(
        EntityManagerInterface $em,
        Practitioner $practitioner,
        SerializerInterface $serializer
    ): JsonResponse {
        $referralsReceived = $practitioner->getPatientReferralsReceived();

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId(); // Return the ID instead of the full object
            },
        ];
        $jsonContent = $serializer->serialize($referralsReceived, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);
    }
}
