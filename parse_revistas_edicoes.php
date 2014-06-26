<?php

require "common/config.php";
require "common/function/retirarAcento.php";
require "common/bizobj/class_pid_revistas.php";
require "common/bizobj/class_artigos.php";

echo "[Inicio] \n";

//echo "Pegando na base de dados a listagem de revistas \n";

$edicoes = new class_pid_revistas;
if ($edicoes->select($sql)) {
    while ($edicoes->fetch()) {
	
        //echo " - Acessando a revista salva offline: ".retirarAcento($revistas->rev_nome)."\n";
		
        $file = "files/revistas/".$edicoes->pidrev_dt_download."_".str_replace(array(" ",":"), "_", retirarAcento($edicoes->rev_nome))."_".$edicoes->pid.".html";
        $handle = @fopen($file, "r");
        if ($handle) {
            //echo "  + Extraindo os pIDs da revista e salvando no banco de dados...";
			
            $pid = array();
			
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
				
                //<a href="http://www.scielo.br/scielo.php?script=sci_arttext&amp;pid=S0011-52582014000100001&amp;lng=pt&amp;nrm=iso&amp;tlng=pt">
                $pattern = '/http:\/\/www\.scielo\.br\/scielo\.php\?script=sci_arttext&amp;pid=S([0-9]{4}-[0-9]*)&amp;lng=pt&amp;nrm=iso&amp;tlng=pt/';
                if (preg_match($pattern, $buffer, $pid_arr)) {
                    $artigos = new class_artigos;
					
                    $artigos->pidrev_id = $edicoes->pidrev_id;
                    $artigos->art_num = substr($pid_arr[1], -5);
                    $artigos->art_url = $pid_arr[0];
					
                    $artigos->insert($sql);
                }
            }
            fclose($handle);
            echo "[OK] \n";
        }
    }
} else
    die("Não há revistas cadastradas");

echo "[Fim]"

?>