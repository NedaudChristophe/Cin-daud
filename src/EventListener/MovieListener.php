<?php
namespace App\EventListener;

use App\Entity\Movie;
use App\Services\MySlugger;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class MovieListener
{
    /**
    * instance de MySlugger
    *
    * @var MySlugger
    */
    private $slugger;
    
    /**
    * Constructor
    */
    public function __construct(MySlugger $slugger)
    {
        $this->slugger = $slugger;
    }
    /**
     * fait la mise à jour du slug pour l'objet Movie
     *
     * @param Movie $movie
     * @param LifecycleEventArgs $event
     */
    public function generateSlug(Movie $movie, LifecycleEventArgs $event): void
    {
        // die("je suis passé par là : MovieListener");
        //dd($movie);
        $newSlug = $this->slugger->slug($movie->getTitle());
        $movie->setSlug($newSlug);
        
    }
}
//code pour slugger titre en preupdate