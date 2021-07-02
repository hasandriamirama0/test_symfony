<?php

namespace App\Entity;

use App\Repository\PostalCodeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostalCodeRepository::class)
 */
class PostalCode {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Generates the magic method
     */
    public function __toString(){
        // to show the name of the postal code in the select
        return $this->label;
        // to show the id of the postal code in the select
        // return $this->id;
    }
}
