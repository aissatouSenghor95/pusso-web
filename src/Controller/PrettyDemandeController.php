<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Extension\StringFillerExtension;
use App\Form\AbonnementMutationType;
use App\Form\AbonnementType;
use App\Form\AutorisationType;
use App\Form\BranchementType;
use App\Form\ChangementType;
use App\Form\CompteurDivisionnaireType;
use App\Form\DemandeType;
use App\Form\DepannageType;
use App\Form\EnregistrementType;
use App\Form\ResidenceType;
use App\Form\ResiliationType;
use App\Form\TransfertType;
use App\Helper\SdeBranchementFieldLength;
use App\Repository\CommentaireRepository;
use App\Repository\DemandeRepository;
use App\Repository\OrganismeRepository;
use App\Repository\ServiceRepository;
use App\Repository\StatutDemandeRepository;
use App\Repository\TypeCommentaireRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Geocoder\Provider\Provider;
use Geocoder\Query\GeocodeQuery;
use GuzzleHttp\ClientInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PrettyDemandeController extends AbstractController
{
    public function __construct(DemandeRepository $demandeRepository,
                                StatutDemandeRepository $statutDemandeRepository,
                                CommentaireRepository $commentaireRepository,
                                TypeCommentaireRepository $typeCommentaireRepository,
                                OrganismeRepository $organismeRepository,
                                ServiceRepository  $serviceRepository)
    {
        $this->demandeRepository = $demandeRepository ;
        $this->commentaireRepository = $commentaireRepository ;
        $this->statutDemandeRepository = $statutDemandeRepository ;
        $this->typeCommentaireRepository = $typeCommentaireRepository ;
        $this->organismeRepository = $organismeRepository ;
        $this->serviceRepository = $serviceRepository ;
    }

    private function setRequestService(Request $request, Demande $demande){
        if($request->get('tag')){
            $service = $this->serviceRepository->findOneBy(['tag' => $request->get('tag')]) ;
            if(!$service){
                throw $this->createNotFoundException('Le service que vous recherchez n\'existe pas.') ;
            }
            $demande->setService($service) ;
        }
    }
    public function branchement(Request $request): Response{
        $demande = new Demande() ;
        $creationOK = false ;
        $this->setRequestService($request, $demande) ;
        self::setCommonInfos($demande) ;
        $form = $this->createForm($demande->getService()->getTag() == 'SDECD'? CompteurDivisionnaireType::class : BranchementType::class, $demande, array('organisme' => $demande->getOrganisme()->getNom(), 'service' => $demande->getService()->getTag())) ;
        $form->handleRequest($request) ;
        if($form->isSubmitted() && $request->isMethod('POST') && $form->isValid()) {
            $em = $this->getDoctrine()->getManager() ;
            $demande->setOwner($this->getUser()) ;
            $demande->setPrix($demande->getService()->getPrix()) ;
            $demande->setStatut($this->statutDemandeRepository->find(1)) ;
            $em->persist($demande) ;
            $em->flush() ;
            $creationOK = true ;
        }
        return $this->render('demande/'.$demande->getService()->getTemplate(), [
            'form'          => $form->createView(),
            'creationOK'    => $creationOK,
            'page_title'    => 'Demande de branchement',
            'tag'         => $demande->getService()->getTag(),
            'logo'          => $demande->getService()->getOrganisme()->getLogo()
        ]);
    }

    public function changement(Request $request): Response{
        $demande = new Demande() ;
        $creationOK = false ;
        $this->setRequestService($request, $demande) ;
        self::setCommonInfos($demande) ;
        $form = $this->createForm(ChangementType::class, $demande, array('organisme' => $demande->getOrganisme()->getNom())) ;
        $form->handleRequest($request) ;
        if($form->isSubmitted() && $request->isMethod('POST') && $form->isValid()) {
            $em = $this->getDoctrine()->getManager() ;
            $demande->setOwner($this->getUser()) ;
            $demande->setPrix($demande->getService()->getPrix()) ;
            $demande->setStatut($this->statutDemandeRepository->find(1)) ;
            $em->persist($demande) ;
            $em->flush() ;
            $creationOK = true ;
        }
        return $this->render('demande/'.$demande->getService()->getTemplate(), [
            'form'          => $form->createView(),
            'creationOK'    => $creationOK,
            'page_title'    => 'Avenant',
            'logo'          => $demande->getService()->getOrganisme()->getLogo()
        ]);
    }

    public function abonnement(Request $request, HttpClientInterface $client, LoggerInterface $logger): Response{
        $demande = new Demande() ;
        $creationOK = false ;
        $response = $client->request('GET', 'http://ip-api.com/json/' . $request->getClientIp()) ;
        $logger->info('Status', ['response' => $response->getStatusCode()]);
        $infos = $response->toArray() ;
        $this->setRequestService($request, $demande) ;
        self::setCommonInfos($demande) ;
        $form = $this->createForm(AbonnementType::class, $demande, ['forfaits' => $demande->getService()->getForfaits(), 'organisme' => $demande->getService()->getOrganisme()->getNom()]) ;
        $form->handleRequest($request) ;
        if($form->isSubmitted() && $request->isMethod('POST') && $form->isValid()) {
            $em = $this->getDoctrine()->getManager() ;
            $demande->setLat($infos['lon']) ;
            $demande->setLon($infos['lat']) ;
            $demande->setOwner($this->getUser()) ;
            $demande->setPrix($demande->getForfait()->getPrix()) ;
            $demande->setStatut($this->statutDemandeRepository->find(1)) ;
            $em->persist($demande) ;
            $em->flush() ;
            $creationOK = true ;
        }
        return $this->render('demande/abonnement.html.twig', [
            'form'          => $form->createView(),
            'creationOK'    => $creationOK,
            'forfaits'      => $demande->getService()->getForfaits(),
            'organisme'     => $demande->getService()->getOrganisme()->getNom(),
            'logo'          => $demande->getService()->getOrganisme()->getLogo(),
            'page_title'    => 'Demande d\'abonnement'
        ]);
    }

    public function abonnementMutation(Request $request): Response{
        $demande = new Demande() ;
        $creationOK = false ;
        $this->setRequestService($request, $demande) ;
        self::setCommonInfos($demande) ;
        $form = $this->createForm(AbonnementMutationType::class, $demande) ;
        $form->handleRequest($request) ;
        if($form->isSubmitted() && $request->isMethod('POST') && $form->isValid()) {
            $em = $this->getDoctrine()->getManager() ;
            $demande->setOwner($this->getUser()) ;
            $demande->setPrix($demande->getForfait()->getPrix()) ;
            $demande->setStatut($this->statutDemandeRepository->find(1)) ;
            $em->persist($demande) ;
            $em->flush() ;
            $creationOK = true ;
        }
        return $this->render('demande/abonnement-mutation.html.twig', [
            'form'          => $form->createView(),
            'creationOK'    => $creationOK,
            'forfaits'      => $demande->getService()->getForfaits(),
            'page_title'    => 'Demande d\'abonnement',
            'logo'          => $demande->getService()->getOrganisme()->getLogo()
        ]);
    }

    private function setCommonInfos(Demande $demande){
        $demande->setOrganisme($demande->getService()->getOrganisme()) ;
        $demande->setNom($this->getUser()->getNom()) ;
        $demande->setPrenom($this->getUser()->getPrenom()) ;
        $demande->setEmail($this->getUser()->getUsername()) ;
        $demande->setDateNaissance($this->getUser()->getBirthday()) ;
    }

    public function depannage(Request $request): Response {
        $demande = new Demande() ;
        $creationOK = false ;
        $this->setRequestService($request, $demande) ;
        self::setCommonInfos($demande) ;
        $form = $this->createForm(DepannageType::class, $demande) ;
        $form->handleRequest($request) ;
        if($form->isSubmitted() && $request->isMethod('POST') && $form->isValid()) {
            $em = $this->getDoctrine()->getManager() ;
            $demande->setOwner($this->getUser()) ;
            $demande->setPrix($demande->getService()->getPrix()) ;
            $demande->setStatut($this->statutDemandeRepository->find(1)) ;
            $em->persist($demande) ;
            $em->flush() ;
            $creationOK = true ;
        }
        return $this->render('demande/depannage.html.twig', [
            'form'          => $form->createView(),
            'creationOK'    => $creationOK,
            'logo'          => $demande->getService()->getOrganisme()->getLogo(),
            'page_title'    => 'Demande de dépannage'
        ]);
    }

    public function resiliation(Request $request): Response{
        $demande = new Demande() ;
        $creationOK = false ;
        $this->setRequestService($request, $demande) ;
        self::setCommonInfos($demande) ;
        $form = $this->createForm(ResiliationType::class, $demande, array('organisme' => $demande->getOrganisme()->getNom())) ;
        $form->handleRequest($request) ;
        if($form->isSubmitted() && $request->isMethod('POST') && $form->isValid()) {
            $em = $this->getDoctrine()->getManager() ;
            $demande->setOwner($this->getUser()) ;
            $demande->setPrix($demande->getService()->getPrix()) ;
            $demande->setStatut($this->statutDemandeRepository->find(1)) ;
            $em->persist($demande) ;
            $em->flush() ;
            $creationOK = true ;
        }
        return $this->render('demande/resiliation.html.twig', [
            'form'          => $form->createView(),
            'creationOK'    => $creationOK,
            'page_title'    => 'Demande de résiliation',
            'organisme'     => $demande->getOrganisme()->getNom(),
            'logo'          => $demande->getService()->getOrganisme()->getLogo()
        ]);
    }

    public function transfert(Request $request): Response{
        $demande = new Demande() ;
        $creationOK = false ;
        $this->setRequestService($request, $demande) ;
        self::setCommonInfos($demande) ;
        $form = $this->createForm(TransfertType::class, $demande) ;
        $form->handleRequest($request) ;
        if($form->isSubmitted() && $request->isMethod('POST') && $form->isValid()) {
            $em = $this->getDoctrine()->getManager() ;
            $demande->setOwner($this->getUser()) ;
            $demande->setPrix($demande->getService()->getPrix()) ;
            $demande->setStatut($this->statutDemandeRepository->find(1)) ;
            $em->persist($demande) ;
            $em->flush() ;
            $creationOK = true ;
        }
        return $this->render('demande/transfert.html.twig', [
            'form'          => $form->createView(),
            'creationOK'    => $creationOK,
            'page_title'    => 'Demande de transfert',
            'logo'          => $demande->getService()->getOrganisme()->getLogo()
        ]);
    }

    public function autorisation(Request $request): Response{
        $demande = new Demande() ;
        $creationOK = false ;
        $this->setRequestService($request, $demande) ;
        self::setCommonInfos($demande) ;
        $form = $this->createForm(AutorisationType::class, $demande) ;
        $form->handleRequest($request) ;
        if($form->isSubmitted() && $request->isMethod('POST') && $form->isValid()) {
            $em = $this->getDoctrine()->getManager() ;
            $demande->setOwner($this->getUser()) ;
            $demande->setPrix($demande->getService()->getPrix()) ;
            $demande->setStatut($this->statutDemandeRepository->find(1)) ;
            $em->persist($demande) ;
            $em->flush() ;
            $creationOK = true ;
        }
        return $this->render('demande/autorisation.html.twig', [
            'form'          => $form->createView(),
            'creationOK'    => $creationOK,
            'page_title'    => 'Demande d\'autorisation de construire',
            'logo'          => $demande->getService()->getOrganisme()->getLogo()
        ]);
    }


    public function residence(Request $request): Response{
        $demande = new Demande() ;
        $creationOK = false ;
        $this->setRequestService($request, $demande) ;
        self::setCommonInfos($demande) ;
        $form = $this->createForm(ResidenceType::class, $demande, ['residence' => true]) ;
        $form->handleRequest($request) ;
        if($form->isSubmitted() && $request->isMethod('POST') && $form->isValid()) {
            $em = $this->getDoctrine()->getManager() ;
            $demande->setOwner($this->getUser()) ;
            $demande->setPrix($demande->getService()->getPrix()) ;
            $demande->setStatut($this->statutDemandeRepository->find(1)) ;
            $em->persist($demande) ;
            $em->flush() ;
            $creationOK = true ;
        }
        return $this->render('demande/residence.html.twig', [
            'form'          => $form->createView(),
            'creationOK'    => $creationOK,
            'page_title'    => 'Demande de certificat de résidence',
            'logo'          => $demande->getService()->getOrganisme()->getLogo()
        ]);
    }

    public function domicile(Request $request): Response{
        $demande = new Demande() ;
        $creationOK = false ;
        $this->setRequestService($request, $demande) ;
        self::setCommonInfos($demande) ;
        $form = $this->createForm(ResidenceType::class, $demande, ['residence' => false]) ;
        $form->handleRequest($request) ;
        if($form->isSubmitted() && $request->isMethod('POST') && $form->isValid()) {
            $em = $this->getDoctrine()->getManager() ;
            $demande->setOwner($this->getUser()) ;
            $demande->setPrix($demande->getService()->getPrix()) ;
            $demande->setStatut($this->statutDemandeRepository->find(1)) ;
            $em->persist($demande) ;
            $em->flush() ;
            $creationOK = true ;
        }
        return $this->render('demande/domicile.html.twig', [
            'form'          => $form->createView(),
            'creationOK'    => $creationOK,
            'page_title'    => 'Demande de certificat de résidence',
            'logo'          => $demande->getService()->getOrganisme()->getLogo()
        ]);
    }

    public function enregistrement(Request $request): Response{
        $demande = new Demande() ;
        $creationOK = false ;
        $this->setRequestService($request, $demande) ;
        self::setCommonInfos($demande) ;
        $form = $this->createForm(EnregistrementType::class, $demande) ;
        $form->handleRequest($request) ;
        if($form->isSubmitted() && $request->isMethod('POST') && $form->isValid()) {
            $em = $this->getDoctrine()->getManager() ;
            $demande->setOwner($this->getUser()) ;
            $demande->setPrix($demande->getService()->getPrix()) ;
            $demande->setStatut($this->statutDemandeRepository->find(1)) ;
            $em->persist($demande) ;
            $em->flush() ;
            $creationOK = true ;
        }
        return $this->render('demande/enregistrement.html.twig', [
            'form'          => $form->createView(),
            'creationOK'    => $creationOK,
            'page_title'    => 'Demande d\'enregistrement de contrat.',
            'logo'          => $demande->getService()->getOrganisme()->getLogo()
        ]);
    }

    public function choose() {
        return $this->render('demande/choix_service.html.twig', [
            'page_title'    => 'Demande de service',
            'organismes'    => $this->organismeRepository->findBy([], ['ordre' => 'ASC'])
        ]);
    }

    public function getPdf(Request $request, Pdf $pdf, StringFillerExtension $extension){

        $demande = $this->demandeRepository->find($request->get('id')) ;

        if(!$demande || $demande->getService()->getTemplate() == null){
            throw $this->createNotFoundException('La ressource que vous recherchez n\'existe pas.') ;
        }
        $html = $this->renderView('pdf-templates/' . $demande->getService()->getTemplate(), array(
            'person' => $extension->formatString($demande->getOwner()->getPrenom() . ' ' . $demande->getOwner()->getNom(),SdeBranchementFieldLength::FIELD_NAME),
            'cin'    => $demande->getCin(),
            'nature' => $demande->getNature(),
            'adresse'=> $demande->getAdresse(),
            'adressePro'=> $demande->getAdressePro(),
            'lieuBranchement'=> $demande->getLieuBranchement(),
            'telDomicile'=>$demande->getTelDomicile(),
            'telBureau'=>$demande->getTelBureau(),
            'telPortable'=>$demande->getContact(),
            'nomVoisin'=>$demande->getNomVoisin(),
            'policeVoisin'=>$demande->getPoliceVoisin(),
            $this->getSelectedTypeLieuField($demande) => 'X',
            $this->getSelectedTypeUsageField($demande) => 'X',
            'nbrPointDeau' => $demande->getNbrPointDeau(),
            'nbrLavabos'=> $demande->getNbrLavabos(),
            'nbrRobinets'=>$demande->getNbrRobinets(),
            'nbrEviers'=>$demande->getNbrEviers(),
            'nbrBaignoires'=>$demande->getNbrBaignoires(),
            'nbrUrinoirs'=>$demande->getNbrUrinoirs(),
            'nbrLavoirs'=>$demande->getNbrLavoirs(),
            'nbrBidets'=>$demande->getNbrBidets(),
            'nbrWc'=>$demande->getNbrWc() ,
            'surfaceJardin'=>$demande->getSurfaceJardin(),
            'capacitePiscine'=>$demande->getCapacitePiscine(),
            'consommationAnnuelle'=>$demande->getConsAnnuelle(),
            'consommationMensuelle'=>$demande->getConsMensuelle(),
            'debitJournalier'=>$demande->getDebitJournalier(),
            'debitHoraire'=>$demande->getDebitHoraire(),
            'dateCreation'=>$demande->getCreatedAt()
        ));

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('isRemoteEnabled', true);
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');
        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser (force download)
        $dompdf->stream($demande->getReference().".pdf", [
            "Attachment" => true
        ]);
        return new Response() ;

        /*return new Response( $pdf->getOutputFromHtml($html), 200, array(
            'Content-Type'          => 'application/pdf',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $demande->getReference() . '.pdf'),
        ));*/
    }

    private function getSelectedTypeLieuField(Demande $demande){
        return $demande->getTypeLieu()->getField() ;
    }

    private function getSelectedTypeUsageField(Demande $demande){
        return $demande->getTypeUsage()->getField() ;
    }
}
