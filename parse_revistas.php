<?php

require "common/config.php";
require "common/function/retirarAcento.php";
require "common/bizobj/class_revistas.php";
require "common/bizobj/class_pid_revistas.php";

echo "[Inicio] \n";

echo "Pegando na base de dados a listagem de revistas \n";

$revistas = new class_revistas;
if ($revistas->select($sql)) {
    while ($revistas->fetch()) {
	
        echo " - Acessando a revista salva offline: ".retirarAcento($revistas->rev_nome)."\n";
		
        $file = "files/revistas/".$revistas->rev_dt_download."_".str_replace(" ", "_", retirarAcento($revistas->rev_nome)).".html";
        $handle = @fopen($file, "r");
        if ($handle) {
            echo "  + Extraindo os pIDs da revista e salvando no banco de dados...";
			
            $pid = array();
			
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
				
                //<A href="http://www.scielo.br/scielo.php?script=sci_issuetoc&amp;pid=0102-690920130002&amp;lng=pt&amp;nrm=iso">
                $pattern = '/http:\/\/www\.scielo\.br\/scielo\.php\?script=sci_issuetoc&amp;pid=([0-9]{4}-[0-9]*)&amp;lng=pt&amp;nrm=iso/';
                if (preg_match_all($pattern, $buffer, $pid_arr)) {
                    for ($i = 0; $i < sizeof($pid_arr[1]); ++$i) {
                        $p_rev = new class_pid_revistas;
				
                        $p_rev->rev_id = $revistas->rev_id;
                        $p_rev->pid = $pid_arr[1][$i];
                        $p_rev->insert($sql);
                    }
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