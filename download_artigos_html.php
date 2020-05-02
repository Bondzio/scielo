<?php

require "common/config.php";
require "common/function/retirarAcento.php";
require "common/bizobj/class_pid_revistas.php";
require "common/bizobj/class_artigos.php";

echo "[Inicio] \n";

echo "Pegando os artigos das revistas na base de dados \n";

$i = 1;
$artigos = new class_artigos;
if ($artigos->select($sql)) {
	
    echo "  + Salvando os dados dos artigos offline...\n";
	
    while ($artigos->fetch()) {
        $conteudo = "";
        
        if (trim($artigos->art_url) != "") { 
            $pid = "S".$artigos->pid.$artigos->art_num;
            $file = "files/artigos/html/".$pid.".html";
            
            echo $i." - ".$file."...";
            
            if (!file_exists($file)) {
                $url = html_entity_decode($artigos->art_url);
                $handle = @fopen($url, "r");
                if ($handle) {
                    while (!feof($handle))
                        $conteudo .= fgets($handle, 4096);

                    $handle2 = fopen($file, "w+");
                    if ($handle2) {
                        fwrite($handle2, $conteudo);
                        fclose($handle2);
                    }

                    echo " [OK] \n";

                    sleep(4);

                }
            } else {
                echo "[FAILED] \n";
            }
        }

        ++$i;
    }
} else {
    die("Não há artigos cadastrados");
}

echo "[Fim]"

?>