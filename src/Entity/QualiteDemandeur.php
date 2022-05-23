<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QualiteDemandeur
 *
 * @ORM\Table(name="qualite_demandeur", uniqueConstraints={@ORM\UniqueConstraint(name="qualite_demandeur_lib_unique", columns={"libelle"})})
 * @ORM\Entity
 */
class QualiteDemandeur
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
     * @ORM\Column(name="libelle", type="string", length=30, nullable=false)
     */
    private $libelle;

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


}
