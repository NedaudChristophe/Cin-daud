<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 */
class Person
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *@Groups({"api_persons_browse", "api_castings_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"api_read_movies", "api_persons_browse", "api_persons_read", "api_castings_read"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"api_read_movies", "api_persons_browse", "api_persons_read", "api_castings_read"})
     * 
     */
    private $lastname;

    /**
     * @ORM\OneToMany(targetEntity=Casting::class, mappedBy="person")
     * @Groups({"api_persons_read"})
     */
    private $castings;

    public function __construct()
    {
        $this->castings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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
            $casting->setPerson($this);
        }

        return $this;
    }

    public function removeCasting(Casting $casting): self
    {
        if ($this->castings->removeElement($casting)) {
            // set the owning side to null (unless already changed)
            if ($casting->getPerson() === $this) {
                $casting->setPerson(null);
            }
        }

        return $this;
    }

    public function getCompleteName(): ?string
    {
        return $this->firstname .' '. $this->lastname;
    }
}
