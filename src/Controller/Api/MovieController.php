<?php

namespace App\Controller\Api;

use App\Entity\Movie;
use App\Entity\Review;
use App\Repository\MovieRepository;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * @Route("/api/movies",name="api_movies_")
 */
class MovieController extends ApiController
{
    /**
     * @Route("", name="browse", methods={"GET"}))
     */
    public function browse(MovieRepository $movieRepository): JsonResponse
    {
        $allMovie = $movieRepository->findAll();
        
        return $this->json(
            // data
            $allMovie,
            // code HTTP pour dire que tout se passe bien (200) 
            Response::HTTP_OK,
            // les entêtes HTTP, on les utilise dans très peu de cas, donc valeur par défaut : []
            [],
            // le contexte, on l'utilise pour spécifier les groupes de serialisation
            [
                // je lui donne le/les noms de groupes de serialisation
                "groups" => 
                [
                    "api_movies_browse"
                ]
            ]
        );
    }

    // TODO : une route API pour afficher un seul film
    /**
     * @Route("/{id}",name="read", methods={"GET"})
     */
    public function read(Movie $movie = null)
    {
        // puisque j'autorise la valeur NULL
        // le paramConverter ne va plus faire d'erreur si il ne trouve pas d'objet.
        // il pourra fournir la valeur par defaut : null
        //! il faut donc que l'on gère ce cas là
        if ($movie === null)
        {
            // on renvoie donc une 404
            return $this->json(
                [
                    "erreur" => "le film n'a pas été trouvé",
                    "code_error" => 404
                ],
                Response::HTTP_NOT_FOUND,// 404
                // les autres paramètres sont inutiles
            );
        }
        return $this->json(
            $movie,
            // code HTTP pour dire que tout se passe bien (200) 
            Response::HTTP_OK,
            // les entêtes HTTP, on les utilise dans très peu de cas, donc valeur par défaut : []
            [],
            // le contexte, on l'utilise pour spécifier les groupes de serialisation
            [
                // je lui donne le/les noms de groupes de serialisation
                "groups" => 
                [
                    "api_read_movies"
                ]
            ]);
    }
/*
    /**
     * @Route("/advanced/{id}",name="advanced_read", methods={"GET"})
     */
    public function readAdvanced(Movie $movie = null)
    {
        // puisque j'autorise la valeur NULL
        // le paramConverter ne va plus faire d'erreur si il ne trouve pas d'objet.
        // il pourra fournir la valeur par defaut : null
        //! il faut donc que l'on gère ce cas là
        if ($movie === null)
        {
            return $this->json404("le film n'a pas été trouvé");   
        }

        return $this->json200(
            $movie,
            [
                // je lui donne le/les noms de groupes de serialisation
                "groups" => 
                [
                    "api_read_movies"
                ]
                ]);
    }  

    /**
     * @Route("/{id}/season",name="season", methods={"GET"})
     */
    public function season(Movie $movie = null)
    {
        // puisque j'autorise la valeur NULL
        // le paramConverter ne va plus faire d'erreur si il ne trouve pas d'objet.
        // il pourra fournir la valeur par defaut : null
        //! il faut donc que l'on gère ce cas là
        if ($movie === null)
        {
            // on renvoie donc une 404
            return $this->json(
                [
                    "erreur" => "le film n'a pas été trouvé",
                    "code_error" => 404
                ],
                Response::HTTP_NOT_FOUND,// 404
                // les autres paramètres sont inutiles
            );
        }
        return $this->json(
            $movie,
            // code HTTP pour dire que tout se passe bien (200) 
            Response::HTTP_OK,
            // les entêtes HTTP, on les utilise dans très peu de cas, donc valeur par défaut : []
            [],
            // le contexte, on l'utilise pour spécifier les groupes de serialisation
            [
                // je lui donne le/les noms de groupes de serialisation
                "groups" => 
                [
                    "api_season_movie"
                ]
            ]);
    }

    /**
     * @Route("/api/movies/{id}/reviews",name="api_review_new_movie", methods={"POST"})
     */
    public function review(Review $review = null)
    {
    dd('je suis passé par là');
    }


    /**
     * Creation de Film
     *
     * @Route("", name="add", methods={"POST"})
     * 
     * 
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ManagerRegistry $manager
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function add(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $manager,
        ValidatorInterface $validator
        ): JsonResponse
    {
        // Récupérer le contenu JSON
        $jsonContent = $request->getContent();
        // Désérialiser (convertir) le JSON en entité Doctrine Movie
        //? Si on reçoit un objet dans une relation (ici les genres)
        //? Automatiquement le serializer va demander si un denormalizer sait faire
        //? comme on a créer notre DoctrineDenormalizer, celui ci va être appellé, et il va répondre Oui
        //? on va donc avoir un find() qui sera fait en auto 💪
        $movie = $serializer->deserialize($jsonContent, Movie::class, 'json');
        //! Doctrine n'est pas au courant de notre deserialisation
        //! donc le serializer comprend, par les annotations de l'entity, de quel type est la propriété genres
        //! mais comme on lui donne que des ID, il arrive pas à faire le mapping
        //? Si on demande l'aide à Doctrine ? Mais comment ??? 
        // avec un denormalizer Kustom => DoctrineDenormalizer.php
        
        /* dump($movie);
            App\Entity\Movie {#2787 ▼
            -id: null
            -title: "Curie E19"
            -summary: "string"
            -synopsis: "string"
            -releasedAt: DateTimeImmutable @1650612254 {#2903 ▶}
            -duration: 0
            -poster: "string"
            -country: "string"
            -rating: 0.0
            -seasons: Doctrine\Common\Collections\ArrayCollection {#2786 ▶}
            -type: "string"
            -genres: Doctrine\Common\Collections\ArrayCollection {#2783 ▼
                -elements: array:1 [▼
                0 => App\Entity\Genre {#6407 ▼
                    -id: 474
                    -name: "Documentaire"
                    -movies: Doctrine\ORM\PersistentCollection {#4286 ▶}
                }
                ]
            }
            -castings: Doctrine\Common\Collections\ArrayCollection {#2782 ▶}
            -reviews: Doctrine\Common\Collections\ArrayCollection {#2721 ▶}
            -slug: null
            -updatedAt: null
            }
        */
        // Valider l'entité
        // @link : https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errorsList = $validator->validate($movie);
        // Y'a-t-il des erreurs ?
        if (count($errorsList) > 0) {
            // TODO Retourner des erreurs de validation propres
            //? version 1 bourrine : je transforme le tableau en chaine
            $errors = (string) $errorsList;

            //? 2eme version avec mon objet customJsonError
            /* 
                $myCustomError = new CustomJsonError();
                $myCustomError->setErrorValidation($errorsList);
                $myCustomError->errorCode = Response::HTTP_UNPROCESSABLE_ENTITY;
                $myCustomError->message = "Erreur(s) sur la validation de l'objet";
            */
            
            // 3eme version avec une méthode dans mon parent
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        // On sauvegarde l'entité
        $em = $manager->getManager();
        $em->persist($movie);
        $em->flush();

        // TODO : return 201
        return $this->json(
            $movie,
            // je précise que tout est OK de mon coté en précisant que la création c'est bien passé
            // 201
            Response::HTTP_CREATED,
            // REST demande un header Location + URL de la ressource
            [
                // Nom de l'en-tête + URL
                'Location' => $this->generateUrl('api_read_movies', ['id' => $movie->getId()])
            ], 
            //! on n'oublie pas les groupes de sérialisation, même si on redirige
            [
                "groups" => "api_read_movies"
            ]
        );
    }
}
