<?php
/*
===========================

===========================
*/


$tmplt = 'includes/templates/';
$func  = 'includes/functions/';
$lang  = 'includes/lang/';
$css   = 'asset/css/';
$js    = 'asset/js/';

include 'connect.php'; // Database connection
include $func . 'function.php';
include $lang . 'arabic.php';
include $tmplt . 'header.php';



if (!isset($noSidearea)){
    include $tmplt . 'sidearea.php'; 
    include $tmplt . 'navbar.php'; 
}
?>
