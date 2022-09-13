<?php

namespace App\Controller\Api;

use Exception;
use App\Entity\Casting;
use App\Repository\CastingRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;


/**
 * @Route("/api/castings",name="api_castings_")
 */
class CastingController extends ApiController
{
    /**
     * @Route("", name="browse", methods={"GET"}))
     */
    public function browse(CastingRepository $castingRepository): JsonResponse
    {
        $allCasting = $castingRepository->findAll();

        return $this->json(
            // data
            $allCasting,
            // code HTTP pour dire que tout se passe bien (200) 
            Response::HTTP_OK,
            // les entêtes HTTP, on les utilise dans très peu de cas, donc valeur par défaut : []
            [],
            // le contexte, on l'utilise pour spécifier les groupes de serialisation
            [
                // je lui donne le/les noms de groupes de serialisation
                "groups" =>
                [
                    "api_castings_browse"
                ]
            ]
        );
    }

    /**
     * @Route("/{id}",name="read", methods={"GET"})
     */
    public function read(Casting $casting = null)
    {
        // puisque j'autorise la valeur NULL
        // le paramConverter ne va plus faire d'erreur si il ne trouve pas d'objet.
        // il pourra fournir la valeur par defaut : null
        //! il faut donc que l'on gère ce cas là
        if ($casting === null) {
            // on renvoie donc une 404
            return $this->json(
                [
                    "erreur" => "le film n'a pas été trouvé",
                    "code_error" => 404
                ],
                Response::HTTP_NOT_FOUND, // 404
                // les autres paramètres sont inutiles
            );
        }
        return $this->json(
            $casting, 
            // code HTTP pour dire que tout se passe bien (200) 
            Response::HTTP_OK,
            // les entêtes HTTP, on les utilise dans très peu de cas, donc valeur par défaut : []
            [],
            // le contexte, on l'utilise pour spécifier les groupes de serialisation
            [
                // je lui donne le/les noms de groupes de serialisation
                "groups" =>
                [
                    "api_castings_read"
                ]
            ]
        );
       dd($casting);
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
        CastingRepository $repo,
        SerializerInterface $serializerInterface,
        ValidatorInterface $validator
        ): JsonResponse
    {
        
        // on ne doit pas utiliser des méthodes qui retourne du HTML
        // $this->denyAccessUnlessGranted("ROLE_MANAGER");
        // on teste les droits à la main
        //if (!$this->isGranted("ROLE_MANAGER"))
        //{
        //    return $this->json("Authorised user only", Response::HTTP_FORBIDDEN);
       // }

        // TODO : récuperer les infos dans le body/content de la requete
        $jsonContent = $request->getContent();
        // dd($jsonContent);
        // {"name":"le super genre"}

        // pour désérialiser il nous faut le composant de serialisation
        // on l'obtient avec le service SerializerInterface
        //! faire attention à ce que nous fournit l'utilisateur !!!!!
        try // essaye d'éxécuter ce code
        {
            /** @var Casting $newPerson */
            $newCasting = $serializerInterface->deserialize($jsonContent, Casting::class, 'json');
        }
        catch(Exception $e) // si tu n'y arrives pas
        {
            //dd($e);
            // j'arrive ici si une exception a été lancée
            // dans notre cas si le json fourni n'est pas bien écrit : en fait c'est pas du json
            return $this->json("Le JSON est mal formé", Response::HTTP_BAD_REQUEST);
        }
       // dd($newPerson);

        // TODO : valider les infos
        //! faire attention à ce que nous fournit l'utilisateur !!!!!
        // car on n'a pas de formulaire qui nous valide tout : $form->isValid()
        $errors = $validator->validate($newCasting);
        
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
        $repo->add($newCasting, true);

        // TODO : faire un retour comme quoi tout c'est bien passé
        // on fournit l'objet qui a été créé pour que notre utilisateur puisse avoir l'ID
        return $this->json(
            // le genre avec l'ID
            $newCasting,
            Response::HTTP_CREATED,
            [
                // Nom de l'en-tête + URL
                'Location' => $this->generateUrl('api_castings_read', ['id' => $newCasting->getId()])
            ],
            [
                // je lui donne le/les noms de groupes de serialisation
                "groups" =>"api_castings_read"
               
            ]
                
                    
        );
    }


    /**
     * @Route("/{id}",name="edit", 
     *      methods={"PUT", "PATCH"},
     *      requirements={"id"="\d+"})
     */
    public function edit(
        Casting $casting = null,
        Request $request,
        ManagerRegistry $doctrine,
        SerializerInterface $serializerInterface
        ): JsonResponse
    {
        //dd($casting);
        // gestion du paramConverter
        if ($casting === null){ return $this->json404(); }

       // dd($casting);
        $jsonContent = $request->getContent();
        // dump($jsonContent);
        // @link https://symfony.com/doc/current/components/serializer.html#deserializing-in-an-existing-object
        //? avec le paramètre context, on précise l'objet à mettre à jour : $person
        //! The AbstractNormalizer::OBJECT_TO_POPULATE is only used for the top level object. 
        //! If that object is the root of a tree structure, all child elements that exist in the normalized data will be re-created with new instances.
        $serializerInterface->deserialize(
            $jsonContent,
            Casting::class, 
            'json', 
            [AbstractNormalizer::OBJECT_TO_POPULATE => $casting]
        );
        // dump($casting);
        
        $doctrine->getManager()->flush();
        
        return $this->json(
            // le getId avec l'ID
            $casting,
            Response::HTTP_PARTIAL_CONTENT,
            [
                // Nom de l'en-tête + URL
                'Location' => $this->generateUrl('api_castings_read', ['id' => $casting->getId()])
            ],
            [
                "groups" => "api_castings_read"
            ]
        );
    }

    /**
     * @Route("/{id}",name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     *
     * @param Casting $casting
     */
    public function delete(Casting $casting = null, CastingRepository $repo)
    {
        // gestion du paramConverter
        if ($casting === null) {
            return $this->json404();
        }
        
        // je supprime tout simplement
        $repo->remove($casting, true);

        return $this->json(
            null,
            Response::HTTP_NO_CONTENT,
            [
                // Nom de l'en-tête + URL
                'Location' => $this->generateUrl('api_castings_browse')
            ]
        );
    }
}
 
