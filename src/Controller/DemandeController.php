<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Demande;
use App\Entity\Utilisateur;
use App\Form\AbonnementMutationType;
use App\Form\AbonnementType;
use App\Form\AutorisationType;
use App\Form\BranchementType;
use App\Form\ChangementType;
use App\Form\DemandeType;
use App\Form\DepannageType;
use App\Form\ResidenceType;
use App\Form\ResiliationType;
use App\Form\TransfertType;
use App\Helper\ServicesEnum;
use App\Repository\CommentaireRepository;
use App\Repository\DemandeRepository;
use App\Repository\OrganismeRepository;
use App\Repository\StatutDemandeRepository;
use App\Repository\TypeCommentaireRepository;
use App\Services\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DemandeController extends AbstractController
{

    const ROLE_CLIENT = 'ROLE_CLIENT' ;
    const ROLE_CSUSER = 'ROLE_CUSTOMER_SERVICE' ;
    const ROLE_PRTNER = 'ROLE_PARTENAIRE' ;
    const ROLE_ADMIN    = 'ROLE_ADMIN' ;
    const ROLE_SUPER_ADMIN    = 'ROLE_SUPER_ADMIN' ;
    const STATUS_VALIDEE = 4 ;
    const STATUS_ATTENTE_PAIEMENT = 3 ;
    const STATUS_REJETEE_1 = 5 ;
    const STATUS_REJETEE_2 = 6 ;
    const MSG_VALIDATION = "La demande a été validée avec succès." ;
    const MSG_VALIDATION_ERROR = "Oups, la demande a été déjà étudiée par un autre gestionnaire." ;
    const COMMENT_TYPE_REJECT = 1 ;
    const COMMENT_TYPE_INFO = 2 ;

    public function __construct(DemandeRepository $demandeRepository,
                                StatutDemandeRepository $statutDemandeRepository,
                                CommentaireRepository $commentaireRepository,
                                TypeCommentaireRepository $typeCommentaireRepository,
                                OrganismeRepository $organismeRepository)
    {
        $this->demandeRepository = $demandeRepository ;
        $this->commentaireRepository = $commentaireRepository ;
        $this->statutDemandeRepository = $statutDemandeRepository ;
        $this->typeCommentaireRepository = $typeCommentaireRepository ;
        $this->organismeRepository = $organismeRepository ;
    }

    private function setRequestOrganisme(Request $request, Demande $demande){
        if($request->get('organisme')){
            $organisme = $this->organismeRepository->findOneBy(['nom' => $request->get('organisme')]) ;
            if($organisme){
                $demande->setOrganisme($organisme) ;
            }
        }
    }

    public function add(Request $request): Response
    {
        $demande = new Demande() ;
        $creationOK = false ;
        $this->setRequestOrganisme($request, $demande) ;
        $form = $this->createForm(DemandeType::class, $demande) ;
        $form->handleRequest($request) ;

        if($form->isSubmitted() && $request->isMethod('POST') && $form->isValid()){
            $em = $this->getDoctrine()->getManager() ;
            $demande->setOwner($this->getUser()) ;
            $demande->setStatut($this->statutDemandeRepository->find(1)) ;
            $em->persist($demande) ;
            $em->flush() ;
            $creationOK = true ;
        }
        return $this->render('demande/create.html.twig', [
            'form'          => $form->createView(),
            'creationOK'    => $creationOK,
            'page_title'    => 'Nouvelle demande'
        ]);
    }

    public function view (Request $request, $id){
        /**
         * @var Demande $demande
         */
        $demande = $this->demandeRepository->find($id) ;

        if(!$demande)
            throw $this->createNotFoundException('La ressource que vous recherchez n\'existe pas.') ;

        if(!$this->isGranted(self::ROLE_PRTNER))

        if($demande->getOwner()->getId() != $this->getUser()->getId()
            && !$this->isGranted(self::ROLE_CSUSER)
            && !$this->isGranted(self::ROLE_PRTNER)
            && !$this->isGranted(self::ROLE_ADMIN))
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits nécessaires pour accéder à la ressource demandée.') ;

        if($demande->getPrix() <= 0){
            $demande->setPrix($demande->getService()->getPrix()) ;
            $this->getDoctrine()->getManager()->flush();
        }

        $form = $this->createForm(self::getFormType($demande), $demande, self::getOptions($demande)) ;
        return $this->render('demande/view.html.twig', [

            'page_title'    => 'Détails d\'une demande',
            'demande'       => $demande,
            'form'          => $form->createView()
        ]);
    }

    public function mesDemandes(): Response
    {
        /**
         * @var Utilisateur $user
         */
        $user = $this->getUser() ;
        if($user->getRole()->getLibelle() == self::ROLE_CLIENT){
            $data = [
                'demandes' => $this->demandeRepository->findBy(['owner' => $user->getId()])
            ] ;
        }
        elseif ($user->getRole()->getLibelle() == self::ROLE_CSUSER || $user->getRole()->getLibelle() == self::ROLE_ADMIN || $user->getRole()->getLibelle() == self::ROLE_SUPER_ADMIN ){
            $data = [
                'demandes' => $this->demandeRepository->findBy(['statut' => 1])
            ] ;
        }
        elseif ($user->getRole()->getLibelle() == self::ROLE_PRTNER) {
            $data = [
                'demandes' => $this->demandeRepository->findBy(['statut' => 2, 'organisme' => $user->getOrganisme()->getId()])
            ] ;
        }
        return $this->render('demande/my-requests.html.twig',$data);
    }

    public function enEtude(): Response{
        /**
         * @var Utilisateur $user
         */
        $user = $this->getUser() ;


        if($user->getRole()->getLibelle() == self::ROLE_CLIENT){
            $data = [
                'demandes' => $this->demandeRepository->findBy(['owner' => $user->getId(), 'statut' => [1,2]])
            ] ;
        }
        elseif ($user->getRole()->getLibelle() == self::ROLE_CSUSER || $user->getRole()->getLibelle() == self::ROLE_ADMIN || $user->getRole()->getLibelle() == self::ROLE_SUPER_ADMIN){
            $data = [
                'demandes' => $this->demandeRepository->findBy(['statut' => 2])
            ] ;
        }
        else{
            throw $this->createNotFoundException('La ressource que vous recherchez n\'existe pas.') ;
        }

        return $this->render('demande/enEtude.html.twig', $data);
    }

    public function attentePaiement(){
        /**
         * @var Utilisateur $user
         */
        $user = $this->getUser() ;
        if($user->getRole()->getLibelle() == self::ROLE_CLIENT){
            $data = [
                'demandes' => $this->demandeRepository->findBy(['owner' => $user->getId(), 'statut' => 3])
            ] ;
        }
        elseif ($user->getRole()->getLibelle() == self::ROLE_CSUSER || $user->getRole()->getLibelle() == self::ROLE_ADMIN || $user->getRole()->getLibelle() == self::ROLE_SUPER_ADMIN){
            $data = [
                'demandes' => $this->demandeRepository->findBy(['statut' => 3])
            ] ;
        }elseif ($user->getRole()->getLibelle() == self::ROLE_PRTNER){
            $data = [
                'demandes' => $this->demandeRepository->findBy(['statut' => 3, 'organisme' => $user->getOrganisme()->getId()])
            ] ;
        }

        return $this->render('demande/attentePaiement.html.twig', $data);
    }

    public function validate(Request $request, Mailer $mailer){
        /**
         * @var Demande $demande
         */
        $demande = $this->demandeRepository->find($request->get('id')) ;

        if(!$demande)
            throw $this->createNotFoundException('La ressource que vous recherchez n\'existe pas.') ;

        /**
         * @var Utilisateur $user
         */
        $user = $this->getUser() ;

        if($user->getRole()->getLibelle() == self::ROLE_CSUSER){
            if($demande->getStatut()->getId() != 1){
                $this->addFlash('error', self::MSG_VALIDATION_ERROR) ;
            }
            else{
               self::internalValidation($demande, $mailer) ;
               $demande->setFirstValidatedAt(new \DateTime()) ;
               $demande->setFirstValidatedBy($this->getUser()) ;
               $this->getDoctrine()->getManager()->flush();
            }
        }
        elseif ($user->getRole()->getLibelle() == self::ROLE_PRTNER){
            if($demande->getStatut()->getId() != 2){
                $this->addFlash('error', self::MSG_VALIDATION_ERROR) ;
            }
            else{
                self::internalValidation($demande, $mailer) ;
                $demande->setLastValidatedAt(new \DateTime()) ;
                $demande->setLastValidatedBy($this->getUser()) ;
                $this->getDoctrine()->getManager()->flush();
            }
        }
        else{
            throw $this->createNotFoundException('La ressource que vous recherchez n\'existe pas.') ;
        }
        return $this->redirectToRoute('app_demande_view', ['id' => $demande->getId()]) ;
    }

    public function validees(){
        return self::loadByStatus(self::STATUS_VALIDEE, 'demande/validees.html.twig') ;
    }

    public function rejetees(){
        /**
         * @var Utilisateur $user
         */
        $user = $this->getUser() ;
        if($user->getRole()->getLibelle() == self::ROLE_CLIENT){
            $data = [
                'demandes' => $this->demandeRepository->findBy(['owner' => $user->getId(), 'statut' => [self::STATUS_REJETEE_1, self::STATUS_REJETEE_2]])
            ] ;
        }
        elseif ($user->getRole()->getLibelle() == self::ROLE_CSUSER || $user->getRole()->getLibelle() == self::ROLE_ADMIN){
            $data = [
                'demandes' => $this->demandeRepository->findBy(['statut' => [self::STATUS_REJETEE_1, self::STATUS_REJETEE_2] ])
            ] ;
        }elseif ($user->getRole()->getLibelle() == self::ROLE_PRTNER){
            $data = [
                'demandes' => $this->demandeRepository->findBy(['statut' => [self::STATUS_REJETEE_1, self::STATUS_REJETEE_2], 'organisme' => $user->getOrganisme()->getId()])
            ] ;
        }

        return $this->render('demande/rejetees.html.twig', $data);
    }

    private function loadByStatus(int $status, string $template){
        /**
         * @var Utilisateur $user
         */
        $user = $this->getUser() ;
        if($user->getRole()->getLibelle() == self::ROLE_CLIENT){
            $data = [
                'demandes' => $this->demandeRepository->findBy(['owner' => $user->getId(), 'statut' => $status])
            ] ;
        }
        elseif ($user->getRole()->getLibelle() == self::ROLE_CSUSER || $user->getRole()->getLibelle() == self::ROLE_ADMIN || $user->getRole()->getLibelle() == self::ROLE_SUPER_ADMIN ){
            $data = [
                'demandes' => $this->demandeRepository->findBy(['statut' => $status])
            ] ;
        }elseif ($user->getRole()->getLibelle() == self::ROLE_PRTNER){
            $data = [
                'demandes' => $this->demandeRepository->findBy(['statut' => $status, 'organisme' => $user->getOrganisme()->getId()])
            ] ;
        }

        return $this->render($template, $data);
    }

    public function reject(Request $request, Mailer $mailer){
        /**
         * @var Demande $demande
         */
        $demande = $this->demandeRepository->find($request->get('id')) ;

        if(!$demande)
            throw $this->createNotFoundException('La ressource que vous recherchez n\'existe pas.') ;

        /**
         * @var Utilisateur $user
         */
        $user = $this->getUser() ;

        if($request->isMethod('POST')){
            if($user->getRole()->getLibelle() == self::ROLE_CSUSER){
                if($demande->getStatut()->getId() != 1){
                    $this->addFlash('error', self::MSG_VALIDATION_ERROR) ;
                }
                else{
                    self::internalReject($demande,$mailer, $request->request->get('commentaire')) ;
                    $demande->setFirstRejectedAt(new \DateTime()) ;
                    $demande->setFirstRejectedBy($this->getUser()) ;
                    $this->getDoctrine()->getManager()->flush();
                }
            }
            elseif ($user->getRole()->getLibelle() == self::ROLE_PRTNER){
                if($demande->getStatut()->getId() != 2){
                    $this->addFlash('error', self::MSG_VALIDATION_ERROR) ;
                }
                else{
                    self::internalReject($demande,$mailer, $request->request->get('commentaire')) ;
                    $demande->setLastRejectedAt(new \DateTime()) ;
                    $demande->setLastRejectedBy($this->getUser()) ;
                    $this->getDoctrine()->getManager()->flush();
                }
            }
            else{
                throw $this->createNotFoundException('La ressource que vous recherchez n\'existe pas.') ;
            }
        }
        return $this->redirectToRoute('app_demande_view', ['id' => $demande->getId()]) ;
    }

    private function getCommentaire(string $corps, int $type,Demande $demande): Commentaire{
        $commentaire = new Commentaire() ;
        $commentaire->setCorps($corps) ;
        $commentaire->setDemande($demande) ;
        $commentaire->setAuteur($this->getUser()) ;
        $commentaire->setType($this->typeCommentaireRepository->find($type)) ;
        return $commentaire ;
    }

    private function internalReject(Demande $demande, Mailer $mailer, $commentaire){
        $statut = $this->getUser()->getRole()->getLibelle() == "ROLE_CUSTOMER_SERVICE" ? 5 : 6  ;
        $demande->setStatut($this->statutDemandeRepository->find($statut)) ;
        $em = $this->getDoctrine()->getManager() ;
        $em->persist(self::getCommentaire($commentaire,self::COMMENT_TYPE_REJECT,$demande)) ;
        $em->flush() ;
        // on utilise le service Mailer créé précédemment
        $bodyMail = $mailer->createBodyMail('mails/demande_rejetee.html.twig', [
            'demande'       => $demande,
            'motif'         => $commentaire
        ]);
        $mailer->sendMessage('noreply@pusso.com', $demande->getOwner()->getUsername(), '[PUSSO] Demande rejetée.', $bodyMail);

        $this->addFlash('success', "La demande a été rejetée.") ;
        $this->addFlash('info', "Le client recevra un email d'information.") ;
    }

    private function internalValidation(Demande $demande, Mailer $mailer){
        $statut = $this->getUser()->getRole()->getLibelle() == "ROLE_CUSTOMER_SERVICE" ? 2 : 3 ;
        $demande->setStatut($this->statutDemandeRepository->find($statut)) ;
        $em = $this->getDoctrine()->getManager() ;
        $em->flush() ;

        if(strcmp($demande->getOrganisme()->getNom(), ServicesEnum::LIB_SERVICE_ONAS) === 0){
            $demande->setStatut($this->statutDemandeRepository->find(self::STATUS_ATTENTE_PAIEMENT)) ;
            $demande->setLastValidatedBy($this->getUser()) ;
            $demande->setLastValidatedAt(new \DateTime()) ;
            $this->getDoctrine()->getManager()->flush();
        }

        // on utilise le service Mailer créé précédemment
        $bodyMail = $mailer->createBodyMail('mails/demande_validee.html.twig', [
            'demande'       => $demande,
        ]);
        $mailer->sendMessage('noreply@pusso.com', $demande->getOwner()->getUsername(), '[PUSSO] Demande validée', $bodyMail);

        $this->addFlash('success', self::MSG_VALIDATION) ;
        if($statut == 3)
            $this->addFlash('info', "Elle passera en attente de paiement du client.") ;
        else
            $this->addFlash('info', "La demande passe en étude chez le partenaire concerné.") ;
    }

    public function ajaxValidation(Request $request, Mailer $mailer): JsonResponse {
        if($request->isXmlHttpRequest() && $this->getUser()->getRole()->getLibelle() == self::ROLE_CSUSER){
            $amount = $request->request->get('amount') ;
            $demande= $this->demandeRepository->find($request->request->get('id')) ;

            if($demande != null){
                $demande->setPrix($amount) ;
                $this->internalValidation($demande, $mailer) ;
                return new JsonResponse() ;
            }
        }
        return new JsonResponse(['message' => 'FATAL ERROR'], Response::HTTP_BAD_REQUEST) ;
    }

    private function getFormType(Demande $demande){
        $res = "nan" ;
        $type = $demande->getService()->getType() ;
        if(strcmp($type, 'abonnement') == 0)
            $res = AbonnementType::class ;
        elseif (strcmp($type, 'branchement') == 0)
            $res = BranchementType::class ;
        elseif (strcmp($type, 'abonnement-mutation') == 0)
            $res = AbonnementMutationType::class ;
        elseif (strcmp($type, 'resiliation') == 0)
            $res = ResiliationType::class ;
        elseif (strcmp($type, 'transfert') == 0)
            $res = TransfertType::class ;
        elseif (strcmp($type, 'depannage') == 0)
            $res = DepannageType::class ;
        elseif (strcmp($type, 'residence') == 0 || strcmp($type, 'domicile') == 0 )
            $res = ResidenceType::class ;
        elseif (strcmp($type, 'changement') == 0 )
            $res = ChangementType::class ;
        elseif (strcmp($type, 'autorisation') == 0 )
            $res = AutorisationType::class ;
        return $res ;
    }

    private function getOptions(Demande $demande): array {
        $res = [] ;
        $type = $demande->getService()->getType() ;
        if(strcmp($type, 'residence') == 0)
            $res['residence'] = true ;
        elseif(strcmp($type, 'domicile') == 0)
            $res['residence'] = false ;
        elseif(strcmp($type, 'resiliation') == 0 ||
            strcmp($type, 'changement') == 0 ||
            strcmp($type, 'branchement') == 0)
            $res['organisme'] = $demande->getOrganisme()->getNom() ;
        elseif (strcmp($type, 'abonnement') == 0){
            $res['organisme'] = $demande->getOrganisme()->getNom() ;
            $res['forfaits'] = $demande->getService()->getForfaits() ;
        }
        return $res ;
    }
}
