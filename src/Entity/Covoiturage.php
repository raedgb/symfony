<?php

namespace App\Entity;

use App\Repository\CovoiturageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CovoiturageRepository::class)
 */
class Covoiturage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotNull(message="L'adresse de départ ne peut pas être nulle.")
     */
    private ?string $adresse_depart = null;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotNull(message="L'adresse d'arrivée ne peut pas être nulle.")
     */
    private ?string $adresse_arrive = null;

    /**
     * @ORM\Column(type="date")
     * @Assert\GreaterThanOrEqual("today", message="La date de départ doit être ultérieure ou égale à la date actuelle.")
     */
    private ?\DateTimeInterface $date_depart = null;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotNull(message="L'heure de départ ne peut pas être nulle.")
     */
    private ?string $heure_depart = null;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     max=4,
     *     min=1,
     *     maxMessage="Le nombre de places disponibles doit être inférieure à 4."
     * )
     * @Assert\NotNull(message="Le nombre de places ne peut pas être nul.")
     */
    private ?int $nb_place = null;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull(message="Le prix ne peut pas être nul.")
     */
    private ?float $prix = null;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotNull(message="La description ne peut pas être nulle.")
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotNull(message="Le nom du conducteur ne peut pas être nul.")
     */
    private ?string $nom_conducteur = null;

    /**
     * @ORM\OneToMany(mappedBy="covoiturage", targetEntity=Participation::class)
     */
    private Collection $participation;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $likes = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $dislikes = 0;

    public function __construct()
    {
        $this->participation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresseDepart(): ?string
    {
        return $this->adresse_depart;
    }

    public function setAdresseDepart(string $adresse_depart): self
    {
        $this->adresse_depart = $adresse_depart;

        return $this;
    }

    public function getAdresseArrive(): ?string
    {
        return $this->adresse_arrive;
    }

    public function setAdresseArrive(string $adresse_arrive): self
    {
        $this->adresse_arrive = $adresse_arrive;

        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->date_depart ;
    }

    public function setDateDepart(\DateTimeInterface $date_depart): self
    {
        $this->date_depart = $date_depart;

        return $this;
    }
    public function getDateDepart1(): ?string
    {
        return $this->date_depart ? $this->date_depart->format('Y-m-d') : null;
    }
    public function getHeureDepart(): ?string
    {
        return $this->heure_depart;
    }

    public function setHeureDepart(string $heure_depart): self
    {
        $this->heure_depart = $heure_depart;

        return $this;
    }

    public function getNbPlace(): ?int
    {
        return $this->nb_place;
    }

    public function setNbPlace(int $nb_place): self
    {
       
        $this->nb_place = $nb_place;
    
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNomConducteur(): ?string
    {
        return $this->nom_conducteur;
    }

    public function setNomConducteur(string $nom_conducteur): self
    {
        $this->nom_conducteur = $nom_conducteur;

        return $this;
    }

    /**
     * @return Collection<int, Participation>
     */
    public function getParticipation(): Collection
    {
        return $this->participation;
    }

    public function addParticipation(Participation $participation): self
    {
        if (!$this->participation->contains($participation)) {
            $this->participation->add($participation);
            $participation->setCovoiturage($this);
            $this->nb_place--; 
        } 
            
        return $this;

       
       
    }

    public function removeParticipation(Participation $participation): self
    {
        if ($this->participation->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getCovoiturage() === $this) {
                $participation->setCovoiturage(null);
            }

        }
        return $this;
    }
    public function __toString(): string
    {
        return $this->id;
    }
    public function getLikes(): ?int
    {
        return $this->likes;
    }
    
    public function setLikes(int $likes): self
    {
        $this->likes = $likes;
    
        return $this;
    }
    
    public function incrementLikes(): self
    {
        $this->likes++;
    
        return $this;
    }
    
    public function decrementLikes(): self
    {
        $this->likes--;
    
        return $this;
    }
    
    public function getDislikes(): ?int
    {
        return $this->dislikes;
    }
    
    public function setDislikes(int $dislikes): self
    {
        $this->dislikes = $dislikes;
    
        return $this;
    }
    
    public function incrementDislikes(): self
    {
        $this->dislikes++;
    
        return $this;
    }
    
    public function decrementDislikes(): self
    {
        $this->dislikes--;
    
        return $this;
    }
}
