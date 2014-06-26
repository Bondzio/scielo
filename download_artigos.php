<?php

require "common/config.php";
require "common/function/retirarAcento.php";
require "common/bizobj/class_pid_revistas.php";
require "common/bizobj/class_artigos.php";

echo "[Inicio] \n";

echo "Pegando os artigos das revistas na base de dados \n";

$artigos = new class_artigos;
if ($artigos->select($sql)) {
	
    echo "  + Salvando os dados das edicoes offline...";
	
    while ($artigos->fetch()) {
        $conteudo = "";
		
        $pid = "S".$artigos->pid.$artigos->art_num;
        $file = "files/artigos/".date("Y-m-d")."_".$pid.".xml";
        $url = "http://www.scielo.br/scieloOrg/php/articleXML.php?pid=".$pid."&lang=pt";
        $handle = @fopen($url, "r");
        if ($handle) {
            while (!feof($handle))
                $conteudo .= fgets($handle, 4096);
            
            $handle2 = fopen($file, "w+");
            if ($handle2) {
                fwrite($handle2, $conteudo);
                fclose($handle2);
            }

            $artigos2 = new class_artigos;

            $artigos2->art_id = $artigos->art_id;
            $artigos2->art_dt_download = date("Y-m-d");
            $artigos2->update2($sql);

            fclose($handle);

            echo "[OK] \n";
        }
    }
} else
    die("Não há edicoes cadastradas");

echo "[Fim]"

?>