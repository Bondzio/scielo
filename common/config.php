<?php

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

ini_set('display_errors','On');
error_reporting(E_ALL ^ E_NOTICE);

session_start();

/* Vari�veis para acesso a base de dados local */
//$db_user = "root";
//$db_pass = "1234";
//$db_db = "scielo";
//$db_server = "localhost";

/* Variáveis para acesso a base de dados remota */
$db_user = "thyagosimas24";
$db_pass = "abacabb123";
$db_db = "thyagosimas24";
$db_server = "mysql23.torradeira.com";

/* Conectando a base de dados */
$sql = mysqli_connect($db_server, $db_user, $db_pass, $db_db);
if (mysqli_connect_errno()) {
	$err =  "Failed to connect to MySQL: " . mysqli_connect_error();
	die($err);
}


?>