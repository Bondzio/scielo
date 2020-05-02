<?php

$dir = "files/artigos/xml/";
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