<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class MySlugger
{
    
    /**
    * instance de SluggerInterface
    * @var SluggerInterface
    */
    private $slugger;

    
    /**
    *
    * @var bool
    */
    private $paramLower;
        
    /**
    * Constructor
    * @param string 
    */
    public function __construct(SluggerInterface $slugger, ContainerBagInterface $params, $lower)
    {
        $this->slugger = $slugger;
        $valeurServiceYaml = $params->get('myslugger.lower');

        $this->paramLower = ($valeurServiceYaml === 'true');
    }

    /**
     *
     * @param string $titre
     * @return string titre sluggifié
     */
    public function slug(string $titre): string
    {  
        $slug = $this->slugger->slug($titre);

        if ($this->paramLower) {
            $slug = $slug->lower();
        }

        return $slug;
    }
}