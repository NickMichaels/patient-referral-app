<?php

namespace App\Controller\Api;

use Exception;
use App\Entity\Practicioner;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PracticionerRepository;
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

final class PracticionerController extends AbstractController
{
    #[Route('/api/practicioners', methods: ["GET"])]
    public function index(
        PracticionerRepository $repository,
        SerializerInterface $serializer,
        Request $request,
        PaginatorOptionsResolver $paginatorOptionsResolver
    ): JsonResponse {
        try {
            $queryParams = $paginatorOptionsResolver
              ->configurePage()
              ->resolve($request->query->all());

            $practicioners = $repository->findAllWithPagination($queryParams["page"]);

            $context = [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId(); // Return the ID instead of the full object
                },
            ];
            $jsonContent = $serializer->serialize($practicioners, "json", $context);

            return JsonResponse::fromJsonString($jsonContent);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }


    #[Route("/api/practicioners/{id}", methods: ["GET"])]
    public function show(
        Practicioner $practicioner,
        SerializerInterface $serializer
    ): JsonResponse {
        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId(); // Return the ID instead of the full object
            },
        ];
        $jsonContent = $serializer->serialize($practicioner, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);
    }

    #[Route("/api/practicioners", methods: ["POST"])]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        $content = $request->getContent();

        $practicioner = $serializer->deserialize($content, Practicioner::class, "json");

        $errors = $validator->validate($practicioner);
        if (count($errors) > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }

            return $this->json(["errors" => $errorMessages], 422);
        }

        $em->persist($practicioner);
        $em->flush();

        return $this->json($practicioner, 201);
    }

    #[Route("/api/practicioners/{id}", methods: ["PUT", "PATCH"])]
    public function update(
        Request $request,
        Practicioner $practicioner,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ): JsonResponse {
        $serializer->deserialize(
            $request->getContent(),
            Practicioner::class,
            "json",
            ["object_to_populate" => $practicioner]
        );

        $em->flush();

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId(); // Return the ID instead of the full object
            },
        ];
        $jsonContent = $serializer->serialize($practicioner, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);
    }

    #[Route("/api/practicioners/{id}", methods: ["DELETE"])]
    public function delete(
        EntityManagerInterface $em,
        Practicioner $practicioner
    ): JsonResponse {
        $em->remove($practicioner);

        $em->flush();

        return $this->json(null, 204);
    }

    #[Route("/api/practicioners/{id}/referrals_sent", methods: ["GET"])]
    public function getReferralsSent(
        EntityManagerInterface $em,
        Practicioner $practicioner,
        SerializerInterface $serializer
    ): JsonResponse {
        $referralsSent = $practicioner->getPatientReferralsSent();

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId(); // Return the ID instead of the full object
            },
        ];
        $jsonContent = $serializer->serialize($referralsSent, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);
    }

    #[Route("/api/practicioners/{id}/referrals_received", methods: ["GET"])]
    public function getReferralsReceived(
        EntityManagerInterface $em,
        Practicioner $practicioner,
        SerializerInterface $serializer
    ): JsonResponse {
        $referralsReceived = $practicioner->getPatientReferralsReceived();

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId(); // Return the ID instead of the full object
            },
        ];
        $jsonContent = $serializer->serialize($referralsReceived, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);
    }
}
