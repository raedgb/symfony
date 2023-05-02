<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 *
 * @ORM\Table(name="participation")
 * @ORM\Entity
 */
class Participation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_participation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idParticipation;

    /**
     * @var int
     *
     * @ORM\Column(name="id_covoiturage", type="integer", nullable=false)
     */
    private $idCovoiturage;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_participant", type="string", length=50, nullable=false)
     */
    private $nomParticipant;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=50, nullable=false)
     */
    private $mail;

    public function getIdParticipation(): ?int
    {
        return $this->idParticipation;
    }

    public function getIdCovoiturage(): ?int
    {
        return $this->idCovoiturage;
    }
    
    public function setIdCovoiturage($idCovoiturage)
    {
        $this->idCovoiturage = $idCovoiturage;
    }

    public function getNomParticipant(): ?string
    {
        return $this->nomParticipant;
    }

    public function setNomParticipant(string $nomParticipant): self
    {
        $this->nomParticipant = $nomParticipant;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }


}
