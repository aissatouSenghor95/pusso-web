<?php


namespace App\Controller;


use App\Repository\OrganismeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AjaxServicesController extends AbstractController
{

    /**
     * @var OrganismeRepository
     */
    private $organismeRepository ;

    public function __construct(OrganismeRepository $organismeRepository)
    {
        $this->organismeRepository = $organismeRepository ;
    }

    /**
     * @Route("/ajax/organisms/get", name="ajax_organisms_get")
     */
    public function getOrganismes(){
        $organisms = $this->organismeRepository->findBy([], ['ordre' => 'ASC']) ;
        $data = array() ;
        foreach ($organisms as $organism){
            $toArray = array (
                'id' => $organism->getId(),
                'nom' => $organism->getNom()
                //'logo' => $organism->getLogo(),
                //'secteur'=>$organism->getSecteur()
            ) ;
            /*$services = array() ;
            foreach ($organism->getServices() as $service){
                $services[] = array(
                   'id' => $service->getId(),
                   'libelle' => $service->getLibelle(),
                   'description' => $service->getDescription(),
                   'ordre'  => $service->getOrdre(),
                    'tag'   => $service->getTag(),
                    'type'  => $service->getType()
                ) ;
            }
            $toArray['services'] = $services ;*/
            $data[] = $toArray ;
        }
        return new JsonResponse(
            [
                'data' => $data
            ],
            Response::HTTP_OK) ;
    }

}