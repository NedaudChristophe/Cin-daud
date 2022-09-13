<?php

namespace App\Controller\Front;


use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GenreController extends AbstractController
{
    
    
  /**
   * Undocumented function
   * @Route("/genre/{id}", name="movie_by_genre", methods={"GET"},
   * requirements={"id"="\d+"})
   * @param [type] $id
   * @param Genre $genre
   * @param GenreRepository $genreRepository
   * @param Movie $movie
   * @param MovieRepository $movieRepository
   * @return Response
   */
    public function findMoviesByGenre($id, GenreRepository $genreRepository, MovieRepository $movieRepository):Response
    {
        $genre = $genreRepository->find($id);
        $listGenre = $genreRepository->findAll();
        $movielist = $movieRepository->findMoviesByGenre($id);
        // dd($movielist);
        return $this->render('front/genre/index.html.twig',   [
            'movielist' => $movielist,
            'listGenre' => $listGenre,
            'genre' => $genre
            
        ]);
        


    }
}
