<?php

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

ini_set('display_errors','On');
error_reporting(E_ALL ^ E_NOTICE);

session_start();

/* Variáveis para acesso a base de dados remota */
$db_user = "********";
$db_pass = "********";
$db_db = "********";
$db_server = "********";

/* Conectando a base de dados */
$sql = mysqli_connect($db_server, $db_user, $db_pass, $db_db);
if (mysqli_connect_errno()) {
    $err =  "Failed to connect to MySQL: " . mysqli_connect_error();
    die($err);
}


?>