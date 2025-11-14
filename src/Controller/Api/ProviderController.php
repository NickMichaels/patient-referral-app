<?php

namespace App\Controller\Api;

use App\Entity\Provider;
use App\Entity\Practicioner;
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
                                   SerializerInterface $serializer,
                                   EntityManagerInterface $em,
                                   ValidatorInterface $validator,
                                   Provider $provider): JsonResponse
    {
        $content = $request->getContent();
        $json = json_decode($content);

        $practicionerId = $json->practicioner_id;
        $practicioner = $em->getRepository(Practicioner::class)->findOneBy(['id' => $practicionerId]);

        if ($practicioner instanceof Practicioner) {
            $provider->addPracticioner($practicioner);
            $em->flush();
            return $this->json("Practicioner added to provider", 200);
        } else {
            return $this->json("Practicioner does not exist", 404);
        }
    }
}
