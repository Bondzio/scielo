<?php

require "common/config.php";
require "common/function/retirarAcento.php";
require "common/bizobj/class_revistas.php";
require "common/bizobj/class_pid_revistas.php";

echo "[Inicio] \n";

echo "Pegando as edicoes das revistas na base de dados \n";

$edicoes = new class_pid_revistas;
if ($edicoes->select($sql)) {
    $i = 0;
    while ($edicoes->fetch()) {
        //echo " - Acessando a revista: ".retirarAcento($edicoes->rev_nome)."\n";
        $conteudo = "";
		
        $file = "files/revistas/".date("Y-m-d")."_".str_replace(array(" ",":"), "_", retirarAcento($edicoes->rev_nome))."_".$edicoes->pid.".html";
        $url = "http://www.scielo.br/scielo.php?script=sci_issuetoc&pid=".$edicoes->pid."&lng=pt&nrm=iso";
		
        $handle = @fopen($url, "r");
        if ($handle) {
            //echo "  + Salvando os dados das edicoes offline...";
			
            while (!feof($handle))
                $conteudo .= fgets($handle, 4096);
            
            $handle2 = fopen($file, "w+");
            if ($handle2) {
                fwrite($handle2, $conteudo);
                fclose($handle2);
                echo "[OK] \n";
            }
			
            //echo "  + Atualizando a data de download da revista...";
			
            $edicoes2 = new class_pid_revistas;
			
            $edicoes2->pidrev_id = $edicoes->pidrev_id;
            $edicoes2->pidrev_dt_download = date("Y-m-d");
            $edicoes2->update($sql);
            //echo "[OK] \n";
			
            fclose($handle);
        }
    }
} else
    die("Nao ha edicoes cadastradas");

echo "[Fim]";

?>