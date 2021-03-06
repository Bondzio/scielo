<?php

require "common/config.php";
require "common/function/retirarAcento.php";
require "common/bizobj/class_artigos_relatorios.php";
require "common/bizobj/class_autores.php";
require "common/bizobj/class_artigos_biblio_auts_relatorios.php";
require "common/bizobj/class_palavras_chave.php";

function escapar($m) {
	$m = html_entity_decode(utf8_encode($m));
	return str_replace(array(";", "\""), array(",", "'"), trim($m));
}

echo "[Inicio] \n";

$artigos = new class_artigos;
if ($artigos->select($sql)) {
	$c = "pid; numero; url; titulo_pt; titulo_fr; titulo_en; titulo_es; autores_givennames; autores_surname; ";
	$c .= "autores_completo; autores_instituicao; revista; ano; resumo_pt; resumo_fr; resumo_en; resumo_es; ";
	$c .= "palavras_chave; publisher_name; autores_biblio_givennames; autores_biblio_surname; autores_biblio_completo\n";
	
	$file = "output/output_".date("Y-m-d_his").".csv";
	$fp = fopen($file, 'w');
	fwrite($fp, $c);
	while ($artigos->fetch()) {
		echo "ID: ".$artigos->art_id."\n";
		echo "PID: ".$artigos->pid."\n";
		echo "NUM: ".$artigos->art_num."\n\n--\n\n";
		
		$givennames = "";
		$surname = "";
                $autores_completo = "";
		$instituicao = "";
		
		$biblio_givennames = "";
		$biblio_surname = "";
		$biblio_completo = "";
                
                $kwd = "";
                
		$autores = new class_autores;
		
		$autores->art_id = $artigos->art_id;
		if ($autores->select($sql)) {
			while ($autores->fetch()) {
				$givennames .= $autores->aut_surname.", ";
				$surname .= $autores->aut_givennames.", ";
                                $autores_completo .= $autores->aut_surname." ".strtoupper($autores->aut_givennames).", ";
				$instituicao = trim(str_replace(array(",", "|"), array("", ";"), $autores->aut_instituicao)).", ";
			}
			$givennames = substr($givennames, 0 , -2);
			$surname = substr($surname, 0, -2);
                        $autores_completo = substr($autores_completo, 0, -2);
			$instituicao = substr($instituicao, 0, -2);
		}
		
		$bib_autores = new class_bibliografia_autores;
		
		$bib_autores->art_id = $artigos->art_id;
		if ($bib_autores->select($sql)) {
                        while ($bib_autores->fetch()) {
				$biblio_givennames .= $bib_autores->abi_givennames.", ";
				$biblio_surname .= $bib_autores->abi_surname.", ";
                                
                                $aux = $bib_autores->abi_givennames;
                                $biblio_completo .= strtoupper($aux[0]).". ".$bib_autores->abi_surname.", ";
			}
			$biblio_givennames = substr($biblio_givennames, 0 , -2);
			$biblio_surname = substr($biblio_surname, 0, -2);
                        $biblio_completo = substr($biblio_completo, 0, -2);
		}
		
                $palavras = new class_palavras_chave;
                $palavras->art_id = $artigos->art_id;
                if ($palavras->select($sql)) {
                    while ($palavras->fetch()) {
                       $kwd .= $palavras->pch_palavra.", ";
                    }
                    $kwd = substr($kwd, 0 , -2);
                }
                
		$l = $artigos->pid."; ";
		$l .= $artigos->art_num."; ";
		$l .= str_replace("amp;", "", $artigos->art_url)."; ";
		$l .= escapar($artigos->art_titulo_pt)."; ";
		$l .= escapar($artigos->art_titulo_fr)."; ";
		$l .= escapar($artigos->art_titulo_en)."; ";
		$l .= escapar($artigos->art_titulo_es)."; ";
		$l .= escapar($givennames)."; ";
		$l .= escapar($surname)."; ";
		$l .= escapar($autores_completo)."; ";
		$l .= escapar($instituicao)."; ";
		$l .= escapar($artigos->rev_nome)."; ";
		$l .= $artigos->art_ano."; ";
		$l .= escapar($artigos->art_resumo_pt)."; ";
		$l .= escapar($artigos->art_resumo_fr)."; ";
		$l .= escapar($artigos->art_resumo_en)."; ";
		$l .= escapar($artigos->art_resumo_es)."; ";
                $l .= escapar($kwd)."; ";
		$l .= escapar($artigos->art_publisher_name)."; ";
		$l .= escapar($biblio_givennames)."; ";
		$l .= escapar($biblio_surname)."; ";
		$l .= escapar($biblio_completo)."\n";
		fwrite($fp, $l);
	}
	fclose($fp);
}

echo "[Fim]\n";