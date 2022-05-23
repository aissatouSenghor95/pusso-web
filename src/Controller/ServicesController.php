<?php

namespace App\Controller;

use App\Repository\OrganismeRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ServicesController extends AbstractController
{
    /**
     * @var ServiceRepository
     */
    private $serviceRepository ;

    /**
     * @var OrganismeRepository
     */
    private $organismeRepository ;

    public function __construct(ServiceRepository $serviceRepository, OrganismeRepository $organismeRepository)
    {
        $this->serviceRepository = $serviceRepository ;
        $this->organismeRepository = $organismeRepository ;
    }

    public function index(): Response
    {
        return $this->render('services/index.html.twig', [
            'page_title'    => 'Nos services',
            'organismes'    => $this->organismeRepository->findBy([], ['ordre' => 'ASC'])
        ]);
    }

    public function detail($org): Response
    {
        $organisme = $this->organismeRepository->find($org) ;

        if(!$organisme)
            throw $this->createNotFoundException('Le service que vous recherchez n\'existe pas.') ;

        return $this->render('services/detail.html.twig', [
            'page_title'    => 'Services ' . $organisme->getNom(),
            'organisme'     => $organisme

        ]);
    }
}
