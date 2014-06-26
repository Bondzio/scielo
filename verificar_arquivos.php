<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$dir = "files/artigos/";
$files = scandir($dir);

for ($i = 0; $i < sizeof($files); ++$i) {
    $conteudo = "";
    $handle = @fopen($dir.$files[$i], "r");
    if ($handle) {
        while (!feof($handle)) {
            $conteudo .= fgets($handle, 4096);
        }
        if (trim($conteudo) == "") {
            echo "Arquivo sem nada dentro: ".$files[$i]."\n";
        }
    } else {
        echo "Arquivo nao encontrado: ".$files[$i]."\n";
    }
    
}