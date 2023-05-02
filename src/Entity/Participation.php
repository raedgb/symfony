<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotNull(message: " Le nom du participant ne peut pas être nulle.")]
    private ?string $nom_participant = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotNull(message: " L'adresse email ne peut pas être nulle.")]
    #[Assert\Email(message: "L'adresse email '{{ value }}' n'est pas valide.")]
    private ?string $mail = null;

    #[ORM\ManyToOne(inversedBy: 'participation',fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Le covoiturage ne peut pas être nul.")]
    private ?Covoiturage $covoiturage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomParticipant(): ?string
    {
        return $this->nom_participant;
    }

    public function setNomParticipant(string $nom_participant): self
    {
        $this->nom_participant = $nom_participant;

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

    public function getCovoiturage(): ?Covoiturage
    {
        return $this->covoiturage;
    }

    public function setCovoiturage(?Covoiturage $covoiturage): self
    {
        
        $this->covoiturage = $covoiturage;

        return $this;
    }

   

    #[Assert\Callback]
    public function validateMaxParticipations(ExecutionContextInterface $context, $payload)
    {
        $maxParticipations = 4;
        $covoiturage = $this->getCovoiturage();
        $participationsCount = count($covoiturage->getParticipation());

        if ($participationsCount >= $maxParticipations) {
            $context->buildViolation('Le covoiturage ne peut pas avoir plus de {{ maxParticipations }} participations.')
                ->setParameter('{{ maxParticipations }}', $maxParticipations)
                ->atPath('covoiturage')
                ->addViolation();
        }
    }
}


