<?php

require "common/config.php";
require "common/function/retirarAcento.php";
require "common/bizobj/class_pid_revistas.php";
require "common/bizobj/class_artigos.php";

echo "[Inicio] \n";

echo "Pegando os artigos das revistas na base de dados \n";

$i = 1;
if (($handle = fopen("./files/artigos/correlacao.csv", "r")) !== FALSE) {
	
    echo "  + Salvando os dados dos artigos offline...\n";
	
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $conteudo = "";
        
        $pid = trim($data[1]);
        $file = "files/artigos/xml/".trim($data[0]).".xml";
        
        echo $i." - ".$file."...";
            
        if (!file_exists($file)) {
            $url = "http://www.scielo.br/scieloOrg/php/articleXML.php?pid=".$pid."&lang=en";
            
            $handle2 = @fopen($url, "r");
            if ($handle2) {
                while (!feof($handle2)) {
                    $conteudo .= fgets($handle2, 4096);
                }

                $handle3 = fopen($file, "w+");
                if ($handle3) {
                    fwrite($handle3, $conteudo);
                    fclose($handle3);
                }

                echo "[OK] \n";

                sleep(6);
            } else {
                echo "[FAILED] \n";    
            }
        } else {
            echo "[EXISTED] \n";
        }
        
        ++$i;
    }
} else {
    die("Não há artigos cadastrados");
}

echo "[Fim]"

?>