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
		
        $pid = "S".$artigos->pid.$artigos->art_num;
        $file = "files/artigos/xml/".$pid.".xml";
        if (!file_exists($file)) {
            echo $i." - ".$file."...";

            //$url = "http://www.scielo.br/scieloOrg/php/articleXML.php?pid=S0103-33522015000200121&lang=en";
            //die($url);

            $url = "http://www.scielo.br/scieloOrg/php/articleXML.php?pid=".$pid."&lang=en";
            $handle = @fopen($url, "r");
            if ($handle) {
                while (!feof($handle)) {
                    $conteudo .= fgets($handle, 4096);
                }

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

                sleep(6);
                
                ++$i;
            } else {
                echo "[FAILED]";
            }
        }
    }
} else {
    die("Não há artigos cadastrados");
}

echo "[Fim]"

?>