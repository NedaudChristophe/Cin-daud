<?php

namespace App\Controller\Front;

use App\Entity\Review;


use App\Form\ReviewType;
use App\Services\AutoRating;
use App\Repository\MovieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReviewController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER", message="No access! Get out!")
     * @Route("/review/add/{id}", name="app_review-add", methods={"POST","GET"}, requirements={"id"="\d+"}))
     * @param MovieRepository $movieRepository
     * @return Response
     * @param int $id 
     */ 
     
    public function add ($id, ManagerRegistry $doctrine, Request $request, MovieRepository $movieRepository, 
    AutoRating $serviceRating): Response
    {
        
        //!Objectif : On veut créer un nouvel article à partir des données saisies dans le formulaire

        // On crée une nouvelle instance de Review
        $review = new Review();
        $movie = $movieRepository->find($id);
        
        // Je veux chercher ReviewType qui est la classe qui définit le formulaire pour ajouter une review
        $form = $this->createForm(ReviewType::class, $review);

        // Le Form inspecte la Requête
        $form->handleRequest($request);
        // ET remplit le l'instance de Review contenue dans.. $newReview
          
        // traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $review->setMovie($movie);
            //dd($review);

            // TODO: mettre à jour le rating de movie
            $newRating = $serviceRating->calculRating($movie, $review->getRating());
            
            $movie->setRating($newRating);
            // On va faire appel au Manager de Doctrine
            $entityManager = $doctrine->getManager();
            $entityManager->persist($review);
            $entityManager->flush();

            // On redirige vers la fiche film
            return $this->redirectToRoute('movieShow', ['slug' => $review->getMovie()->getSlug()]);
        }
           
        //? utilisation de renderForm à la place render()
        //https://symfony.com/doc/5.4/forms.html#rendering-forms
        return $this->renderForm('front/review/add.html.twig', [
            'form' => $form,
            'movie' => $movie
        ]
            );
    }


}
