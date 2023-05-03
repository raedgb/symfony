<?php

namespace App\Entity;
use App\Repository\ColisRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass= ColisRepository::class)
 * @Vich\Uploadable
 */
class Colis
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_colis", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idColis;

    /**
     * @var string
     * @assert\Length(min="8", minMessage="Introduire 8 caractère au minimum")
     * @ORM\Column(name="categorie", type="string", length=50, nullable=false)
     */
    private $categorie;

    /**
     * @var int
     * @assert\NotBlank(message="Ce chaps est obligatoire")
     * @ORM\Column(name="poids", type="integer", nullable=false)
     */
    private $poids;

    /**
     * @var string
     * @assert\NotBlank(message="Ce chaps est obligatoire")
     * @ORM\Column(name="id_user", type="string", length=50, nullable=false)
     */
    private $idUser;

    /**
     * @var string
     * @assert\NotBlank(message="Ce chaps est obligatoire")
     * @assert\Length(min="5", minMessage="Introduire 5 caractère au minimum")
     * @ORM\Column(name="adress_depart", type="string", length=80, nullable=false)
     */
    private $adressDepart;

    /**
     * @var string
     * @assert\Length(min="5", minMessage="Introduire 5 caractère au minimum")
     * @ORM\Column(name="adress_arrive", type="string", length=80, nullable=false)
     */
    private $adressArrive;

    /**
     * @var int
     * @assert\NotBlank(message="Ce chaps est obligatoire")
     * @ORM\Column(name="num_des", type="integer", nullable=false)
     */
    private $numDes;

    /**
     * @ORM\Column(type= "string", length=255)
     * @var string|null
     */
    private $image;


    /**
     * @Vich\UploadableField(mapping="colis_uploads", fileNameProperty="image")
     * @var File|null
     */
    private $imageFile;



    /**
     * @ORM\Column(type= "datetime", nullable= true)
     */
    private $updatedAt;

    public function getIdColis(): ?int
    {
        return $this->idColis;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getPoids(): ?int
    {
        return $this->poids;
    }

    public function setPoids(int $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function getIdUser(): ?string
    {
        return $this->idUser;
    }

    public function setIdUser(string $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getAdressDepart(): ?string
    {
        return $this->adressDepart;
    }

    public function setAdressDepart(string $adressDepart): self
    {
        $this->adressDepart = $adressDepart;

        return $this;
    }

    public function getAdressArrive(): ?string
    {
        return $this->adressArrive;
    }

    public function setAdressArrive(string $adressArrive): self
    {
        $this->adressArrive = $adressArrive;

        return $this;
    }

    public function getNumDes(): ?int
    {
        return $this->numDes;
    }

    public function setNumDes(int $numDes): self
    {
        $this->numDes = $numDes;

        return $this;
    }
    
    
    
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function setImageFile(?File $image = null): void
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updatedAt = new \DateTimeImmutable('now');
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }



}