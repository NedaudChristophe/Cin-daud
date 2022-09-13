<?php
// src/Controller/MainController.php 
namespace App\Controller\Front;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use App\Services\AutoRating;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class MainController extends AbstractController
{

    /**
     * @Route("/", name="home", methods={"GET"})
     * controller
     * @return Response
     */
    public function home(
        MovieRepository $movieRepository,
        GenreRepository $genreRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {


        // On va chercher les listes des films dans notre Model Movie
        /* $movie = new Movie();
        $listMovies = $movie->getAllMovies(); */

        //<3 Maintenant on utilise le MovieRepository
        $data = $movieRepository->findAll();
        $listGenre = $genreRepository->findAll();

        $listMovies = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );

        // On transmet à notre vue la liste des films
        return $this->render(
            'front/main/home.html.twig',
            [
                "listMovies" => $listMovies,
                "listGenre" => $listGenre
            ]
        );
    }

    /**
     * @Route("/list", name="list", methods={"GET"})
     * 
     * @return Response
     */
    public function list(
        MovieRepository $movieRepository,
        GenreRepository $genreRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        // On va chercher les listes des films dans notre Model Movie
        //$movie = new movie();
        $data = $movieRepository->findAll();
        $listGenre = $genreRepository->findAll();

        $listMovies = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );


        // On transmet à notre vue la liste des films
        return $this->render(
            'front/main/list.html.twig',
            [
                "listMovies" => $listMovies,
                "listGenre" => $listGenre
            ]
        );
    }


    /**
     * @Route(
     *      "/movie/{slug}", 
     *      name="movieShow", 
     *      methods={"GET"}, 
     *      )
     * 
     * @param string $slug 
     * @return Response
     */
    public function movieShow(MovieRepository $movieRepository, Movie $movie): Response
    {
        //<3 Maintenant on utilise le MovieRepository
        $dataMovie = $movieRepository->find($movie);

        // Si l'id contient un index qui n'existe pas
        if (is_null($dataMovie)) {

            // on lance une exception qui est particulière
            // puisqu'elle renvoie aussi au navigateur un status HTTP 404
            throw $this->createNotFoundException('Le film n\'existe pas.');
        }

        // si on a trouvé les données du film,
        // alors, on va chercher les données du casting TRIEES
        //$castings = $castingRepository->findBy(
        // ['movie' => $dataMovie], // les données du castings pour un film donné : where movie_id=?
        // ['creditOrder' => 'ASC'] // équivaut à : orderby credit_order asc
        //);

        // on renvoie le template twig dans lequel on transmet les données du film demandé en paramètre
        return $this->render(
            'front/main/movie-show.html.twig',
            [
                'movie' => $dataMovie,

            ]
        );
    }

    /**
     * @Route(
     *      "/movie/{id}/review/add", 
     *      name="movie_review_add", 
     *      methods={"GET","POST"}, 
     *      requirements={"id"="\d+"})
     *
     * @param Movie $movie
     * => Param converter
     * * Récupération automatique du Movie via son {id}
     * Plus besoin de faire un find pour récupérer l'objet Movie correspondant à l'id en paramètre d'url
     * @link https://symfony.com/doc/current/doctrine.html#automatically-fetching-objects-paramconverter
     * 
     * 
     * @return Response
     */
    public function movieAddReview(
        Movie $movie,
        Request $request,
        ManagerRegistry $doctrine,
        AutoRating $serviceRating
    ) {
        // Grace au param converter, symfony va chercher tout seul l'objet Movie correspondant à l'id dans l'url

        // Je veux chercher ReviewType qui est la classe qui définit le formulaire pour ajouter une review
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);

        //récupération de la réponse
        $form->handleRequest($request);

        // Si le formulaire a été soumis et que les données sont valides...
        if ($form->isSubmitted() && $form->isValid()) {

            // On relie directement l'entité Movie à la Review courante
            $review->setMovie($movie);

            // TODO: mettre à jour le rating de movie
            $newRating = $serviceRating->calculRating($movie, $review->getRating());
            $movie->setRating($newRating);
            // pas besoin de persist car $movie existe déjà

            // Instant où on enregistre tout en BDD :

            $em = $doctrine->getManager();
            $em->persist($review);
            // $review ET $movie sont enregistrés
            $em->flush();

            // redirection vers la page movieShow
            return $this->redirectToRoute('movieShow', ['slug' => $movie->getSlug()]);
        }

        //? utilisation de renderForm à la place render()
        //https://symfony.com/doc/5.4/forms.html#rendering-forms
        return $this->renderForm(
            'front/main/movie_review_add.html.twig',
            [
                'form' => $form,
                'movie' => $movie
            ]
        );
    }


    
}
