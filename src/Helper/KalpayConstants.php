<?php


namespace App\Helper;


class KalpayConstants
{
    public static $USER = '+33660657877' ;
    public static $UPWD = '0000' ;
    public static $ROLE = 'SuperMerchant' ;
    public static $LOGIN_URL = 'https://staging.kalpayinc.com/api/v1/Login' ;
    public static $GET_USER_URL = 'https://staging.kalpayinc.com/api/v1/User' ;
    public static $SEND_OPT_URL = 'https://staging.kalpayinc.com/api/v1/Otp' ;
    public static $SEND_KALPAY_PYMT_URL = 'https://staging.kalpayinc.com/api/v1/sale/merchant' ;
    public static $SEND_WALLET_PYMT_URL = 'https://staging.kalpayinc.com/api/v1/CallBack/createWalletToKalpay' ;
    public static $FIELD_PHONE = "phoneNumber" ;
    public static $FIELD_USPWD = "password" ;
    public static $FIELD_UROLE = "role" ;
    public static $FIELD_USRID = "userId" ;
    public static $FIELD_CSTID = "customerId" ;
    public static $FIELD_CDPIN = "pin" ;
    public static $FIELD_TGNUM = "targetNumber" ;
    public static $FIELD_WTYPE = "type" ;
    public static $FIELD_WAMNT = "amount" ;
    public static $FIELD_PMOPT = "otp" ;
    public static $FIELD_DESCR = "description" ;
    public static $FIELD_CLBCK = "callBackUrl" ;
    public static $FIELD_STATS = "status" ;
    public static $FIELD_PRTID = "partnerId" ;
    public static $FIELD_RPNUM = 'recipient_phone_number' ;
    public static $STATUS_SUCCESSFUL = 'SUCCESSFUL' ;

    public static $MESSAGES = array(
        '1000' => "L'utilisateur n'a pas pû être trouvé dans notre système, vérifiez bien son Id",
        '1003' => "L'utilisateur n'a pas encore été validé, contactez le support et donnez vos informations business",
        '1004' =>  "Vous avez déjà reçu un code SMS, utilisez le !",
        '1005' =>  "Le code SMS n'a pas pu être envoyé",
        '1006' => "Erreur serveur, Indisponible",
        '1007' => "Paramètres de connexion invalides",
        '1008' => "Format de données incorrect",
        '1009' => "Erreur inconnue",
        '1010' => "PIN incorrect",
        '1011' => "Balance insuffisante, rechargez votre compte",
        '1012' => "Code SMS expiré",
        '1041' => "Utilisateur bloqué temporairement avec trop de tentatives incorrectes de connexion: 3",
        '1061' => "Erreur au niveau du Provider du paiement marchand. D'habitude elle survient lorsqu'on fait des transactions qui se répétent avec un même montant et vers le même numéro. Elle est considérée comme suspecte"
    ) ;
}