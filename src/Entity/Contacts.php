<?php

namespace App\Entity;

use App\Repository\ContactsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContactsRepository::class)
 */
class Contacts
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $namecomplet;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $telephone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNamecomplet(): ?string
    {
        return $this->namecomplet;
    }

    public function setNamecomplet(string $namecomplet): self
    {
        $this->namecomplet = $namecomplet;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }
}
