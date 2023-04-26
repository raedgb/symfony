<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Entity
 */

#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface , PasswordAuthenticatedUserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

     /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(
     *     message="l adresse  n est pas valide")
     * @Assert\NotBlank(message="Email is required")
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Name is required")
     */
    private $nom;

    /**
     *  @ORM\Column(type="string", length=255, nullable=false)
     *  @Assert\NotBlank(message="Last Name is required")
     * )
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=8, nullable=false)
     * @Assert\NotBlank(message="Num° is required")
     * @Assert\Length(
     * min = 8,
     * minMessage = "Votre Num° doit contenir au moins {{limit }} caracteres")
     */
    private $numtel;


    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank(message="Password is required")
     * @Assert\Length(min="8", minMessage="Password must be more then 8 caracteres")
     */
    private  $pwd ;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissc", type="date", nullable=false)
     */
    private $datenaissc;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=50, nullable=false)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=50, nullable=false)
     */
    private $sexe;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=0, nullable=false)
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $reset_token;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPwd(): ?string
    {
        return $this->pwd;
    }

    public function setPwd(string $pwd): self
    {
        $this->pwd = $pwd;

        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(int $numtel): self
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getDatenaissc():?\DateTimeInterface
    {
        return $this->datenaissc;
    }

    public function setDatenaissc(\DateTimeInterface $datenaissc): self
    {
        $this->datenaissc = $datenaissc;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
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
    
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

   

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $role = $this->role;
        // guarantee every user at least has ROLE_USER
       // $roles[] = "ROLE_USER";
    
        if ($role === 'admin') {
            $roles[] = "admin";
        } elseif ($role === 'passager') {
            $roles[] = "passager";
        } elseif ($role === 'livreur') {
            $roles[] = "livreur";
        } elseif ($role === 'conducteur') {
            $roles[] = "conducteur";
        }
    
        return array_unique($roles);
    }
    

    public function setRoles(array $roles): self
    {
        $this->role = $roles;

        return $this;
    }


 /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->pwd;
    }

    public function setPassword(string $pwd): self
    {
        $this->pwd = $pwd;

        return $this;
    }


    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }

}
