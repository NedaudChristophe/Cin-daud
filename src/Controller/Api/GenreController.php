<?php

namespace App\Controller\Api;

use Exception;
use App\Entity\Genre;
use App\Repository\GenreRepository;
use App\Controller\Api\ApiController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;


/**
 * @Route("/api/genres",name="api_genres_")
 */
class GenreController extends ApiController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(GenreRepository $repo): JsonResponse
    {
        $all = $repo->findAll();
        
        return $this->json(
            // data
            $all,
            // code HTTP pour dire que tout se passe bien (200) 
            Response::HTTP_OK,
            // les entêtes HTTP, on les utilise dans très peu de cas, donc valeur par défaut : []
            [],
            // le contexte, on l'utilise pour spécifier les groupes de serialisation
            [
                // je lui donne le/les noms de groupes de serialisation
                "groups" => 
                [
                    "api_genres_browse"
                ]
            ]
        );
    }

    /**
     * @Route("/{id}",name="read", 
     *      methods={"GET"},
     *      requirements={"id"="\d+"})
     */
    public function read(Genre $genre)
    {
        return $this->json(
            // le genre
            $genre,
            Response::HTTP_OK,
            [],
            // le contexte, on l'utilise pour spécifier les groupes de serialisation
            [
                // je lui donne le/les noms de groupes de serialisation
                "groups" => 
                [
                    "api_genres_read"
                ]
            ]
        );
    }

    /**
     * @Route("",name="add", methods={"POST"})
     * //@IsGranted("ROLE_MANAGER")
     *
     * @param Request $request
     * @param GenreRepository $repo
     * @return JsonResponse
     */
    public function add(
        Request $request,
        GenreRepository $repo,
        SerializerInterface $serializerInterface,
        ValidatorInterface $validator
        ): JsonResponse
    {
        
        // on ne doit pas utiliser des méthodes qui retourne du HTML
        // $this->denyAccessUnlessGranted("ROLE_MANAGER");
        // on teste les droits à la main
        if (!$this->isGranted("ROLE_MANAGER"))
        {
            return $this->json("Authorised user only", Response::HTTP_FORBIDDEN);
        }

        // TODO : récuperer les infos dans le body/content de la requete
        $jsonContent = $request->getContent();
        // dd($jsonContent);
        // {"name":"le super genre"}

        // pour désérialiser il nous faut le composant de serialisation
        // on l'obtient avec le service SerializerInterface
        //! faire attention à ce que nous fournit l'utilisateur !!!!!
        try // essaye d'éxécuter ce code
        {
            /** @var Genre $newGenre */
            $newGenre = $serializerInterface->deserialize($jsonContent, Genre::class, 'json');
        }
        catch(Exception $e) // si tu n'y arrives pas
        {
            //dd($e);
            // j'arrive ici si une exception a été lancée
            // dans notre cas si le json fourni n'est pas bien écrit : en fait c'est pas du json
            return $this->json("Le JSON est mal formé", Response::HTTP_BAD_REQUEST);
        }
        //dd($newGenre);

        // TODO : valider les infos
        //! faire attention à ce que nous fournit l'utilisateur !!!!!
        // car on n'a pas de formulaire qui nous valide tout : $form->isValid()
        $errors = $validator->validate($newGenre);
        
        if (count($errors)> 0)
        {
            //dd($errors);
            // TODO : à améliorer, car illisible
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // TODO : faire l'insertion
        // on utilise la version raccourcie par le repository
        // le paramètre true, nous fait le flush() auto
        // ça correspond à persist() ET flush()
        $repo->add($newGenre, true);

        // TODO : faire un retour comme quoi tout c'est bien passé
        // on fournit l'objet qui a été créé pour que notre utilisateur puisse avoir l'ID
        return $this->json(
            // le genre avec l'ID
            $newGenre,
            Response::HTTP_CREATED,
            [
                // Nom de l'en-tête + URL
                'Location' => $this->generateUrl('api_genres_read', ['id' => $newGenre->getGenreId()])
            ]
            // comme on redirige, on n'a pas besoin de spécifier des groupes de sérialisation
        );
    }


    /**
     * @Route("/{id}",name="edit", 
     *      methods={"PUT", "PATCH"},
     *      requirements={"id"="\d+"})
     */
    public function edit(
        Genre $genre = null,
        Request $request,
        ManagerRegistry $doctrine,
        SerializerInterface $serializerInterface
        ): JsonResponse
    {
        //dd($genre);
        // gestion du paramConverter
        if ($genre === null){ return $this->json404(); }

        //dump($genre);
        $jsonContent = $request->getContent();
        // dump($jsonContent);
        // @link https://symfony.com/doc/current/components/serializer.html#deserializing-in-an-existing-object
        //? avec le paramètre context, on précise l'objet à mettre à jour : $genre
        //! The AbstractNormalizer::OBJECT_TO_POPULATE is only used for the top level object. 
        //! If that object is the root of a tree structure, all child elements that exist in the normalized data will be re-created with new instances.
        $serializerInterface->deserialize(
            $jsonContent,
            Genre::class, 
            'json', 
            [AbstractNormalizer::OBJECT_TO_POPULATE => $genre]
        );
        //dump($genre);
        
        $doctrine->getManager()->flush();
        
        return $this->json(
            // le genre avec l'ID
            $genre,
            Response::HTTP_PARTIAL_CONTENT,
            [
                // Nom de l'en-tête + URL
                'Location' => $this->generateUrl('api_genres_read', ['id' => $genre->getGenreId()])
            ],
            [
                "groups" => "api_genres_read"
            ]
        );
    }

    /**
     * @Route("/{id}",name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     *
     * @param Genre $genre
     */
    public function delete(Genre $genre = null, GenreRepository $repo)
    {
        // gestion du paramConverter
        if ($genre === null){ return $this->json404(); }
        
        // je supprime tout simplement
        $repo->remove($genre, true);

        return $this->json(
            null,
            Response::HTTP_NO_CONTENT,
            [
                // Nom de l'en-tête + URL
                'Location' => $this->generateUrl('api_genres_browse')
            ]
        );
    }
}