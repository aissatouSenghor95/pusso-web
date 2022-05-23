<?php

namespace App\Controller;

use App\Repository\DemandeRepository;
use App\Repository\OrganismeRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    const SENEAU_ORG_ID = 5;
    const CANALP_ORG_ID = 6 ;
    public function __construct(DemandeRepository $demandeRepository,
                                ServiceRepository $serviceRepository,
                                OrganismeRepository $organismeRepository)
    {
        $this->demandeRepository = $demandeRepository ;
        $this->serviceRepository = $serviceRepository ;
        $this->organismeRepository = $organismeRepository ;
    }

    public function index(){

        if (!$this->get('session')->get('_security_main')) {
            return $this->redirectToRoute('login') ;
        }

        $data = ['page_title' => 'Dashboard'] ;
        $organismes = $this->organismeRepository->findBy([], ['ordre' => 'ASC']) ;

        if($this->getUser()->getRole()->getLibelle() == 'ROLE_CLIENT'){
            $data = [
                'nbrDemandes'                   => $this->demandeRepository->count(['owner' => $this->getUser()->getId()]),
                'nbrDemandesEncours'            => $this->demandeRepository->count(['owner' => $this->getUser()->getId(), 'statut' => [1,2]]),
                'nbrDemandesAttentePaiement'    => $this->demandeRepository->count(['owner' => $this->getUser()->getId(), 'statut' => 3 ]),
                'nbrDemandesRejetees'           => $this->demandeRepository->count(['owner' => $this->getUser()->getId(), 'statut' => [6,7] ]),
                'senEauServices'                => $this->serviceRepository->findBy(['organisme' => self::SENEAU_ORG_ID], ['ordre' => 'ASC']),
                'canalServices'                 => $this->serviceRepository->findBy(['organisme' => self::CANALP_ORG_ID], ['ordre' => 'ASC']),
                'organismes'                    => $organismes
            ] ;
        }elseif ($this->getUser()->getRole()->getLibelle() == 'ROLE_CUSTOMER_SERVICE' ||
            $this->getUser()->getRole()->getLibelle() == 'ROLE_ADMIN' ||
            $this->getUser()->getRole()->getLibelle() == 'ROLE_SUPER_ADMIN'){
            $data = [
                'nbrDemandes'                   => $this->demandeRepository->count(['statut' => 1]),
                'nbrDemandesEncours'            => $this->demandeRepository->count(['statut' => 2]),
                'nbrDemandesAttentePaiement'    => $this->demandeRepository->count(['statut' => 3 ]),
                'nbrDemandesRejetees'           => $this->demandeRepository->count(['statut' => 7 ]),
                'organismes'                    => $organismes
            ] ;
        }elseif ($this->getUser()->getRole()->getLibelle() == 'ROLE_PARTENAIRE'){
            $data = [
                'nbrDemandes'                   => $this->demandeRepository->count(['statut' => 2, 'organisme' => $this->getUser()->getOrganisme()->getId()]),
                'nbrDemandesAttentePaiement'    => $this->demandeRepository->count(['statut' => 3, 'organisme' => $this->getUser()->getOrganisme()->getId()]),
                'nbrDemandesValidees'           => $this->demandeRepository->count(['statut' => 4, 'organisme' => $this->getUser()->getOrganisme()->getId()]),
                'organismes'                    => $organismes
            ] ;
        }
        return $this->render('home/index.html.twig', $data);
    }
}
