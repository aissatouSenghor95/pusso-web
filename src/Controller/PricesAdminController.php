<?php

namespace App\Controller;

use App\Entity\Organisme;
use App\Entity\Service;
use App\Form\EditServicePricesType;
use App\Form\UserType;
use App\Repository\OrganismeRepository;
use App\Repository\ServiceRepository;
use Http\Discovery\Exception\NotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted("ROLE_ADMIN")]
class PricesAdminController extends AbstractController
{
    private OrganismeRepository $organismeRepository ;
    private ServiceRepository $serviceRepository ;

    public function __construct(OrganismeRepository $organismeRepository, ServiceRepository $serviceRepository)
    {
        $this->organismeRepository = $organismeRepository ;
        $this->serviceRepository = $serviceRepository ;
    }

    #[Route('/admin/organismes', name: 'admin_organismes')]
    public function index(): Response
    {
        return $this->render('prices_admin/organismes.html.twig', [
            'organismes' => $this->organismeRepository->findAll()
        ]);
    }

    #[Route('/admin/organismes/{id}', name: 'admin_organismes_show_services', requirements: ['id' => '\d+'])]
    public function showServices(int $id): Response
    {
        return $this->render('prices_admin/organisme_services.html.twig', [
            'organisme' =>self::getOrganismeOrThrowError($id),
        ]);
    }

    #[Route('/admin/services/{id}', name: 'admin_services_edit', requirements: ['id' => '\d+'])]
    public function editService(int $id, Request $request): Response
    {
        $service = self::getServiceOrThrowError($id) ;
        $form = $this->createForm(EditServicePricesType::class, $service) ;

        $form->handleRequest($request) ;

        $modificationOK = false ;

        if($form->isSubmitted() && $form->isValid() && $request->isMethod('POST')){
            try {
                $this->getDoctrine()->getManager()->flush() ;
                $this->addFlash('success' , 'Les modifications ont ??t?? sauvegard??es avec succ??s.');
                $modificationOK = true ;
            }catch (\Exception $ex){
                $this->addFlash('error' , 'Erreur rencontr??e lors de la sauvegarde, veuillez r??essayer?');
            }
        }

        return $this->render('prices_admin/organisme_services_edit.html.twig', [
            'service' => $service,
            'modificationOK' => $modificationOK,
            'form' => $form->createView()
        ]);
    }

    private function getOrganismeOrThrowError(int $id): Organisme {
        $organisme = $this->organismeRepository->find($id) ;
        if($organisme == null)
            throw new NotFoundHttpException() ;
        return $organisme ;
    }

    private function getServiceOrThrowError(int $id): Service {
        $service = $this->serviceRepository->find($id) ;
        if($service == null)
            throw new NotFoundHttpException() ;
        return $service ;
    }
}
