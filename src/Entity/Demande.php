<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Demande
 *
 * @ORM\Table(name="demande", uniqueConstraints={@ORM\UniqueConstraint(name="reference_unique", columns={"reference"})}, indexes={@ORM\Index(name="demande_at_status", columns={"statut"}), @ORM\Index(name="demande_by_owner", columns={"owner"}), @ORM\Index(name="demande_type_pi", columns={"type_piece_identite"}), @ORM\Index(name="demande_usage", columns={"type_usage"}), @ORM\Index(name="demande_last_rejected_by", columns={"last_rejected_by"}), @ORM\Index(name="demande_first_validated_by", columns={"first_validated_by"}), @ORM\Index(name="demande_to_organisme", columns={"organisme"}), @ORM\Index(name="demande_choix_pres_facture", columns={"presentation_facture"}), @ORM\Index(name="demande_type_local", columns={"type_local"}), @ORM\Index(name="demande_with_forfait", columns={"forfait"}), @ORM\Index(name="demande_last_validated_by", columns={"last_validated_by"}), @ORM\Index(name="demande_for_service", columns={"service"}), @ORM\Index(name="demande_type_inscription", columns={"type_inscription"}), @ORM\Index(name="demande_type_lieu", columns={"type_lieu"}), @ORM\Index(name="demande_nationalite", columns={"nationalite"}), @ORM\Index(name="demande_first_rejected_by", columns={"first_rejected_by"})})
 * @ORM\Entity(repositoryClass="App\Repository\DemandeRepository")
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks()
 */
class Demande
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
     * @var string|null
     *
     * @ORM\Column(name="reference", type="string", length=12, nullable=true)
     */
    private $reference;

    /**
     * @var string|null
     *
     * @ORM\Column(name="contact", type="string", length=20, nullable=true)
     */
    private $contact;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=true)
     */
    private $prix = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="first_validated_at", type="datetime", nullable=true)
     */
    private $firstValidatedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_validated_at", type="datetime", nullable=true)
     */
    private $lastValidatedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="first_rejected_at", type="datetime", nullable=true)
     */
    private $firstRejectedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_rejected_at", type="datetime", nullable=true)
     */
    private $lastRejectedAt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pi_file_name", type="string", length=200, nullable=true)
     */
    private $piFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="pi_file_size", type="integer", nullable=true)
     */
    private $piFileSize;

    /**
     * @var string|null
     *
     * @ORM\Column(name="co_file_name", type="string", length=200, nullable=true)
     */
    private $coFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="co_file_size", type="integer", nullable=true)
     */
    private $coFileSize;

    /**
     * @var string|null
     *
     * @ORM\Column(name="do_file_name", type="string", length=200, nullable=true)
     */
    private $doFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="do_file_size", type="integer", nullable=true)
     */
    private $doFileSize;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cont_loc_file_name", type="string", length=200, nullable=true)
     */
    private $contLocFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="cont_loc_file_size", type="integer", nullable=true)
     */
    private $contLocFileSize;

    /**
     * @var string|null
     *
     * @ORM\Column(name="status_file_name", type="string", length=200, nullable=true)
     */
    private $statusFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="status_file_size", type="integer", nullable=true)
     */
    private $statusFileSize;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cert_her_file_name", type="string", length=200, nullable=true)
     */
    private $certHerFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="cert_her_file_size", type="integer", nullable=true)
     */
    private $certHerFileSize;

    /**
     * @var string|null
     *
     * @ORM\Column(name="aut_file_name", type="string", length=200, nullable=true)
     */
    private $autFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="aut_file_size", type="integer", nullable=true)
     */
    private $autFileSize;

    /**
     * @var string|null
     *
     * @ORM\Column(name="quota_file_name", type="string", length=200, nullable=true)
     */
    private $quotaFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="quota_file_size", type="integer", nullable=true)
     */
    private $quotaFileSize;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mg_file_name", type="string", length=200, nullable=true)
     */
    private $mgFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="mg_file_size", type="integer", nullable=true)
     */
    private $mgFileSize;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ccd_file_name", type="string", length=200, nullable=true)
     */
    private $ccdFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ccd_file_size", type="integer", nullable=true)
     */
    private $ccdFileSize;

    /**
     * @var bool
     *
     * @ORM\Column(name="paiement_ok", type="boolean", nullable=false)
     */
    private $paiementOk = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_update", type="datetime", nullable=true)
     */
    private $lastUpdate;

    /**
     * @var string
     *
     * @ORM\Column(name="cin", type="string", length=30, nullable=false)
     */
    private $cin;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nature", type="string", length=150, nullable=true)
     */
    private $nature;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adresse", type="string", length=150, nullable=true)
     */
    private $adresse;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tel_domicile", type="string", length=20, nullable=true)
     */
    private $telDomicile;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tel_bureau", type="string", length=20, nullable=true)
     */
    private $telBureau;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adresse_pro", type="string", length=150, nullable=true)
     */
    private $adressePro;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lieu_branchement", type="string", length=200, nullable=true)
     */
    private $lieuBranchement;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom_voisin", type="string", length=200, nullable=true)
     */
    private $nomVoisin;

    /**
     * @var string|null
     *
     * @ORM\Column(name="police_voisin", type="string", length=200, nullable=true)
     */
    private $policeVoisin;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_point_deau", type="integer", nullable=true)
     */
    private $nbrPointDeau;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_lavabos", type="integer", nullable=true)
     */
    private $nbrLavabos;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_robinets", type="integer", nullable=true)
     */
    private $nbrRobinets;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_eviers", type="integer", nullable=true)
     */
    private $nbrEviers;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_baignoires", type="integer", nullable=true)
     */
    private $nbrBaignoires;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_urinoirs", type="integer", nullable=true)
     */
    private $nbrUrinoirs;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_lavoirs", type="integer", nullable=true)
     */
    private $nbrLavoirs;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_bidets", type="integer", nullable=true)
     */
    private $nbrBidets;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_wc", type="integer", nullable=true)
     */
    private $nbrWc;

    /**
     * @var float|null
     *
     * @ORM\Column(name="surface_jardin", type="float", precision=10, scale=0, nullable=true)
     */
    private $surfaceJardin;

    /**
     * @var float|null
     *
     * @ORM\Column(name="capacite_piscine", type="float", precision=10, scale=0, nullable=true)
     */
    private $capacitePiscine;

    /**
     * @var float|null
     *
     * @ORM\Column(name="cons_annuelle", type="float", precision=10, scale=0, nullable=true)
     */
    private $consAnnuelle;

    /**
     * @var float|null
     *
     * @ORM\Column(name="cons_mensuelle", type="float", precision=10, scale=0, nullable=true)
     */
    private $consMensuelle;

    /**
     * @var float|null
     *
     * @ORM\Column(name="debit_journalier", type="float", precision=10, scale=0, nullable=true)
     */
    private $debitJournalier;

    /**
     * @var float|null
     *
     * @ORM\Column(name="debit_horaire", type="float", precision=10, scale=0, nullable=true)
     */
    private $debitHoraire;

    /**
     * @var \Pays
     *
     * @ORM\ManyToOne(targetEntity="Pays")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="nationalite", referencedColumnName="id")
     * })
     */
    private $nationalite;

    /**
     * @var string|null
     *
     * @ORM\Column(name="profession", type="string", length=75, nullable=true)
     */
    private $profession;

    /**
     * @var string|null
     *
     * @ORM\Column(name="employeur", type="string", length=75, nullable=true)
     */
    private $employeur;

    /**
     * @var string|null
     *
     * @ORM\Column(name="proprietaire", type="string", length=75, nullable=true)
     */
    private $proprietaire;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ancienne_adresse", type="string", length=100, nullable=true)
     */
    private $ancienneAdresse;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ancienne_police", type="string", length=100, nullable=true)
     */
    private $anciennePolice;

    /**
     * @var string|null
     *
     * @ORM\Column(name="numero_registre", type="string", length=100, nullable=true)
     */
    private $numeroRegistre;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="prelevement_bancaire", type="boolean", nullable=true)
     */
    private $prelevementBancaire;

    /**
     * @var string|null
     *
     * @ORM\Column(name="banque", type="string", length=100, nullable=true)
     */
    private $banque;

    /**
     * @var string|null
     *
     * @ORM\Column(name="num_compte", type="string", length=250, nullable=true)
     */
    private $numCompte;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_foyers_lumineux", type="integer", nullable=true)
     */
    private $nbrFoyersLumineux;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_chauffe_eau", type="integer", nullable=true)
     */
    private $nbrChauffeEau;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_fers_repasser", type="integer", nullable=true)
     */
    private $nbrFersRepasser;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_frigos", type="integer", nullable=true)
     */
    private $nbrFrigos;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_ventilateurs", type="integer", nullable=true)
     */
    private $nbrVentilateurs;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_machines_laver", type="integer", nullable=true)
     */
    private $nbrMachinesLaver;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_televiseurs", type="integer", nullable=true)
     */
    private $nbrTeleviseurs;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_climatiseurs", type="integer", nullable=true)
     */
    private $nbrClimatiseurs;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_moteurs_divers", type="integer", nullable=true)
     */
    private $nbrMoteursDivers;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_congelateurs", type="integer", nullable=true)
     */
    private $nbrCongelateurs;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_wifi", type="integer", nullable=true)
     */
    private $nbrWifi;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_ordinateurs", type="integer", nullable=true)
     */
    private $nbrOrdinateurs;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_effet", type="datetime", nullable=true)
     */
    private $dateEffet;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_naissance", type="datetime", nullable=true)
     */
    private $dateNaissance;

    /**
     * @var string|null
     *
     * @ORM\Column(name="motif_resiliation", type="string", length=150, nullable=true)
     */
    private $motifResiliation;

    /**
     * @var \StatutDemande
     *
     * @ORM\ManyToOne(targetEntity="StatutDemande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="statut", referencedColumnName="id")
     * })
     */
    private $statut;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="owner", referencedColumnName="id")
     * })
     */
    private $owner;

    /**
     * @var \ChoixPresentationFacture
     *
     * @ORM\ManyToOne(targetEntity="ChoixPresentationFacture")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="presentation_facture", referencedColumnName="id")
     * })
     */
    private $presentationFacture;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="first_rejected_by", referencedColumnName="id")
     * })
     */
    private $firstRejectedBy;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="first_validated_by", referencedColumnName="id")
     * })
     */
    private $firstValidatedBy;

    /**
     * @var \Service
     *
     * @ORM\ManyToOne(targetEntity="Service")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="service", referencedColumnName="id")
     * })
     */
    private $service;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="last_rejected_by", referencedColumnName="id")
     * })
     */
    private $lastRejectedBy;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="last_validated_by", referencedColumnName="id")
     * })
     */
    private $lastValidatedBy;

    /**
     * @var \Organisme
     *
     * @ORM\ManyToOne(targetEntity="Organisme")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organisme", referencedColumnName="id")
     * })
     */
    private $organisme;

    /**
     * @var \TypeInscription
     *
     * @ORM\ManyToOne(targetEntity="TypeInscription")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_inscription", referencedColumnName="id")
     * })
     */
    private $typeInscription;

    /**
     * @var \TypeLieu
     *
     * @ORM\ManyToOne(targetEntity="TypeLieu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_lieu", referencedColumnName="id")
     * })
     */
    private $typeLieu;

    /**
     * @var \TypeLocal
     *
     * @ORM\ManyToOne(targetEntity="TypeLocal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_local", referencedColumnName="id")
     * })
     */
    private $typeLocal;

    /**
     * @var \TypePieceIdentite
     *
     * @ORM\ManyToOne(targetEntity="TypePieceIdentite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_piece_identite", referencedColumnName="id")
     * })
     */
    private $typePieceIdentite;

    /**
     * @var \TypeUsage
     *
     * @ORM\ManyToOne(targetEntity="TypeUsage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_usage", referencedColumnName="id")
     * })
     */
    private $typeUsage;

    /**
     * @var \Forfait
     *
     * @ORM\ManyToOne(targetEntity="Forfait")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="forfait", referencedColumnName="id")
     * })
     */
    private $forfait;

    /**
     * @var \TypeDemandeur
     *
     * @ORM\ManyToOne(targetEntity="TypeDemandeur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_demandeur", referencedColumnName="id")
     * })
     */
    private $typeDemandeur;

    /**
     * @var \TypeChangement
     *
     * @ORM\ManyToOne(targetEntity="TypeChangement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_changement", referencedColumnName="id")
     * })
     */
    private $typeChangement;

    /**
     * @var string|null
     *
     * @ORM\Column(name="whatsapp", type="string", length=75, nullable=true)
     */
    private $whatsapp;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mandataire", type="string", length=100, nullable=true)
     */
    private $mandataire;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tel_mandataire", type="string", length=30, nullable=true)
     */
    private $telMandataire;

    /**
     * @var \QualiteDemandeur
     *
     * @ORM\ManyToOne(targetEntity="QualiteDemandeur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="qualite_demandeur", referencedColumnName="id")
     * })
     */
    private $qualiteDemandeur;

    /**
     * @var \TypeBranchement
     *
     * @ORM\ManyToOne(targetEntity="TypeBranchement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_demandeur", referencedColumnName="id")
     * })
     */
    private $typeBranchement;

    /**
     * @var \TypeConstruction
     *
     * @ORM\ManyToOne(targetEntity="TypeConstruction")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_construction", referencedColumnName="id")
     * })
     */
    private $typeConstruction;

    /**
     * @var string|null
     *
     * @ORM\Column(name="numero_police", type="string", length=50, nullable=true)
     */
    private $numeroPolice;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=true)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prenom", type="string", length=75, nullable=true)
     */
    private $prenom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="dm_file_name", type="string", length=200, nullable=true)
     */
    private $dmFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="dm_file_size", type="integer", nullable=true)
     */
    private $dmFileSize;

    /**
     * @var string|null
     *
     * @ORM\Column(name="dl_file_name", type="string", length=200, nullable=true)
     */
    private $dlFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="dl_file_size", type="integer", nullable=true)
     */
    private $dlFileSize;

    /**
     * @var string|null
     *
     * @ORM\Column(name="js_file_name", type="string", length=200, nullable=true)
     */
    private $jsFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="js_file_size", type="integer", nullable=true)
     */
    private $jsFileSize;

    /**
     * @var string|null
     *
     * @ORM\Column(name="jm_file_name", type="string", length=200, nullable=true)
     */
    private $jmFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="jm_file_size", type="integer", nullable=true)
     */
    private $jmFileSize;

    /**
     * @var string|null
     *
     * @ORM\Column(name="jp_file_name", type="string", length=200, nullable=true)
     */
    private $jpFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="jp_file_size", type="integer", nullable=true)
     */
    private $jpFileSize;

    /**
     * @var string|null
     *
     * @ORM\Column(name="jc_file_name", type="string", length=200, nullable=true)
     */
    private $jcFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="jc_file_size", type="integer", nullable=true)
     */
    private $jcFileSize;

    /**
     * @var string|null
     *
     * @ORM\Column(name="jf_file_name", type="string", length=200, nullable=true)
     */
    private $jfFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="jf_file_size", type="integer", nullable=true)
     */
    private $jfFileSize;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fr_file_name", type="string", length=200, nullable=true)
     */
    private $frFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="fr_file_size", type="integer", nullable=true)
     */
    private $frFileSize;

    /**
     * @var string|null
     *
     * @ORM\Column(name="devis_file_name", type="string", length=200, nullable=true)
     */
    private $devisFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="devis_file_size", type="integer", nullable=true)
     */
    private $devisFileSize;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pf_file_name", type="string", length=200, nullable=true)
     */
    private $pfFileName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="pf_file_size", type="integer", nullable=true)
     */
    private $pfFileSize;

    /**
     * @var double
     *
     * @ORM\Column(name="lon", type="float", nullable=true)
     */
    private $lon;

    /**
     * @var double
     *
     * @ORM\Column(name="lat", type="float", nullable=true)
     */
    private $lat;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbr_cptr_div", type="integer", nullable=true)
     */
    private $nbrCptrDiv = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="accessibilite_compteurs", type="boolean", nullable=true)
     */
    private $accessibiliteCompteurs = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="pose_robinet", type="boolean", nullable=true)
     */
    private $poseRobinet = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="respect_distance", type="boolean", nullable=true)
     */
    private $respectDistance = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="raccordement", type="boolean", nullable=true)
     */
    private $raccordement = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="pose_clapet", type="boolean", nullable=true)
     */
    private $poseClapet = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="installation_horizontale", type="boolean", nullable=true)
     */
    private $installationHorizontale = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="numerotation", type="boolean", nullable=true)
     */
    private $numeration = false;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="piece_identites", fileNameProperty="piFileName", size="piFilesize")
     */
    public $piFile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="contrats", fileNameProperty="coFileName", size="coFilesize")
     */
    public $coFile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="domiciles", fileNameProperty="doFileName", size="doFilesize")
     */
    public $doFile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="contrats_location", fileNameProperty="contLocFileName", size="contLocFilesize")
     */
    public $contLocFile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="certificats_heredite", fileNameProperty="certHerFileName", size="certHerFileSize")
     */
    public $certHerFile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="statut_commerce", fileNameProperty="statusFileName", size="statusFilesize")
     */
    public $statusCommerceFile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="demandes_manuscrites", fileNameProperty="dmFileName", size="dmFilesize")
     */
    public $demandeManuscriteFile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="plans_situation", fileNameProperty="dlFileName", size="dlFilesize")
     */
    public $planSituationFile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="jeu_situation", fileNameProperty="jsFileName", size="jsFilesize")
     */
    public $jeuSituationFile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="jeu_masse", fileNameProperty="jmFileName", size="jmFilesize")
     */
    public $jeuMasseFile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="jeu_plan", fileNameProperty="jpFileName", size="jpFilesize")
     */
    public $jeuPlanFile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="jeu_coupe", fileNameProperty="jcFileName", size="jcFilesize")
     */
    public $jeuCoupeFile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="jeu_facade", fileNameProperty="jfFileName", size="jfFilesize")
     */
    public $jeuFacadeFile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="fiche_renseignement", fileNameProperty="frFileName", size="frFilesize")
     */
    public $ficheRenseignementFile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="devis_projet", fileNameProperty="devisFileName", size="devisFilesize")
     */
    public $devisProjetFile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="plans_fosses_septiques", fileNameProperty="pfFileName", size="pfFilesize")
     */
    public $planFosseFile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="autorisation_proprietaire", fileNameProperty="autFileName", size="autFilesize")
     */
    public $autFile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="quota_accorde", fileNameProperty="quotaFileName", size="quotaFilesize")
     */
    public $quotaFile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="mandat_gerance", fileNameProperty="mgFileName", size="mgFilesize")
     */
    public $mgFile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="convention_exploitation", fileNameProperty="ccdFileName", size="ccdFilesize")
     */
    public $ccdFile;

    /**
     * @ORM\OneToMany(targetEntity="Commentaire", mappedBy="demande")
     */
    private $commentaires;

    public function __construct()
    {
        $this->createdAt = new \DateTime() ;
        $this->commentaires = new ArrayCollection() ;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $piFile
     */
    public function setPiFile(?File $piFile = null): void
    {
        $this->piFile = $piFile;

        if(null !== $piFile){
            $this->lastUpdated = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getPiFile(): ?File
    {
        return $this->piFile;
    }


    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $coFile
     */
    public function setCoFile(?File $coFile = null): void
    {
        $this->coFile = $coFile;

        if(null !== $coFile){
            $this->lastUpdate = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getCoFile(): ?File
    {
        return $this->coFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $doFile
     */
    public function setDoFile(?File $doFile = null): void
    {
        $this->doFile = $doFile;

        if(null !== $doFile){
            $this->lastUpdate = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getDoFile(): ?File
    {
        return $this->doFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $autFile
     */
    public function setAutFile(?File $autFile = null): void
    {
        $this->autFile = $autFile;

        if(null !== $autFile){
            $this->lastUpdate = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getAutFile(): ?File
    {
        return $this->autFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $quotaFile
     */
    public function setQuotaFile(?File $quotaFile = null): void
    {
        $this->quotaFile = $quotaFile;

        if(null !== $quotaFile){
            $this->lastUpdate = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getQuotaFile(): ?File
    {
        return $this->quotaFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $mgFile
     */
    public function setMgFile(?File $mgFile = null): void
    {
        $this->mgFile = $mgFile;

        if(null !== $mgFile){
            $this->lastUpdate = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getMgFile(): ?File
    {
        return $this->mgFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $ccdFile
     */
    public function setCcdFile(?File $ccdFile = null): void
    {
        $this->ccdFile = $ccdFile;

        if(null !== $ccdFile){
            $this->lastUpdate = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getCcdFile(): ?File
    {
        return $this->ccdFile ;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $contLocFile
     */
    public function setContLocFile(?File $contLocFile = null): void
    {
        $this->contLocFile = $contLocFile;

        if(null !== $contLocFile){
            $this->lastUpdate = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getContLocFile(): ?File
    {
        return $this->contLocFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $statusCommerceFile
     */
    public function setStatusCommerceFile(?File $statusCommerceFile = null): void
    {
        $this->statusCommerceFile = $statusCommerceFile;

        if(null !== $statusCommerceFile){
            $this->lastUpdate = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getStatusCommerceFile(): ?File
    {
        return $this->statusCommerceFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $certHerFile
     */
    public function setCertHerFile(?File $certHerFile = null): void
    {
        $this->certHerFile = $certHerFile;

        if(null !== $certHerFile){
            $this->lastUpdate = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getCertHerFile(): ?File
    {
        return $this->certHerFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $demandeManuscriteFile
     */
    public function setDemandeManuscriteFile(?File $demandeManuscriteFile = null): void
    {
        $this->demandeManuscriteFile = $demandeManuscriteFile;

        if(null !== $demandeManuscriteFile){
            $this->lastUpdated = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getDemandeManuscriteFile(): ?File
    {
        return $this->demandeManuscriteFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $planSituationFile
     */
    public function setPlanSituationFile(?File $planSituationFile = null): void
    {
        $this->planSituationFile = $planSituationFile;

        if(null !== $planSituationFile){
            $this->lastUpdated = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getPlanSituationFile(): ?File
    {
        return $this->planSituationFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $jeuSituationFile
     */
    public function setJeuSituationFile(?File $jeuSituationFile = null): void
    {
        $this->jeuSituationFile = $jeuSituationFile;

        if(null !== $jeuSituationFile){
            $this->lastUpdated = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getJeuSituationFile(): ?File
    {
        return $this->jeuSituationFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $jeuPlanFile
     */
    public function setJeuPlanFile(?File $jeuPlanFile = null): void
    {
        $this->jeuPlanFile = $jeuPlanFile;

        if(null !== $jeuPlanFile){
            $this->lastUpdated = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getJeuPlanFile(): ?File
    {
        return $this->jeuPlanFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $jeuMasseFile
     */
    public function setJeuMasseFile(?File $jeuMasseFile = null): void
    {
        $this->jeuMasseFile = $jeuMasseFile;

        if(null !== $jeuMasseFile){
            $this->lastUpdated = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getMassePlanFile(): ?File
    {
        return $this->jeuMasseFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $jeuCoupeFile
     */
    public function setJeuCoupeFile(?File $jeuCoupeFile = null): void
    {
        $this->jeuCoupeFile = $jeuCoupeFile;

        if(null !== $jeuCoupeFile){
            $this->lastUpdated = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getJeuCoupeFile(): ?File
    {
        return $this->jeuCoupeFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $jeuFacadeFile
     */
    public function setJeuFacadeFile(?File $jeuFacadeFile = null): void
    {
        $this->jeuFacadeFile = $jeuFacadeFile;

        if(null !== $jeuFacadeFile){
            $this->lastUpdated = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getJeuFacadeFile(): ?File
    {
        return $this->jeuFacadeFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $ficheRenseignementFile
     */
    public function setFicheRenseignementFile(?File $ficheRenseignementFile = null): void
    {
        $this->ficheRenseignementFile = $ficheRenseignementFile;

        if(null !== $ficheRenseignementFile){
            $this->lastUpdated = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getFicheRenseignementFile(): ?File
    {
        return $this->jeuCoupeFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $devisProjetFile
     */
    public function setDevisProjetFile(?File $devisProjetFile = null): void
    {
        $this->devisProjetFile = $devisProjetFile;

        if(null !== $devisProjetFile){
            $this->lastUpdated = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getDevisProjetFile(): ?File
    {
        return $this->devisProjetFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $planFosseFile
     */
    public function setPlanFosseFile(?File $planFosseFile = null): void
    {
        $this->planFosseFile = $planFosseFile;

        if(null !== $planFosseFile){
            $this->lastUpdated = new \DateTime() ;
        }
    }

    /**
     * @return null|File
     */
    public function getPlanFosseFile(): ?File
    {
        return $this->planFosseFile;
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

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setDemande($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            if ($commentaire->getDemande() === $this) {
                $commentaire->setDemande(null);
            }
        }
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): self
    {
        $this->contact = $contact;

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

    public function getFirstValidatedAt(): ?\DateTimeInterface
    {
        return $this->firstValidatedAt;
    }

    public function setFirstValidatedAt(?\DateTimeInterface $firstValidatedAt): self
    {
        $this->firstValidatedAt = $firstValidatedAt;

        return $this;
    }

    public function getLastValidatedAt(): ?\DateTimeInterface
    {
        return $this->lastValidatedAt;
    }

    public function setLastValidatedAt(?\DateTimeInterface $lastValidatedAt): self
    {
        $this->lastValidatedAt = $lastValidatedAt;

        return $this;
    }

    public function getFirstRejectedAt(): ?\DateTimeInterface
    {
        return $this->firstRejectedAt;
    }

    public function setFirstRejectedAt(?\DateTimeInterface $firstRejectedAt): self
    {
        $this->firstRejectedAt = $firstRejectedAt;

        return $this;
    }

    public function getLastRejectedAt(): ?\DateTimeInterface
    {
        return $this->lastRejectedAt;
    }

    public function setLastRejectedAt(?\DateTimeInterface $lastRejectedAt): self
    {
        $this->lastRejectedAt = $lastRejectedAt;

        return $this;
    }

    public function getPiFileName(): ?string
    {
        return $this->piFileName;
    }

    public function setPiFileName(?string $piFileName): self
    {
        $this->piFileName = $piFileName;

        return $this;
    }

    public function getPiFileSize(): ?int
    {
        return $this->piFileSize;
    }

    public function setPiFileSize(?int $piFileSize): self
    {
        $this->piFileSize = $piFileSize;

        return $this;
    }

    public function getCoFileName(): ?string
    {
        return $this->coFileName;
    }

    public function setCoFileName(?string $coFileName): self
    {
        $this->coFileName = $coFileName;

        return $this;
    }

    public function getCoFileSize(): ?int
    {
        return $this->coFileSize;
    }

    public function setCoFileSize(?int $coFileSize): self
    {
        $this->coFileSize = $coFileSize;

        return $this;
    }

    public function getPaiementOk(): ?bool
    {
        return $this->paiementOk;
    }

    public function setPaiementOk(bool $paiementOk): self
    {
        $this->paiementOk = $paiementOk;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
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

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getNature(): ?string
    {
        return $this->nature;
    }

    public function setNature(?string $nature): self
    {
        $this->nature = $nature;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelDomicile(): ?string
    {
        return $this->telDomicile;
    }

    public function setTelDomicile(?string $telDomicile): self
    {
        $this->telDomicile = $telDomicile;

        return $this;
    }

    public function getTelBureau(): ?string
    {
        return $this->telBureau;
    }

    public function setTelBureau(?string $telBureau): self
    {
        $this->telBureau = $telBureau;

        return $this;
    }

    public function getAdressePro(): ?string
    {
        return $this->adressePro;
    }

    public function setAdressePro(?string $adressePro): self
    {
        $this->adressePro = $adressePro;

        return $this;
    }

    public function getLieuBranchement(): ?string
    {
        return $this->lieuBranchement;
    }

    public function setLieuBranchement(?string $lieuBranchement): self
    {
        $this->lieuBranchement = $lieuBranchement;

        return $this;
    }

    public function getNomVoisin(): ?string
    {
        return $this->nomVoisin;
    }

    public function setNomVoisin(?string $nomVoisin): self
    {
        $this->nomVoisin = $nomVoisin;

        return $this;
    }

    public function getPoliceVoisin(): ?string
    {
        return $this->policeVoisin;
    }

    public function setPoliceVoisin(?string $policeVoisin): self
    {
        $this->policeVoisin = $policeVoisin;

        return $this;
    }

    public function getNbrPointDeau(): ?int
    {
        return $this->nbrPointDeau;
    }

    public function setNbrPointDeau(?int $nbrPointDeau): self
    {
        $this->nbrPointDeau = $nbrPointDeau;

        return $this;
    }

    public function getNbrLavabos(): ?int
    {
        return $this->nbrLavabos;
    }

    public function setNbrLavabos(?int $nbrLavabos): self
    {
        $this->nbrLavabos = $nbrLavabos;

        return $this;
    }

    public function getNbrRobinets(): ?int
    {
        return $this->nbrRobinets;
    }

    public function setNbrRobinets(?int $nbrRobinets): self
    {
        $this->nbrRobinets = $nbrRobinets;

        return $this;
    }

    public function getNbrEviers(): ?int
    {
        return $this->nbrEviers;
    }

    public function setNbrEviers(?int $nbrEviers): self
    {
        $this->nbrEviers = $nbrEviers;

        return $this;
    }

    public function getNbrBaignoires(): ?int
    {
        return $this->nbrBaignoires;
    }

    public function setNbrBaignoires(?int $nbrBaignoires): self
    {
        $this->nbrBaignoires = $nbrBaignoires;

        return $this;
    }

    public function getNbrUrinoirs(): ?int
    {
        return $this->nbrUrinoirs;
    }

    public function setNbrUrinoirs(?int $nbrUrinoirs): self
    {
        $this->nbrUrinoirs = $nbrUrinoirs;

        return $this;
    }

    public function getNbrLavoirs(): ?int
    {
        return $this->nbrLavoirs;
    }

    public function setNbrLavoirs(?int $nbrLavoirs): self
    {
        $this->nbrLavoirs = $nbrLavoirs;

        return $this;
    }

    public function getNbrBidets(): ?int
    {
        return $this->nbrBidets;
    }

    public function setNbrBidets(?int $nbrBidets): self
    {
        $this->nbrBidets = $nbrBidets;

        return $this;
    }

    public function getNbrWc(): ?int
    {
        return $this->nbrWc;
    }

    public function setNbrWc(?int $nbrWc): self
    {
        $this->nbrWc = $nbrWc;

        return $this;
    }

    public function getSurfaceJardin(): ?float
    {
        return $this->surfaceJardin;
    }

    public function setSurfaceJardin(?float $surfaceJardin): self
    {
        $this->surfaceJardin = $surfaceJardin;

        return $this;
    }

    public function getCapacitePiscine(): ?float
    {
        return $this->capacitePiscine;
    }

    public function setCapacitePiscine(?float $capacitePiscine): self
    {
        $this->capacitePiscine = $capacitePiscine;

        return $this;
    }

    public function getConsAnnuelle(): ?float
    {
        return $this->consAnnuelle;
    }

    public function setConsAnnuelle(?float $consAnnuelle): self
    {
        $this->consAnnuelle = $consAnnuelle;

        return $this;
    }

    public function getConsMensuelle(): ?float
    {
        return $this->consMensuelle;
    }

    public function setConsMensuelle(?float $consMensuelle): self
    {
        $this->consMensuelle = $consMensuelle;

        return $this;
    }

    public function getDebitJournalier(): ?float
    {
        return $this->debitJournalier;
    }

    public function setDebitJournalier(?float $debitJournalier): self
    {
        $this->debitJournalier = $debitJournalier;

        return $this;
    }

    public function getDebitHoraire(): ?float
    {
        return $this->debitHoraire;
    }

    public function setDebitHoraire(?float $debitHoraire): self
    {
        $this->debitHoraire = $debitHoraire;

        return $this;
    }

    public function getStatut(): ?StatutDemande
    {
        return $this->statut;
    }

    public function setStatut(?StatutDemande $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getOwner(): ?Utilisateur
    {
        return $this->owner;
    }

    public function setOwner(?Utilisateur $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getFirstRejectedBy(): ?Utilisateur
    {
        return $this->firstRejectedBy;
    }

    public function setFirstRejectedBy(?Utilisateur $firstRejectedBy): self
    {
        $this->firstRejectedBy = $firstRejectedBy;

        return $this;
    }

    public function getFirstValidatedBy(): ?Utilisateur
    {
        return $this->firstValidatedBy;
    }

    public function setFirstValidatedBy(?Utilisateur $firstValidatedBy): self
    {
        $this->firstValidatedBy = $firstValidatedBy;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getLastRejectedBy(): ?Utilisateur
    {
        return $this->lastRejectedBy;
    }

    public function setLastRejectedBy(?Utilisateur $lastRejectedBy): self
    {
        $this->lastRejectedBy = $lastRejectedBy;

        return $this;
    }

    public function getLastValidatedBy(): ?Utilisateur
    {
        return $this->lastValidatedBy;
    }

    public function setLastValidatedBy(?Utilisateur $lastValidatedBy): self
    {
        $this->lastValidatedBy = $lastValidatedBy;

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

    public function getTypeLieu(): ?TypeLieu
    {
        return $this->typeLieu;
    }

    public function setTypeLieu(?TypeLieu $typeLieu): self
    {
        $this->typeLieu = $typeLieu;

        return $this;
    }

    public function getTypeUsage(): ?TypeUsage
    {
        return $this->typeUsage;
    }

    public function setTypeUsage(?TypeUsage $typeUsage): self
    {
        $this->typeUsage = $typeUsage;

        return $this;
    }

    public function getForfait(): ?Forfait
    {
        return $this->forfait;
    }

    public function setForfait(?Forfait $forfait): self
    {
        $this->forfait = $forfait;

        return $this;
    }

    public function getDoFileName(): ?string
    {
        return $this->doFileName;
    }

    public function setDoFileName(?string $doFileName): self
    {
        $this->doFileName = $doFileName;

        return $this;
    }

    public function getDoFileSize(): ?int
    {
        return $this->doFileSize;
    }

    public function setDoFileSize(?int $doFileSize): self
    {
        $this->doFileSize = $doFileSize;

        return $this;
    }

    public function getContLocFileName(): ?string
    {
        return $this->contLocFileName;
    }

    public function setContLocFileName(?string $contLocFileName): self
    {
        $this->contLocFileName = $contLocFileName;

        return $this;
    }

    public function getContLocFileSize(): ?int
    {
        return $this->contLocFileSize;
    }

    public function setContLocFileSize(?int $contLocFileSize): self
    {
        $this->contLocFileSize = $contLocFileSize;

        return $this;
    }

    public function getStatusFileName(): ?string
    {
        return $this->statusFileName;
    }

    public function setStatusFileName(?string $statusFileName): self
    {
        $this->statusFileName = $statusFileName;

        return $this;
    }

    public function getStatusFileSize(): ?int
    {
        return $this->statusFileSize;
    }

    public function setStatusFileSize(?int $statusFileSize): self
    {
        $this->statusFileSize = $statusFileSize;

        return $this;
    }

    public function getCertHerFileName(): ?string
    {
        return $this->certHerFileName;
    }

    public function setCertHerFileName(?string $certHerFileName): self
    {
        $this->certHerFileName = $certHerFileName;

        return $this;
    }

    public function getCertHerFileSize(): ?int
    {
        return $this->certHerFileSize;
    }

    public function setCertHerFileSize(?int $certHerFileSize): self
    {
        $this->certHerFileSize = $certHerFileSize;

        return $this;
    }

    public function getNationalite(): ?Pays
    {
        return $this->nationalite;
    }

    public function setNationalite(?Pays $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function getEmployeur(): ?string
    {
        return $this->employeur;
    }

    public function setEmployeur(?string $employeur): self
    {
        $this->employeur = $employeur;

        return $this;
    }

    public function getProprietaire(): ?string
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?string $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    public function getAncienneAdresse(): ?string
    {
        return $this->ancienneAdresse;
    }

    public function setAncienneAdresse(?string $ancienneAdresse): self
    {
        $this->ancienneAdresse = $ancienneAdresse;

        return $this;
    }

    public function getAnciennePolice(): ?string
    {
        return $this->anciennePolice;
    }

    public function setAnciennePolice(?string $anciennePolice): self
    {
        $this->anciennePolice = $anciennePolice;

        return $this;
    }

    public function getNumeroRegistre(): ?string
    {
        return $this->numeroRegistre;
    }

    public function setNumeroRegistre(?string $numeroRegistre): self
    {
        $this->numeroRegistre = $numeroRegistre;

        return $this;
    }

    public function getPrelevementBancaire(): ?bool
    {
        return $this->prelevementBancaire;
    }

    public function setPrelevementBancaire(?bool $prelevementBancaire): self
    {
        $this->prelevementBancaire = $prelevementBancaire;

        return $this;
    }

    public function getBanque(): ?string
    {
        return $this->banque;
    }

    public function setBanque(?string $banque): self
    {
        $this->banque = $banque;

        return $this;
    }

    public function getNumCompte(): ?string
    {
        return $this->numCompte;
    }

    public function setNumCompte(?string $numCompte): self
    {
        $this->numCompte = $numCompte;

        return $this;
    }

    public function getNbrFoyersLumineux(): ?int
    {
        return $this->nbrFoyersLumineux;
    }

    public function setNbrFoyersLumineux(?int $nbrFoyersLumineux): self
    {
        $this->nbrFoyersLumineux = $nbrFoyersLumineux;

        return $this;
    }

    public function getNbrChauffeEau(): ?int
    {
        return $this->nbrChauffeEau;
    }

    public function setNbrChauffeEau(?int $nbrChauffeEau): self
    {
        $this->nbrChauffeEau = $nbrChauffeEau;

        return $this;
    }

    public function getNbrFersRepasser(): ?int
    {
        return $this->nbrFersRepasser;
    }

    public function setNbrFersRepasser(?int $nbrFersRepasser): self
    {
        $this->nbrFersRepasser = $nbrFersRepasser;

        return $this;
    }

    public function getNbrFrigos(): ?int
    {
        return $this->nbrFrigos;
    }

    public function setNbrFrigos(?int $nbrFrigos): self
    {
        $this->nbrFrigos = $nbrFrigos;

        return $this;
    }

    public function getNbrVentilateurs(): ?int
    {
        return $this->nbrVentilateurs;
    }

    public function setNbrVentilateurs(?int $nbrVentilateurs): self
    {
        $this->nbrVentilateurs = $nbrVentilateurs;

        return $this;
    }

    public function getNbrMachinesLaver(): ?int
    {
        return $this->nbrMachinesLaver;
    }

    public function setNbrMachinesLaver(?int $nbrMachinesLaver): self
    {
        $this->nbrMachinesLaver = $nbrMachinesLaver;

        return $this;
    }

    public function getNbrTeleviseurs(): ?int
    {
        return $this->nbrTeleviseurs;
    }

    public function setNbrTeleviseurs(?int $nbrTeleviseurs): self
    {
        $this->nbrTeleviseurs = $nbrTeleviseurs;

        return $this;
    }

    public function getNbrClimatiseurs(): ?int
    {
        return $this->nbrClimatiseurs;
    }

    public function setNbrClimatiseurs(?int $nbrClimatiseurs): self
    {
        $this->nbrClimatiseurs = $nbrClimatiseurs;

        return $this;
    }

    public function getNbrMoteursDivers(): ?int
    {
        return $this->nbrMoteursDivers;
    }

    public function setNbrMoteursDivers(?int $nbrMoteursDivers): self
    {
        $this->nbrMoteursDivers = $nbrMoteursDivers;

        return $this;
    }

    public function getNbrCongelateurs(): ?int
    {
        return $this->nbrCongelateurs;
    }

    public function setNbrCongelateurs(?int $nbrCongelateurs): self
    {
        $this->nbrCongelateurs = $nbrCongelateurs;

        return $this;
    }

    public function getNbrWifi(): ?int
    {
        return $this->nbrWifi;
    }

    public function setNbrWifi(?int $nbrWifi): self
    {
        $this->nbrWifi = $nbrWifi;

        return $this;
    }

    public function getNbrOrdinateurs(): ?int
    {
        return $this->nbrOrdinateurs;
    }

    public function setNbrOrdinateurs(?int $nbrOrdinateurs): self
    {
        $this->nbrOrdinateurs = $nbrOrdinateurs;

        return $this;
    }

    public function getDateEffet(): ?\DateTimeInterface
    {
        return $this->dateEffet;
    }

    public function setDateEffet(?\DateTimeInterface $dateEffet): self
    {
        $this->dateEffet = $dateEffet;

        return $this;
    }

    public function getMotifResiliation(): ?string
    {
        return $this->motifResiliation;
    }

    public function setMotifResiliation(?string $motifResiliation): self
    {
        $this->motifResiliation = $motifResiliation;

        return $this;
    }

    public function getPresentationFacture(): ?ChoixPresentationFacture
    {
        return $this->presentationFacture;
    }

    public function setPresentationFacture(?ChoixPresentationFacture $presentationFacture): self
    {
        $this->presentationFacture = $presentationFacture;

        return $this;
    }

    public function getTypeInscription(): ?TypeInscription
    {
        return $this->typeInscription;
    }

    public function setTypeInscription(?TypeInscription $typeInscription): self
    {
        $this->typeInscription = $typeInscription;

        return $this;
    }

    public function getTypeLocal(): ?TypeLocal
    {
        return $this->typeLocal;
    }

    public function setTypeLocal(?TypeLocal $typeLocal): self
    {
        $this->typeLocal = $typeLocal;

        return $this;
    }

    public function getTypePieceIdentite(): ?TypePieceIdentite
    {
        return $this->typePieceIdentite;
    }

    public function setTypePieceIdentite(?TypePieceIdentite $typePieceIdentite): self
    {
        $this->typePieceIdentite = $typePieceIdentite;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getWhatsapp(): ?string
    {
        return $this->whatsapp;
    }

    public function setWhatsapp(?string $whatsapp): self
    {
        $this->whatsapp = $whatsapp;

        return $this;
    }

    public function getMandataire(): ?string
    {
        return $this->mandataire;
    }

    public function setMandataire(?string $mandataire): self
    {
        $this->mandataire = $mandataire;

        return $this;
    }

    public function getTelMandataire(): ?string
    {
        return $this->telMandataire;
    }

    public function setTelMandataire(?string $telMandataire): self
    {
        $this->telMandataire = $telMandataire;

        return $this;
    }

    public function getTypeDemandeur(): ?TypeDemandeur
    {
        return $this->typeDemandeur;
    }

    public function setTypeDemandeur(?TypeDemandeur $typeDemandeur): self
    {
        $this->typeDemandeur = $typeDemandeur;

        return $this;
    }

    public function getQualiteDemandeur(): ?QualiteDemandeur
    {
        return $this->qualiteDemandeur;
    }

    public function setQualiteDemandeur(?QualiteDemandeur $qualiteDemandeur): self
    {
        $this->qualiteDemandeur = $qualiteDemandeur;

        return $this;
    }

    public function getTypeBranchement(): ?TypeBranchement
    {
        return $this->typeBranchement;
    }

    public function setTypeBranchement(?TypeBranchement $typeBranchement): self
    {
        $this->typeBranchement = $typeBranchement;

        return $this;
    }

    public function getTypeConstruction(): ?TypeConstruction
    {
        return $this->typeConstruction;
    }

    public function setTypeConstruction(?TypeConstruction $typeConstruction): self
    {
        $this->typeConstruction = $typeConstruction;

        return $this;
    }

    public function getNumeroPolice(): ?string
    {
        return $this->numeroPolice;
    }

    public function setNumeroPolice(?string $numeroPolice): self
    {
        $this->numeroPolice = $numeroPolice;

        return $this;
    }

    public function getTypeChangement(): ?TypeChangement
    {
        return $this->typeChangement;
    }

    public function setTypeChangement(?TypeChangement $typeChangement): self
    {
        $this->typeChangement = $typeChangement;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDmFileName(): ?string
    {
        return $this->dmFileName;
    }

    public function setDmFileName(?string $dmFileName): self
    {
        $this->dmFileName = $dmFileName;

        return $this;
    }

    public function getDmFileSize(): ?int
    {
        return $this->dmFileSize;
    }

    public function setDmFileSize(?int $dmFileSize): self
    {
        $this->dmFileSize = $dmFileSize;

        return $this;
    }

    public function getDlFileName(): ?string
    {
        return $this->dlFileName;
    }

    public function setDlFileName(?string $dlFileName): self
    {
        $this->dlFileName = $dlFileName;

        return $this;
    }

    public function getDlFileSize(): ?int
    {
        return $this->dlFileSize;
    }

    public function setDlFileSize(?int $dlFileSize): self
    {
        $this->dlFileSize = $dlFileSize;

        return $this;
    }

    public function getJsFileName(): ?string
    {
        return $this->jsFileName;
    }

    public function setJsFileName(?string $jsFileName): self
    {
        $this->jsFileName = $jsFileName;

        return $this;
    }

    public function getJsFileSize(): ?int
    {
        return $this->jsFileSize;
    }

    public function setJsFileSize(?int $jsFileSize): self
    {
        $this->jsFileSize = $jsFileSize;

        return $this;
    }

    public function getJmFileName(): ?string
    {
        return $this->jmFileName;
    }

    public function setJmFileName(?string $jmFileName): self
    {
        $this->jmFileName = $jmFileName;

        return $this;
    }

    public function getJmFileSize(): ?int
    {
        return $this->jmFileSize;
    }

    public function setJmFileSize(?int $jmFileSize): self
    {
        $this->jmFileSize = $jmFileSize;

        return $this;
    }

    public function getJpFileName(): ?string
    {
        return $this->jpFileName;
    }

    public function setJpFileName(?string $jpFileName): self
    {
        $this->jpFileName = $jpFileName;

        return $this;
    }

    public function getJpFileSize(): ?int
    {
        return $this->jpFileSize;
    }

    public function setJpFileSize(?int $jpFileSize): self
    {
        $this->jpFileSize = $jpFileSize;

        return $this;
    }

    public function getJcFileName(): ?string
    {
        return $this->jcFileName;
    }

    public function setJcFileName(?string $jcFileName): self
    {
        $this->jcFileName = $jcFileName;

        return $this;
    }

    public function getJcFileSize(): ?int
    {
        return $this->jcFileSize;
    }

    public function setJcFileSize(?int $jcFileSize): self
    {
        $this->jcFileSize = $jcFileSize;

        return $this;
    }

    public function getJfFileName(): ?string
    {
        return $this->jfFileName;
    }

    public function setJfFileName(?string $jfFileName): self
    {
        $this->jfFileName = $jfFileName;

        return $this;
    }

    public function getJfFileSize(): ?int
    {
        return $this->jfFileSize;
    }

    public function setJfFileSize(?int $jfFileSize): self
    {
        $this->jfFileSize = $jfFileSize;

        return $this;
    }

    public function getFrFileName(): ?string
    {
        return $this->frFileName;
    }

    public function setFrFileName(?string $frFileName): self
    {
        $this->frFileName = $frFileName;

        return $this;
    }

    public function getFrFileSize(): ?int
    {
        return $this->frFileSize;
    }

    public function setFrFileSize(?int $frFileSize): self
    {
        $this->frFileSize = $frFileSize;

        return $this;
    }

    public function getDevisFileName(): ?string
    {
        return $this->devisFileName;
    }

    public function setDevisFileName(?string $devisFileName): self
    {
        $this->devisFileName = $devisFileName;

        return $this;
    }

    public function getDevisFileSize(): ?int
    {
        return $this->devisFileSize;
    }

    public function setDevisFileSize(?int $devisFileSize): self
    {
        $this->devisFileSize = $devisFileSize;

        return $this;
    }

    public function getPfFileName(): ?string
    {
        return $this->pfFileName;
    }

    public function setPfFileName(?string $pfFileName): self
    {
        $this->pfFileName = $pfFileName;

        return $this;
    }

    public function getPfFileSize(): ?int
    {
        return $this->pfFileSize;
    }

    public function setPfFileSize(?int $pfFileSize): self
    {
        $this->pfFileSize = $pfFileSize;

        return $this;
    }

    public function getAutFileName(): ?string
    {
        return $this->autFileName;
    }

    public function setAutFileName(?string $autFileName): self
    {
        $this->autFileName = $autFileName;

        return $this;
    }

    public function getAutFileSize(): ?int
    {
        return $this->autFileSize;
    }

    public function setAutFileSize(?int $autFileSize): self
    {
        $this->autFileSize = $autFileSize;

        return $this;
    }

    public function getQuotaFileName(): ?string
    {
        return $this->quotaFileName;
    }

    public function setQuotaFileName(?string $quotaFileName): self
    {
        $this->quotaFileName = $quotaFileName;

        return $this;
    }

    public function getQuotaFileSize(): ?int
    {
        return $this->quotaFileSize;
    }

    public function setQuotaFileSize(?int $quotaFileSize): self
    {
        $this->quotaFileSize = $quotaFileSize;

        return $this;
    }

    public function getMgFileName(): ?string
    {
        return $this->mgFileName;
    }

    public function setMgFileName(?string $mgFileName): self
    {
        $this->mgFileName = $mgFileName;

        return $this;
    }

    public function getMgFileSize(): ?int
    {
        return $this->mgFileSize;
    }

    public function setMgFileSize(?int $mgFileSize): self
    {
        $this->mgFileSize = $mgFileSize;

        return $this;
    }

    public function getCcdFileName(): ?string
    {
        return $this->ccdFileName;
    }

    public function setCcdFileName(?string $ccdFileName): self
    {
        $this->ccdFileName = $ccdFileName;

        return $this;
    }

    public function getCcdFileSize(): ?int
    {
        return $this->ccdFileSize;
    }

    public function setCcdFileSize(?int $ccdFileSize): self
    {
        $this->ccdFileSize = $ccdFileSize;

        return $this;
    }

    public function getLon()
    {
        return $this->lon;
    }

    public function setLon($lon): self
    {
        $this->lon = $lon;

        return $this;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function setLat($lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getNbrCptrDiv(): ?int
    {
        return $this->nbrCptrDiv;
    }

    public function setNbrCptrDiv(?int $nbrCptrDiv): self
    {
        $this->nbrCptrDiv = $nbrCptrDiv;

        return $this;
    }

    public function getAccessibiliteCompteurs(): ?bool
    {
        return $this->accessibiliteCompteurs;
    }

    public function setAccessibiliteCompteurs(?bool $accessibiliteCompteurs): self
    {
        $this->accessibiliteCompteurs = $accessibiliteCompteurs;

        return $this;
    }

    public function getPoseRobinet(): ?bool
    {
        return $this->poseRobinet;
    }

    public function setPoseRobinet(?bool $poseRobinet): self
    {
        $this->poseRobinet = $poseRobinet;

        return $this;
    }

    public function getRespectDistance(): ?bool
    {
        return $this->respectDistance;
    }

    public function setRespectDistance(?bool $respectDistance): self
    {
        $this->respectDistance = $respectDistance;

        return $this;
    }

    public function getRaccordement(): ?bool
    {
        return $this->raccordement;
    }

    public function setRaccordement(?bool $raccordement): self
    {
        $this->raccordement = $raccordement;

        return $this;
    }

    public function getPoseClapet(): ?bool
    {
        return $this->poseClapet;
    }

    public function setPoseClapet(?bool $poseClapet): self
    {
        $this->poseClapet = $poseClapet;

        return $this;
    }

    public function getInstallationHorizontale(): ?bool
    {
        return $this->installationHorizontale;
    }

    public function setInstallationHorizontale(?bool $installationHorizontale): self
    {
        $this->installationHorizontale = $installationHorizontale;

        return $this;
    }

    public function getNumeration(): ?bool
    {
        return $this->numeration;
    }

    public function setNumeration(?bool $numeration): self
    {
        $this->numeration = $numeration;

        return $this;
    }


}
