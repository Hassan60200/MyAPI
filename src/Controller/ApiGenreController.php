<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiGenreController extends AbstractController
{
    /**
     * @Route("/api/genres", name="api_genres", methods={"GET"})
     */
    public function list(GenreRepository $repo, SerializerInterface $serializer)
    {

        $genres = $repo->findAll();
        $resultat = $serializer->serialize(
            $genres,
            'json',
            [
                'groups' => ['listGenreFull']
            ]
        );

        return new JsonResponse($resultat, 200, [], true);
    }

    /**
     * @Route("/api/genres/{id}", name="api_genres_show", methods={"GET"})
     */
    public function show(Genre $genre, SerializerInterface $serializer)
    {
        $resultat = $serializer->serialize(
            $genre,
            'json',
            [
                'groups' => ['listGenreSimple']
            ]
        );
        return new JsonResponse($resultat, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/genres", name="api_genres_create", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, ObjectManager $manager)
    {
        $data = $request->getContent();
        $genre = $serializer->deserialize($data, Genre::class, 'json');
        $manager->persist($genre);
        $manager->flush();

        return new JsonResponse(null, Response::HTTP_CREATED, [
            "location" => "api/genres/".$genre->getId()
        ], true);
    }
}
