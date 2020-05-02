<?php

$html_path = "files/artigos/html/";
$files = scandir($html_path);
foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) == "html") {
        $aux = explode("_", $file);
        //rename ($html_path.$file, $html_path.$aux[1]);
    }
}


?>