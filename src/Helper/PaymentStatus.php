<?php


namespace App\Helper;


abstract class PaymentStatus
{
    const STATUS_INITIATED  = "INITIATED" ;
    const STATUS_VALIDATED  = "VALIDATED" ;
    const STATUS_CANCELED   = "CANCELED" ;
    const STATUS_FAILED     = "FAILED" ;

}