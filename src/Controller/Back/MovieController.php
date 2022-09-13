<?php

namespace App\Controller\Back;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Services\MySlugger;
use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 
 * @IsGranted("ROLE_MANAGER")
 * @Route("/back/movie")
 */
class MovieController extends AbstractController
{
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }    
        
    



    /**
     * 
     * @IsGranted("ROLE_MANAGER")
     * @Route("/", name="app_back_movie_index", methods={"GET"})
     */
    public function index(MovieRepository $movieRepository): Response
    {

        return $this->render('back/movie/index.html.twig', [
            'movies' => $movieRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN") 
     * @Route("/new", name="app_back_movie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MovieRepository $movieRepository/*, MySlugger $slugger*/): Response
    {   

        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             $movie->setSlug(strtolower($this->slugger->slug($movie->getTitle())));
            $movieRepository->add($movie, true);

            return $this->redirectToRoute('app_back_movie_index', [], Response::HTTP_SEE_OTHER);
        }



            
        return $this->renderForm('back/movie/new.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN") 
     * @IsGranted("ROLE_MANAGER")
     * @Route("/{id}", name="app_back_movie_show", methods={"GET"})
     */
    public function show(Movie $movie): Response
    {
        return $this->render('back/movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}/edit", name="app_back_movie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Movie $movie, MovieRepository $movieRepository): Response
    {
       

        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movieRepository->add($movie, true);

            return $this->redirectToRoute('app_back_movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    /**
     * * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="app_back_movie_delete", methods={"POST"})
     */
    public function delete(Request $request, Movie $movie, MovieRepository $movieRepository): Response
    {
        
        if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->request->get('_token'))) {
            $movieRepository->remove($movie, true);
        }

        return $this->redirectToRoute('app_back_movie_index', [], Response::HTTP_SEE_OTHER);
    }
}
