<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 * @ORM\Table(name="city", indexes={@ORM\Index(name="postal_code_id", columns={"postal_code_id"})})
 */
class City {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
     /**
     * @var \App\Entity\PostalCode
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\PostalCode")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="postal_code_id", referencedColumnName="id")
     * })
     */
    private $postal_code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
