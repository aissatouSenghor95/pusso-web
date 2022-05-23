<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 *
 * @ORM\Table(name="service", indexes={@ORM\Index(name="service_updated_by", columns={"updated_by"}), @ORM\Index(name="service_for_organisme", columns={"organisme"}), @ORM\Index(name="service_created_by", columns={"created_by"})})
 * @ORM\Entity(repositoryClass="App\Repository\ServiceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Service
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
     * @ORM\Column(name="libelle", type="string", length=75, nullable=false)
     */
    private $libelle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_update", type="datetime", nullable=true)
     */
    private $lastUpdate;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     * })
     */
    private $createdBy;

    /**
     * @var \Organisme
     *
     * @ORM\ManyToOne(targetEntity="Organisme", inversedBy="services")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organisme", referencedColumnName="id")
     * })
     */
    private $organisme;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=10, nullable=false)
     */
    private $tag;

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="string", length=50, nullable=true)
     */
    private $template;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20, nullable=false)
     */
    private $type;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     * })
     */
    private $updatedBy;

    /**
     * @ORM\OneToMany(targetEntity="Forfait", mappedBy="service")
     */
    private $forfaits;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=true)
     */
    private $prix = 0;

    /**
     * @var float|null
     *
     * @ORM\Column(name="frais", type="float", precision=10, scale=0, nullable=true)
     */
    private $frais = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="ordre", type="integer", nullable=false)
     */
    private $ordre;

    /**
     * @var bool
     *
     * @ORM\Column(name="pdf", type="boolean", nullable=false)
     */
    private $pdf = false;

    public function __construct()
    {
        $this->createdAt = new \DateTime() ;
        $this->forfaits = new ArrayCollection();
    }


    /**
     * @ORM\PrePersist
     */
    public function auditCreation()
    {
        $this->createdAt = new \DateTime() ;
    }

    /**
     * @ORM\PreUpdate
     */
    public function auditModification(){
        $this->lastUpdate = new \DateTime() ;
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeInterface
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(?\DateTimeInterface $lastUpdate): self
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    public function getCreatedBy(): ?Utilisateur
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?Utilisateur $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getOrganisme(): ?Organisme
    {
        return $this->organisme;
    }

    public function setOrganisme(?Organisme $organisme): self
    {
        $this->organisme = $organisme;

        return $this;
    }

    public function getUpdatedBy(): ?Utilisateur
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?Utilisateur $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @return Collection|Forfait[]
     */
    public function getForfaits(): Collection
    {
        return $this->forfaits;
    }

    public function addForfait(Forfait $forfait): self
    {
        if (!$this->forfaits->contains($forfait)) {
            $this->forfaits[] = $forfait;
            $forfait->setService($this);
        }

        return $this;
    }

    public function removeForfait(Forfait $forfait): self
    {
        if ($this->forfaits->removeElement($forfait)) {
            // set the owning side to null (unless already changed)
            if ($forfait->getService() === $this) {
                $forfait->setService(null);
            }
        }

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getPdf(): ?bool
    {
        return $this->pdf;
    }

    public function setPdf(bool $pdf): self
    {
        $this->pdf = $pdf;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getFrais(): float|int|null
    {
        return $this->frais;
    }

    /**
     * @param float|null $frais
     */
    public function setFrais(float|int|null $frais): void
    {
        $this->frais = $frais;
    }
}
