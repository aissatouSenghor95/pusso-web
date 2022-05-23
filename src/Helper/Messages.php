<?php


namespace App\Helper;


abstract class Messages
{
    const PHONE_NUMBER_INVALID_FORMAT   = 'Veuillez vérifier le format de votre numéro de téléphone.' ;
    const PIN_NUMBER_INVALID_FORMAT     = 'Veuillez vérifier le format du code pin.' ;
    const VALIDATION_CODE_IS_SENT       = 'Code de validation envoyé par sms.' ;
    const KALPAY_ACCOUNT_NOT_FOUND      = 'Compte Kalpay non trouvé ou inactif.' ;
    const WALLET_ACCOUNT_NOT_FOUND      = 'Compte non trouvé, veuillez réessayer.' ;
    const PAYMENT_IMPOSSIBLE_RELOAD     = 'Paiement impossible, veuillez recharger la page.' ;
    const UNEXPECTED_ERROR_RELOAD       = 'Erreur inattendue, veuillez réessayer.' ;
    const PAYMENT_SUCCESSFULL           = 'Votre paiement est validé avec succès.' ;
    const LOGIN_FAILED_MESSAGE          = 'Service de paiement indisponible (login)' ;
}