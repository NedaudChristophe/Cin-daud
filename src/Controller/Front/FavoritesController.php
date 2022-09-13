<?php

namespace App\Controller\Front;


use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FavoritesController extends AbstractController
{

    private $sessionTab;

    public function __construct(SessionInterface $session)
    {
        $this->sessionTab = $session->get('favoris') ?? [];
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/favorites", name="favorites_list", methods={"GET"})
     */
    public function list(MovieRepository $movieRepository): Response
    {
        // chercher les données grâce au model Movie
       
        $favoritesList = [];
        foreach($this->sessionTab as $idMovie) {
            $favoritesList[] = $movieRepository->find($idMovie);
        }

        return $this->render('front/favorites/index.html.twig', [
            'favoritesList' => $favoritesList,
            
        ]);
    }

    // Ajout d'un nouveau film
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/favorites/add", name="favorites_add", methods={"POST"})
     *
     * @return Response
     */
    public function add(Request $request, SessionInterface $session) :Response
    {
        // récupérer l'id du film à ajouter dans favoris
        // POST : request
        $id_favorite = $request->request->get('id_favorite');

        array_push($this->sessionTab, $id_favorite);
        $this->sessionTab = array_unique($this->sessionTab);

        // ajouter le film dans la liste des favoris
        $session->set('favoris', $this->sessionTab);

        // En PHP, équivaut à :
        //$_SESSION['favoris'] = $this->sessionTab;

        // faire un message d'alerte
        // flash message

        // renvoyer vers la page des favoris
        return $this->redirectToRoute('favorites_list');
    }
   
    // suppression d'un film
    /**
     * @Route("/favorites/delete", name="favorites_delete")
     *
     * @return Response
     */
    public function delete(Request $request, SessionInterface $session) :Response
    {
        // récupérer l'id du film à supprimer de la liste des favoris
        $id_favorite = $request->request->get('id_favorite');
        // supprimer l'id du film dans la liste des favoris
        unset($this->sessionTab, $id_favorite, );
        $this->sessionTab = array_unique($this->sessionTab);
        
        // ajouter le film dans la liste des favoris
        $session->set('favoris', $this->sessionTab);
        

        // faire un message d'alerte
        // renvoyer vers la page des favoris
        return new Response('action de suppression d\un favori');
    }

    // suppression de tous les films
    /**
     * @Route("/favorites/delete-all", name="favorites_delete-all")
     *
     * @return Response
     */
    public function deleteAll(Request $request, SessionInterface $session) :Response
    {
        // supprimer tous les films de la session
        $session->remove('favoris', $this->sessionTab);
        // faire un message d'alerte
        // renvoyer vers la page des favoris
        return $this->redirectToRoute('favorites_list');
    }
}