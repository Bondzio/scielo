<?php

require "common/config.php";
require "common/bizobj/class_revistas.php";
require "common/bizobj/class_pid_revistas.php";

$revistas = new class_revistas;
if ($revistas->select($sql)) {
	while ($revistas->fetch()) {
		//$file = $revistas->rev_url;
		$file = "files/revistas/volume2.html";
		
		$handle = @fopen($file, "r");
		if ($handle) {
			
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
		}
		fclose($handle);
	}
} else
	die("Não há revistas cadastradas");

?>