<?php

require "common/config.php";
require "common/function/retirarAcento.php";
require "common/bizobj/class_revistas.php";

echo "[Inicio] \n";

echo "Pegando na base de dados a listagem de revistas \n";

$revistas = new class_revistas;
if ($revistas->select($sql)) {
    while ($revistas->fetch()) {
        $conteudo = "";
		
        echo " - Acessando a revista: ".retirarAcento($revistas->rev_nome)."\n";
		
        $file = "files/revistas/".date("Y-m-d")."_".str_replace(array(" ",":"), "_", retirarAcento($revistas->rev_nome)).".html";
        $handle = @fopen($revistas->rev_url, "r");
        if ($handle) {
            echo "  + Salvando os dados da revista offline...";
			
            while (!feof($handle))
                $conteudo .= fgets($handle, 4096);
			
            $handle2 = fopen($file, "w+");
            if ($handle2) {
                fwrite($handle2, $conteudo);
                fclose($handle2);
                echo "[OK] \n";
            }
			
            echo "  + Atualizando a data de download da revista...";
			
            $revistas2 = new class_revistas;
			
            $revistas2->rev_id = $revistas->rev_id;
            $revistas2->rev_dt_download = date("Y-m-d");
            $revistas2->update($sql);
            echo "[OK] \n";
			
            fclose($handle);
        }
    }
} else
    die("Não há revistas cadastradas");

echo "[Fim]"

?>