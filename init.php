<?php
/*
===========================

===========================
*/

$tmplt = 'includes/templates/';
$func  = 'admin/includes/functions/';
$lang  = 'includes/lang/';
$css   = 'asset/css/';
$js    = 'asset/js/';

include 'admin/connect.php'; // Database connection
include $func . 'function.php';
include $tmplt . 'header.php';
include $tmplt . 'navbar.php';
