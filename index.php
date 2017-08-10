<?php

require_once "vendor/autoload.php";


$number = new \PHPTools\Helpers\Number\Number(true);
// echo $number;
var_dump($number->format());
var_dump($number->integer());
var_dump($number->float());

$number->sum(123,45677)
    ->subtract(77)
    ->multiply(2)
    ->divide(2)
;
var_dump($number->float(), $number->modulo(21.3));
var_dump($number);
