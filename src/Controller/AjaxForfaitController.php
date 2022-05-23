<?php


namespace App\Controller;

use App\Helper\Messages;
use App\Repository\ForfaitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Routing\Annotation\Route;

class AjaxForfaitController extends AbstractController
{
    /**
     * @var ForfaitRepository
     */
    private $forfaitRepository ;

    public function __construct(ForfaitRepository $forfaitRepository)
    {
        $this->forfaitRepository = $forfaitRepository ;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/ajax/forfaits/get", name="ajax_forfaits_get")
     */
    public function getForfait(Request $request): JsonResponse {
        if($request->isXmlHttpRequest()){
            $id = $request->request->get('id');
            $forfait = $this->forfaitRepository->find($id) ;
            if(!$forfait)
                return new JsonResponse([ "message" => 'Forfait non trouvé' ],Response::HTTP_BAD_REQUEST);
            return new JsonResponse(
                ['forfait' => [
                    'description'   => $forfait->getDescription(),
                    'titre'         => $forfait->getTitre(),
                    'prix'          => $forfait->getPrix()
                ],
                    'success' => true ], Response::HTTP_OK) ;
        }
        return new JsonResponse([ "message" => Messages::UNEXPECTED_ERROR_RELOAD ],Response::HTTP_BAD_REQUEST);
    }
}