<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChoixPresentationFacture
 *
 * @ORM\Table(name="choix_presentation_facture", uniqueConstraints={@ORM\UniqueConstraint(name="choix_pres_facture_lib_unique", columns={"libelle"})})
 * @ORM\Entity
 */
class ChoixPresentationFacture
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
     * @ORM\Column(name="libelle", type="string", length=50, nullable=false)
     */
    private $libelle;

    /**
     * @var int
     *
     * @ORM\Column(name="num", type="integer", nullable=false)
     */
    private $num;

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

    public function getNum(): ?int
    {
        return $this->num;
    }

    public function setNum(int $num): self
    {
        $this->num = $num;

        return $this;
    }


}
