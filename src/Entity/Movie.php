<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 *  on active la gestion des EventDoctrine avec cette annotation
 * @ORM\HasLifecycleCallbacks()
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api_movies_browse", "api_movies_read", "api_genres_read", "api_persons_read", "api_castings_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_movies_browse","api_movies_read", "api_genres_read", "api_browse_genres","api_season_movie", "api_persons_read", "api_castings_read"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=20)
     * @Groups({"api_movies_browse","api_movies_read", "api_genres_read", "api_persons_read"})
     *
     */
    private $type;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"api_movies_read"})
     *
     */
    private $releaseDate;

    /**
     * @ORM\OneToMany(targetEntity=Season::class, mappedBy="movie", orphanRemoval=true)
     * @Groups({"api_movies_read", "api_season_movie"})
     */
    private $seasons;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="movies")
     * @Groups({"api_movies_read"})
     */
    private $genre;

    /**
     * @ORM\OneToMany(targetEntity=Casting::class, mappedBy="movie", orphanRemoval=true)
     * @ORM\OrderBy({"creditOrder" = "ASC"})
     * @Groups({"api_movies_read"})
     * #https://www.doctrine-project.org/projects/doctrine-orm/en/2.11/reference/annotations-reference.html#annref_orderby
     */ 
    private $castings; 
    

    /**
     * @ORM\Column(type="integer")
     * @Groups({"api_movies_browse","api_movies_read", "api_genres_read"})
     */
    private $duration;

    /**
     * @ORM\Column(type="text")
     * @Groups({"api_movies_browse", "api_genres_read"})
     */
    private $summary;

    /**
     * @ORM\Column(type="text")
     * @Groups({"api_movies_read", "api_read_movies"})
     */
    private $synopsis;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_movies_browse", "api_movies_read", "api_persons_read"})
     */
    private $poster;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"api_movies_browse", "api_movies_read", "api_genres_read"})
     */
    private $rating;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="movie")
     * @Groups({"api_movies_read"})
     */
    private $reviews;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_movies_browse", "api_movies_read", "api_genres_read","api_persons_read"})
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    
    public function __construct()
    {
        $this->seasons = new ArrayCollection();
        $this->genre = new ArrayCollection();
        $this->castings = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * @return Collection<int, Season>
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons[] = $season;
            $season->setMovie($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): self
    {
        if ($this->seasons->removeElement($season)) {
            // set the owning side to null (unless already changed)
            if ($season->getMovie() === $this) {
                $season->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenre(): Collection
    {
        return $this->genre;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genre->contains($genre)) {
            $this->genre[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        $this->genre->removeElement($genre);

        return $this;
    }

    /**
     * @return Collection<int, Casting>
     */
    public function getCastings(): Collection
    {
        return $this->castings;
    }

    public function addCasting(Casting $casting): self
    {
        if (!$this->castings->contains($casting)) {
            $this->castings[] = $casting;
            $casting->setMovie($this);
        }

        return $this;
    }

    public function removeCasting(Casting $casting): self
    {
        if ($this->castings->removeElement($casting)) {
            // set the owning side to null (unless already changed)
            if ($casting->getMovie() === $this) {
                $casting->setMovie(null);
            }
        }

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

 

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setMovie($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getMovie() === $this) {
                $review->setMovie(null);
            }
        }

        return $this;
    }
/*
    public function __toString():string
    {
        return $this->title;
    }

*/

public function getSlug(): ?string
{
    return $this->slug;
}

public function setSlug(string $slug): self
{
    $this->slug = $slug;

    return $this;
}

public function getUpdatedAt(): ?\DateTimeInterface
{
    return $this->updatedAt;
}

public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
{
    $this->updatedAt = $updatedAt;

    return $this;
}

    /**
     * @ORM\PreUpdate
     * ! ne fonctionne que si l'annotation sur la classe est présente 
     * ! pas d'injection de dépendance ici ==> voir EventListener
     * 
     */
    public function setUpdateAtValue(): void
    {
       // $this->title .= " updated"; //pour rajouter updated au titre du film lors de la mise à jour blague
        
        $this->updatedAt = new DateTime('now');
    }

}
