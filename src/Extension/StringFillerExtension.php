<?php


namespace App\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class StringFillerExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('prettyString', [$this, 'formatString']),
        ];
    }

    public function formatString($string , $size = 0, $filler= '.') {
        if($size > strlen($string)){
            $length = strlen($string) ;
            $toFill = $size - $length ;
            $completion = str_repeat($filler, $toFill/2) ;
            if($toFill % 2 == 0){
                $toReturn = $completion . $string . $completion ;
            }
            else{
                $toReturn =  $completion . $string . substr($completion, 0, -1) ;
            }
            return $toReturn ;
        }
    }
}