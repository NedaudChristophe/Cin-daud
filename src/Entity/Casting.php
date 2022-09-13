<?php

namespace App\Entity;

use App\Repository\CastingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CastingRepository::class)
 */
class Casting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api_castings_browse", "api_castings_read"})
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"api_read_movies", "api_persons_read", "api_castings_browse", "api_castings_read", "api_castings_add"})
     */
    private $role;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"api_read_movies", "api_castings_browse", "api_castings_read", "api_castings_add"})
     */
    private $creditOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="castings",cascade={"persist"}))
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     * @Groups({"api_persons_read", "api_castings_read","api_castings_add" ,"api_castings_add"})
     */
    private $movie;

    /**
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="castings",cascade={"persist"}))
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     * @Groups({"api_read_movies", "api_castings_read", "api_castings_add"})
     */
    private $person;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getCreditOrder(): ?int
    {
        return $this->creditOrder;
    }

    public function setCreditOrder(int $creditOrder): self
    {
        $this->creditOrder = $creditOrder;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }
}
