<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Mission
 *
 * @ORM\Table(name="mission")
 * @ORM\Entity
 */
class Mission
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_mission", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMission;

    /**
     * @var string
     * @assert\GreaterThan("today",message="La date doit superieur à la  date d'aujourd'hui")
     * @ORM\Column(name="date_livraison", type="string", length=50, nullable=false)
     */
    private $dateLivraison;

    /**
     * @var float
     * @assert\NotBlank(message="Ce chaps est obligatoire")
     * @ORM\Column(name="solde_livraison", type="float", precision=10, scale=0, nullable=false)
     */
    private $soldeLivraison;

    /**
     * @var string
     * @assert\NotBlank(message="Ce chaps est obligatoire")
     * @ORM\Column(name="id_livreur", type="string", length=50, nullable=false)
     */
    private $idLivreur;

    /**
     * @var int
     * @assert\NotBlank(message="Ce chaps est obligatoire")
     * @ORM\Column(name="id_colis", type="integer", nullable=false)
     */
    private $idColis;

    public function getIdMission(): ?int
    {
        return $this->idMission;
    }

    public function getDateLivraison(): ?string
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(string $dateLivraison): self
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }

    public function getSoldeLivraison(): ?float
    {
        return $this->soldeLivraison;
    }

    public function setSoldeLivraison(float $soldeLivraison): self
    {
        $this->soldeLivraison = $soldeLivraison;

        return $this;
    }

    public function getIdLivreur(): ?string
    {
        return $this->idLivreur;
    }

    public function setIdLivreur(string $idLivreur): self
    {
        $this->idLivreur = $idLivreur;

        return $this;
    }

    public function getIdColis(): ?int
    {
        return $this->idColis;
    }

    public function setIdColis(int $idColis): self
    {
        $this->idColis = $idColis;

        return $this;
    }


}
