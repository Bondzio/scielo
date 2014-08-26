<?php

require "common/config.php";
require "common/function/retirarAcento.php";
require "common/bizobj/class_pid_revistas.php";
require "common/bizobj/class_artigos.php";

echo "[Inicio] \n";

echo "Pegando os artigos das revistas na base de dados \n";

$artigos = new class_artigos;
if ($artigos->select($sql)) {
	
    echo "  + Salvando os dados dos edicoes offline...";
	
    while ($artigos->fetch()) {
        $conteudo = "";
        
        if (trim($artigos->art_url) != "") { 
            $pid = "S".$artigos->pid.$artigos->art_num;
            $file = "files/artigos/html/".date("Y-m-d")."_".$pid.".html";
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

                echo "[OK] \n";
            }
        }
    }
} else
    die("Não há edicoes cadastradas");

echo "[Fim]"

?>