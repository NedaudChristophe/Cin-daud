<?php

namespace App\Services;

use App\Entity\Movie;

class AutoRating
{
    /**
     
     * @param Movie $movie 
     * @param integer $newRating 
     * @return float 
     */
    public function calculRating(Movie $movie, int $newRating): float
    {
        
        $allReviews = $movie->getReviews();

        
        $totalRating = 0;
        foreach ($allReviews as $review)
        {
            
            $rating = $review->getRating();
            
            $totalRating += $rating;
        }
       
        $totalRating += $newRating;

        
        $nombreDeReview = count($allReviews) + 1;

        
        $calculRating = round($totalRating / $nombreDeReview, 2);

        
        return $calculRating;
    }
}