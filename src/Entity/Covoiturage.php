<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Covoiturage
 *
 * @ORM\Table(name="covoiturage")
 * @ORM\Entity
 */
class Covoiturage
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_covoiturage", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCovoiturage;

    /**
     * @var string
     * @assert\NotBlank
     * @assert\Length(min="4", minMessage="Introduire 4 caractère au minimum")
     * @ORM\Column(name="adresse_depart", type="string", length=50, nullable=false)
     */
    private $adresseDepart;

    /**
     * @var string
     * @assert\NotBlank(message="Ce chaps est obligatoire")
     * @assert\Length(min="5", minMessage="Introduire 5 caractère au minimum")
     * @ORM\Column(name="adresse_arrive", type="string", length=50, nullable=false)
     */
    private $adresseArrive;

    /**
     * @var \DateTime
     * @assert\NotBlank(message="Ce chaps est obligatoire")
     * @assert\GreaterThan("today",message="La date doit superieur à la  date d'aujourd'hui")
     * @ORM\Column(name="date_depart", type="date", nullable=false)
     */
    private $dateDepart;

    /**
     * @var string
     * @assert\NotBlank(message="Ce chaps est obligatoire")
     * @ORM\Column(name="heure_depart", type="string", length=50, nullable=false)
     */
    private $heureDepart;

    /**
     * @var int
     * @assert\NotBlank(message="Ce chaps est obligatoire")
     * @ORM\Column(name="nb_place", type="integer", nullable=false)
     */
    private $nbPlace;

    /**
     * @var float
     * @assert\NotBlank(message="Ce chaps est obligatoire")
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var string
     * @assert\Length(min="8", minMessage="Introduire 8 caractère au minimum")
     * @assert\NotBlank(message="Ce chaps est obligatoire")
     * @ORM\Column(name="description", type="string", length=50, nullable=false)
     */
    private $description;

    /**
     * @var string
     * @assert\Length(min="5", minMessage="Introduire 5 caractère au minimum")
     * @ORM\Column(name="nom_conducteur", type="string", length=50, nullable=false)
     */
    private $nomConducteur;

    public function getIdCovoiturage(): ?int
    {
        return $this->idCovoiturage;
    }

    public function getAdresseDepart(): ?string
    {
        return $this->adresseDepart;
    }

    public function setAdresseDepart(string $adresseDepart): self
    {
        $this->adresseDepart = $adresseDepart;

        return $this;
    }

    public function getAdresseArrive(): ?string
    {
        return $this->adresseArrive;
    }

    public function setAdresseArrive(string $adresseArrive): self
    {
        $this->adresseArrive = $adresseArrive;

        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(\DateTimeInterface $dateDepart): self
    {
        $this->dateDepart = $dateDepart;

        return $this;
    }

    public function getHeureDepart(): ?string
    {
        return $this->heureDepart;
    }

    public function setHeureDepart(string $heureDepart): self
    {
        $this->heureDepart = $heureDepart;

        return $this;
    }

    public function getNbPlace(): ?int
    {
        return $this->nbPlace;
    }

    public function setNbPlace(int $nbPlace): self
    {
        $this->nbPlace = $nbPlace;

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
        return $this->nomConducteur;
    }

    public function setNomConducteur(string $nomConducteur): self
    {
        $this->nomConducteur = $nomConducteur;

        return $this;
    }


}
