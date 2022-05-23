<?php


namespace App\Controller;

use App\Entity\Demande;
use App\Entity\Paiement;
use App\Entity\Utilisateur;
use App\Helper\KalpayConstants;
use App\Helper\Messages;
use App\Helper\PaymentStatus;
use App\Helper\PaymentType;
use App\Repository\DemandeRepository;
use App\Repository\PaiementRepository;
use App\Repository\StatutDemandeRepository;
use App\Repository\StatutPaiementRepository;
use App\Repository\TypePaiementRepository;
use App\Repository\UtilisateurRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PaiementController extends AbstractController
{
    const STATUT_DEMANDE_PAYMENT_OK = 4 ;
    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger ;

    /**
     * @var DemandeRepository
     */
    private $demandeRepository;

    /**
     * @var PaiementRepository
     */
    private $paiementRepository ;

    /**
     * @var StatutPaiementRepository
     */
    private $statutPaiementRepository ;

    /**
     * @var TypePaiementRepository
     */
    private $typePaiementRepository ;

    /**
     * @var StatutDemandeRepository
     */
    private $statutDemandeRepository ;

    /**
     * @var UtilisateurRepository
     */
    private $userRepository ;

    private $userId ;
    private $token ;

    private $TYPE_MAPPING = [null, 11, 12, 13] ;

    public function __construct(HttpClientInterface $client,
                                LoggerInterface $logger,
                                DemandeRepository $demandeRepository,
                                PaiementRepository $paiementRepository,
                                StatutPaiementRepository $statutPaiementRepository,
                                TypePaiementRepository $typePaiementRepository,
                                StatutDemandeRepository $statutDemandeRepository,
                                UtilisateurRepository $userRepository,
                                TokenGeneratorInterface $tokenGenerator)
    {
        $this->client = $client;
        $this->logger = $logger ;
        $this->demandeRepository = $demandeRepository ;
        $this->paiementRepository = $paiementRepository ;
        $this->statutPaiementRepository = $statutPaiementRepository ;
        $this->typePaiementRepository = $typePaiementRepository ;
        $this->statutDemandeRepository = $statutDemandeRepository ;
        $this->userRepository = $userRepository ;
        $this->tokenGenerator = $tokenGenerator ;
    }

    /**
     * @Route("/ajax/payments/init", name="ajax_payment_init")
     */
    public function sendPayment(Request  $request): JsonResponse {
        if($request->isXmlHttpRequest()){
            $phone = $request->request->get('phone');
            $type = $request->request->get('type') ;
            $id = $request->request->get('demande') ;
            $this->logger->info($phone . " / ". $type);
            if($this->login()){
                $demande = $this->getDemande($id) ;
                if($demande->getStatut()->getId() != 3){
                    return new JsonResponse(['message' => Messages::PAYMENT_IMPOSSIBLE_RELOAD], Response::HTTP_BAD_REQUEST) ;
                }
                if($this->TYPE_MAPPING[$type] != null){ // ORANGE_FREE-MONEY_E-MONEY
                    return $this->walletPaymentProcess($phone, $this->TYPE_MAPPING[$type], $demande) ;
                }else{ // KALPAY
                    return $this->kalpayPaymentProcess($phone, null,null, $demande) ;
                }
            }
        }
        return new JsonResponse([ "message" => Messages::UNEXPECTED_ERROR_RELOAD ],Response::HTTP_BAD_REQUEST);
    }
    /**
     * @Route("/ajax/payments/finish", name="ajax_payment_finish")
     */
    public function finishPayment(Request $request): JsonResponse {
        if($request->isXmlHttpRequest()) {
            $pin = $request->request->get('pin');
            $opt = $request->request->get('opt');
            $idDemande = $request->request->get('demande') ;

            $demande = $this->getDemande($idDemande) ;
            if($demande->getStatut()->getId() != 3 || $demande->getOwner()->getId() != $this->getUser()->getId()){
                return new JsonResponse(['message' => Messages::PAYMENT_IMPOSSIBLE_RELOAD], Response::HTTP_BAD_REQUEST) ;
            }
            if($this->login()){
                return $this->kalpayPaymentProcess(null, $pin, $opt, $demande) ;
            }
            else{
                return new JsonResponse(['message' => Messages::LOGIN_FAILED_MESSAGE], Response::HTTP_BAD_REQUEST) ;
            }

        }
        return new JsonResponse([ "message" => Messages::UNEXPECTED_ERROR_RELOAD ],Response::HTTP_BAD_REQUEST);
    }

    private function kalpayPaymentProcess($phone, $pin, $opt, Demande $demande): JsonResponse {
        if(!$this->phoneFormatIsValid($phone))
            return new JsonResponse([ "message" => Messages::PHONE_NUMBER_INVALID_FORMAT ],Response::HTTP_BAD_REQUEST);

        /**
         * @var $paiement Paiement
         */
        $paiement = $this->paiementRepository->findOneBy(['demande' => $demande->getId(), 'statut' => 1]) ;

        if($paiement != null && $opt != null && $pin != null){

            if(!$this->pinFormatIsValid($pin))
                return new JsonResponse([ "message" => Messages::PIN_NUMBER_INVALID_FORMAT ],Response::HTTP_BAD_REQUEST);

            if($this->sendKalpayPayment($paiement->getNumero(), $pin, $opt, $paiement->getDemande())){
                $demande = $this->demandeRepository->find($paiement->getDemande()->getId()) ;
                $paiement->setStatut($this->statutPaiementRepository->findOneBy(['libelle' => PaymentStatus::STATUS_VALIDATED])) ;
                $demande->setStatut($this->statutDemandeRepository->find(self::STATUT_DEMANDE_PAYMENT_OK)) ;
                $em = $this->getDoctrine()->getManager() ;
                $em->flush();
                return new JsonResponse(["message" => Messages::PAYMENT_SUCCESSFULL ], Response::HTTP_OK) ;
            }else{
                $paiement->setStatut($this->statutPaiementRepository->findOneBy(['libelle' => PaymentStatus::STATUS_FAILED])) ;
                $em = $this->getDoctrine()->getManager() ;
                $em->flush();
                return new JsonResponse(['message' => 'Paiement échoué, veuillez réessayer'], Response::HTTP_BAD_REQUEST ) ;
            }
        }
        else{
            if($this->sendOpt($phone)){
                $this->initPayment($demande, $phone, PaymentType::TYPE_KALPAY);
                return new JsonResponse(["message" => Messages::VALIDATION_CODE_IS_SENT ], Response::HTTP_OK) ;
            }else{
                return new JsonResponse(["message" => Messages::KALPAY_ACCOUNT_NOT_FOUND], Response::HTTP_BAD_REQUEST) ;
            }
        }

    }

    private function sendKalpayPayment(string $phone, string $pin, string $opt, Demande $demande): bool{
        $response = null ;
        try {
            $response = $this->client->request(
                Request::METHOD_POST,
                KalpayConstants::$SEND_KALPAY_PYMT_URL,
                [
                    'headers' => [
                        'Authorization' => 'Bearer '. $this->token,
                        'Content-Type' => 'application/json'
                    ],
                    'body' => json_encode([
                        KalpayConstants::$FIELD_CSTID => $this->getCustomerId($phone),
                        KalpayConstants::$FIELD_CDPIN => $pin,
                        KalpayConstants::$FIELD_PMOPT => $opt,
                        KalpayConstants::$FIELD_WAMNT => 0 * ($demande->getPrix() + 10000) + 25,
                        KalpayConstants::$FIELD_DESCR => "Paiement service PUSSO",
                    ])
                ]
            );
            $this->logger->info($response->getContent());
            return $response->getStatusCode() == Response::HTTP_OK ;
        }catch (\Exception $ex){
            $this->logger->error("****ERREUR****" . $ex->getMessage()) ;
            $this->logger->info($response->getContent());
            return false ;
        }
    }

    private function sendOpt($phone){
        try {
            $response = $this->client->request(
                Request::METHOD_PUT,
                KalpayConstants::$SEND_OPT_URL,
                [
                    'headers' => [
                        'Authorization' => 'Bearer '. $this->token,
                        'Content-Type' => 'application/json'
                    ],
                    'body' => json_encode([
                        KalpayConstants::$FIELD_USRID => $this->getCustomerId($phone),
                    ])
                ]
            );
            return $response->getStatusCode() == Response::HTTP_OK ;
        }catch (\Exception $ex){
            $this->logger->error("****ERREUR****" . $ex->getMessage()) ;
            return false ;
        }
    }

    private function walletPaymentProcess($phone, $type, Demande $demande): JsonResponse{
        if(!$this->phoneFormatIsValid($phone))
            return new JsonResponse([ "message" => Messages::PHONE_NUMBER_INVALID_FORMAT ],Response::HTTP_BAD_REQUEST);
        else if($this->sendWalletPaymentProvider($phone, $type, $demande)){
            $this->initPayment($demande, $phone, $this->getPaymentTypeFromInt($type));
            return new JsonResponse(["message" => Messages::VALIDATION_CODE_IS_SENT, "finish" => true], Response::HTTP_OK) ;
        }else{
            return new JsonResponse(["message" => Messages::WALLET_ACCOUNT_NOT_FOUND], Response::HTTP_BAD_REQUEST) ;
        }
    }

    private function getPaymentTypeFromInt(string $type): string {
        if($type == 11)
            return PaymentType::TYPE_FM ;
        elseif ($type == 12)
            return PaymentType::TYPE_OM ;
        elseif ($type == 13)
            return PaymentType::TYPE_EM ;
        return PaymentType::TYPE_KALPAY ;
    }

    private function sendWalletPaymentProvider($phone, $type, Demande $demande): bool{
        try {
            $user = $this->userRepository->find($this->getUser()->getId()) ;
            $user->setToken($this->tokenGenerator->generateToken()) ;
            $url = 'https://pusso.technokocc.fr' . $this->generateUrl('app_payment_wallet_confirm', array('id' => $demande->getId(), 'token' => $user->getToken())) ;
            $this->logger->info("URL CALLBACK = ". $url);
            $response = $this->client->request(
                Request::METHOD_POST,
                KalpayConstants::$SEND_WALLET_PYMT_URL,
                [
                    'headers' => [
                        'Authorization' => 'Bearer '. $this->token,
                        'Content-Type' => 'application/json'
                    ],
                    'body' => json_encode([
                        KalpayConstants::$FIELD_USRID => $this->userId,
                        KalpayConstants::$FIELD_CDPIN => KalpayConstants::$UPWD,
                        KalpayConstants::$FIELD_TGNUM => $phone,
                        KalpayConstants::$FIELD_WTYPE => $type,
                        KalpayConstants::$FIELD_WAMNT => 0 * ($demande->getPrix() + 10000) + 5,
                        KalpayConstants::$FIELD_CLBCK => $url,
                        KalpayConstants::$FIELD_PRTID => $user->getToken()
                    ])
                ]
            );
            $this->getDoctrine()->getManager()->flush();
            $this->logger->info($response->getContent());
            return $response->getStatusCode() == Response::HTTP_OK ;
        }catch (\Exception $ex){
            $this->logger->error("****SENDING WALLET PAYMENT FAILED****: " . $ex->getMessage()) ;
            return false ;
        }
    }

    public function confirm(Request $request): Response {

        $this->logger->info($request->getContent());
        $this->logger->info("confirmation du paiement ID = ". $request->get('id')) ;

        /**
         * @var $payment Paiement
         */
        $payment = $this->paiementRepository->findOneBy(['demande' => intval($request->get('id')), 'statut' => 1], ['id' => 'DESC']);
        /**
         * @var $user Utilisateur
         */
        $user = $this->userRepository->findOneBy(['token' => $request->get('token')]) ;
        $content = json_decode($request->getContent()) ;

        if( $payment == null || $user == null || strcmp($payment->getNumero(), $content->recipient_phone_number) !== 0
            || $payment->getDemande()->getStatut()->getId() != 3 || $user->getId() != $payment->getDemande()->getOwner()->getId())
            throw $this->createAccessDeniedException('Access denied') ;

        if($content->status == KalpayConstants::$STATUS_SUCCESSFUL){
            $demande = $this->demandeRepository->find($payment->getDemande()->getId()) ;
            $demande->setStatut($this->statutDemandeRepository->find(self::STATUT_DEMANDE_PAYMENT_OK)) ;
            $demande->setPaiementOk(true) ;
            $payment->setStatut($this->statutPaiementRepository->findOneBy(['libelle' => PaymentStatus::STATUS_VALIDATED])) ;
        }
        else{
            $payment->setStatut($this->statutPaiementRepository->findOneBy(['libelle' => PaymentStatus::STATUS_FAILED])) ;
        }
        $user->eraseCredentials() ;
        $this->getDoctrine()->getManager()->flush();
        return new Response() ;
    }

    private function phoneFormatIsValid(?string $phone): bool{
        return true ;
    }

    private function pinFormatIsValid(?string $pin): bool {
        return true ;
    }

    private function login(): bool {
        try {
            $response = $this->client->request(
                Request::METHOD_POST,
                KalpayConstants::$LOGIN_URL,
                [
                    'headers' => [
                        'Content-Type' => 'application/json'
                    ],
                    'body' => json_encode([
                        KalpayConstants::$FIELD_PHONE => KalpayConstants::$USER,
                        KalpayConstants::$FIELD_USPWD => KalpayConstants::$UPWD,
                        KalpayConstants::$FIELD_UROLE => KalpayConstants::$ROLE
                    ])
                ]
            );
            $this->userId = $response->toArray()['response']['data']['info']['userId'] ;
            $this->token = $response->toArray()['response']['data']['token'] ;
            $this->logger->info("UserID = " . $this->userId);
            return true;
        }catch (\Exception $ex){
            $this->logger->error("****LOGIN FAILED****: " . $ex->getMessage()) ;
            return false ;
        }
    }

    private function getCustomerId(String $phone): string{
        try {
            $response = $this->client->request(
                Request::METHOD_GET,
                KalpayConstants::$GET_USER_URL . '/'. $phone. '/Customer',
                [
                    'headers' => [
                        'Authorization' => 'Bearer '. $this->token
                    ]
                ]
            );
            return $response->toArray()['response']['data']['id'] ;
        }catch (\Exception $ex){
            $this->logger->error("****ERREUR****" . $ex->getMessage()) ;
            return new JsonResponse(["message"=>"error"],Response::HTTP_BAD_REQUEST);
        }
    }

    private function getDemande(int $id): Demande {
        $demande = $this->demandeRepository->find($id) ;
        if(!$demande || $this->getUser()->getId() != $demande->getOwner()->getId())
            throw new $this->createNotFoundException("erreur fatale.") ;
        return $demande ;
    }

    private function initPayment(Demande $demande, $phone, string $type): void {
        $payment = new Paiement() ;
        $payment->setDemande($demande) ;
        $payment->setStatut($this->statutPaiementRepository->findOneBy(['libelle' => PaymentStatus::STATUS_INITIATED])) ;
        $payment->setMontant($demande->getService()->getPrix() + $demande->getService()->getFrais()) ;
        $payment->setType($this->typePaiementRepository->findOneBy(['libelle' => $type ])) ;
        $payment->setNumero($phone) ;
        $payment->setCreatedBy($this->getUser()) ;
        $em = $this->getDoctrine()->getManager() ;
        $em->persist($payment);
        $em->flush();
    }

    public function myPayments(Request $request): Response{
        $payments = $this->paiementRepository->findAllByDemandeOwnerId($this->getUser()->getId()) ;
        return $this->render('paiement/my-payments.html.twig', ['paiements' => $payments]) ;
    }

}