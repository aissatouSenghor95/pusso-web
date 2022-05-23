<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeUsage
 *
 * @ORM\Table(name="type_usage", uniqueConstraints={@ORM\UniqueConstraint(name="type_usage_lib_unique", columns={"libelle"})})
 * @ORM\Entity(repositoryClass="App\Repository\TypeUsageRepository")
 */
class TypeUsage
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
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=25, nullable=false)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="field", type="string", length=25, nullable=true)
     */
    private $field;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function setField(?string $field): self
    {
        $this->field = $field;

        return $this;
    }


}
