<?php
// src/Entity/Genre.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
// ! Pour utiliser les possibilités de mapping de Doctrine
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity
 */
class Genre
{


    /**
     * @ORM\Id
     * ? signifie : que la prop est une clé primaire/identifiant
     * 
     * @ORM\GeneratedValue
     * ? signifie : que la valeur sera en auto incrément
     * 
     * @ORM\Column(type="integer", name="id")
     * @Groups({"api_genres_read", "api_genres_browse"})
     */
    private $genreId;



    /**
     * @ORM\Column(length=50)
     * @Groups({"api_read_movies", "api_genres_read", "api_genres_browse"})
     * @Assert\Length(
     *      min=3,
     *      minMessage = "Votre genre doit contenir {{ limit }} charactères minimun")
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Movie::class, mappedBy="genre")
     * @Groups({"api_genres_read"})
     */
    private $movies;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
    }

    /**
     * retourne l'identifiant du genre
     */
    public function getGenreId(): ?int
    {
        return $this->genreId;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string
     * @return  self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Movie>
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): self
    {
        if (!$this->movies->contains($movie)) {
            $this->movies[] = $movie;
            $movie->addGenre($this);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): self
    {
        if ($this->movies->removeElement($movie)) {
            $movie->removeGenre($this);
        }

        return $this;
    }
}


//? Dans notre BDD, nous avons prévu de créer 
// Une colonne id
// une colonne name
/* 
    id	int(10) unsigned Auto Increment	
    name	varchar(50)
*/

//? php bin/console make:migration
// Cette commande va créer un fichier pour mettre à jour la BDD par rapport à nos classes entités définies
//? php bin/console doctrine:migrations:migrate
// Met à jour la BDD avec les requêtes contenues dans les fichiers de migration

//? php bin/console doctrine:schema:validate
// vérifie si nos classes entités sont bien synchronisées avec la BDD
// + vérifie si la syntaxe de mapping est correcte