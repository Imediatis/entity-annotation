<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once __DIR__.'/../vendor/autoload.php';

use Imediatis\EntityAnnotation\DatePart;

$dpart = new DatePart('2018-12-12 15:20:55');

echo $dpart->isValideDate;