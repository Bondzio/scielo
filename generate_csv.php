<?php

require "common/config.php";
require "common/function/retirarAcento.php";
require "common/bizobj/class_artigos_relatorios.php";
require "common/bizobj/class_autores.php";
require "common/bizobj/class_artigos_biblio_auts_relatorios.php";

echo "[Inicio] \n";

$artigos = new class_artigos;
if ($artigos->select($sql)) {
	$c = "pid, numero, url, titulo_pt, titulo_fr, titulo_en, titulo_es, autores_givennames, autores_surname, ";
	$c .= "autores_instituicao, revista, ano, resumo_pt, resumo_fr, resumo_en, resumo_es, publisher_name, ";
	$c .= "autores_biblio_givennames, autores_biblio_surname\n";
	
	$file = "output/output_".date("Y-m-d_his").".csv";
	$fp = fopen($file, 'w');
	fwrite($fp, $c);
	while ($artigos->fetch()) {
		echo "ID: ".$artigos->art_id."\n";
		echo "PID: ".$artigos->pid."\n";
		echo "NUM: ".$artigos->art_num."\n\n--\n\n";
		
		$givennames = "";
		$surname = "";
		$instituicao = "";
		
		$biblio_givennames = "";
		$biblio_surname = "";
		
		$autores = new class_autores;
		
		$autores->art_id = $artigos->art_id;
		if ($autores->select($sql)) {
			while ($autores->fetch()) {
				$givennames .= $autores->aut_givennames."; ";
				$surname .= $autores->aut_surname."; ";
				$instituicao .= $autores->aut_instituicao."; ";
			}
			$givennames = substr($givennames, 0 , -2);
			$surname = substr($surname, 0, -2);
			$instituicao = substr($instituicao, 0, -2);
		}
		
		$bib_autores = new class_bibliografia_autores;
		
		$bib_autores->art_id = $artigos->art_id;
		if ($bib_autores->select($sql)) {
			while ($bib_autores->fetch()) {
				$biblio_givennames .= utf8_decode($bib_autores->abi_givennames)."; ";
				$biblio_surname .= utf8_decode($bib_autores->abi_surname)."; ";
			}
			$biblio_givennames = substr($biblio_givennames, 0 , -2);
			$biblio_surname = substr($biblio_surname, 0, -2);
		}
		
		$l = "\"".$artigos->pid."\", ";
		$l .= "\"".$artigos->art_num."\", ";
		$l .= "\"".str_replace("amp;", "", $artigos->art_url)."\", ";
		$l .= "\"".$artigos->art_titulo_pt."\", ";
		$l .= "\"".$artigos->art_titulo_fr."\", ";
		$l .= "\"".$artigos->art_titulo_en."\", ";
		$l .= "\"".$artigos->art_titulo_es."\", ";
		$l .= "\"".$givennames."\", ";
		$l .= "\"".$surname."\", ";
		$l .= "\"".$instituicao."\", ";
		$l .= "\"".$artigos->rev_nome."\", ";
		$l .= "\"".$artigos->art_ano."\", ";
		$l .= "\"".$artigos->art_resumo_pt."\", ";
		$l .= "\"".$artigos->art_resumo_fr."\", ";
		$l .= "\"".$artigos->art_resumo_en."\", ";
		$l .= "\"".$artigos->art_resumo_es."\", ";
		$l .= "\"".$artigos->art_publisher_name."\", ";
		$l .= "\"".$biblio_givennames."\", ";
		$l .= "\"".$biblio_surname."\"\n";
		fwrite($fp, $l);
	}
	fclose($fp);
}

echo "[Fim]";