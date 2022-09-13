<?php
// src/Controller/Front/TestController.php 
namespace App\Controller\Front;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Services\OmdbApi;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Nous permet de manager nos entités et notre BDD
use Doctrine\Persistence\ManagerRegistry;


class TestController extends AbstractController
{
    /**
     * @Route("/test/add_movie", name="app_test_addmovie")
     */
    public function add(ManagerRegistry $doctrine): Response
    {
        // On crée une entité "Doctrine"
        $newMovie = new Movie();
        $newMovie->setTitle('Arnold et Willy');
        $newMovie->setType('Série');
        $newMovie->setReleaseDate(new DateTime('1978-11-03'));
        dump($newMovie);

        // On récupère le manager d'entité de Doctrine
        $entityManager = $doctrine->getManager();
        // On demande au manager d'entité de prendre connaissance de notre objet
        $entityManager->persist($newMovie);

        // Ensuite on lui demande de mettre à jour la BDD 
        $entityManager->flush();

        dd($newMovie);

        return $this->render('front/test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    /**
     * @Route("/test/show_movie/{id}", name="app_test_showmovie")
     * 
     * Pour trouver les données d'un film, 
     * on a besoin du repository de cette entité
     * donc pour trouver une entité Movie,
     * il nous le MovieRepository
     */
    public function show($id, MovieRepository $movieRepository)
    {
        $movie = $movieRepository->find($id);

        // gérer la 404

        dd($movie);
    }

    /**
     * @Route("/test/list_movies", name="app_test_listmovies")
     *
     * @param MovieRepository $movieRepository
     * @return void
     */
    public function list(MovieRepository $movieRepository)
    {
        $movies = $movieRepository->findAll();

        dump($movies);

        $seasons = $movies[1]->getSeasons();

        /*
        le lazy loading, Doctrine n'ira chercher les saisons que lorsqu'on aura besoin d'afficher les informations 

        
        foreach($seasons as $season)
        {
            echo 'saison ' . $season->getNumber() . '<br>';
        }

        dump($movies); */

        return $this->render('front/test/index.html.twig');
        
    }


    /**
     * @Route("/test/update_movie/{id}", name="app_test_updatemovie")
     * 
     * @param [type] $id
     * @param MovieRepository $movieRepository
     * @param ManagerRegistry $doctrine
     * @return void
     */
    public function update($id, MovieRepository $movieRepository, ManagerRegistry $doctrine)
    {
        $movie = $movieRepository->find($id);
        dump($movie);
        $movie->setType('Film');

        // On récupère le manager d'entité de Doctrine
        $entityManager = $doctrine->getManager();
        // Pas besoin de persist() car l'entity manager sait déjà qu'il y a eu une modification sur une entité
        // Ensuite on lui demande de mettre à jour la BDD 
        $entityManager->flush();

        dd($movie);
    }


    /**
     * @Route("/test/delete_movie/{id}", name="app_test_deletemovie")
     *
     * @param [type] $id
     * @param MovieRepository $movieRepository
     * @param ManagerRegistry $doctrine
     * @return void
     */
    public function delete($id, MovieRepository $movieRepository, ManagerRegistry $doctrine )
    {
        $movie = $movieRepository->find($id);
        // On récupère le manager d'entité de Doctrine
        $entityManager = $doctrine->getManager();
        // On envoie à l'netity manager la demande de suppression pour ce film
        $entityManager->remove($movie);
        // Ensuite on lui demande de mettre à jour la BDD 
        $entityManager->flush();

    }

    /**
     * @Route("/omdb",name="test_omdb")
     */
    public function omdb(OmdbApi $omdbApi)
    {
        // en dur pour le test
        $titre = 'totoro';

        // TODO : utilisation de mon service
        $urlPoster = $omdbApi->fetchPoster($titre);
        //dd($urlPoster);
        // TODO affichage de la réponse
        return $this->render('test/test_omdb.html.twig', 
        [
            'url_poster' => $urlPoster
        ]);
    }
}
