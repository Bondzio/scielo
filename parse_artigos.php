<?php

require "common/config.php";
require "common/function/retirarAcento.php";
require "common/bizobj/class_artigos.php";
require "common/class/class-XMLtoArray.php";

echo "[Inicio] \n";

//echo "Pegando os artigos das revistas na base de dados \n";

//$artigos = new class_artigos;
//if ($artigos->select($sql)) {
	//echo "  + Salvando os dados das edicoes offline...";
	//while ($artigos->fetch()) {
		//$pid = "S".$artigos->pid.$artigos->art_num;
		//$file = "files/artigos/".$artigos->art_dt_download."_".$pid.".xml";
		
		$pid = "S0102-69092013000100001";
		$dt = "2014-05-15";
		$file = "files/artigos/".$dt."_".$pid.".xml";
		$xml = new XMLtoArray(file_get_contents($file, "r"));
		
		$xml->getArray();
		
		$open_article = false;
		$open_pub_date = false;
		$open_abstract = false;
		$language_abstract = false;
		
		$open_ref_lst = false;
		$open_ref = false;
		foreach ($xml->arr as $tag) {
			
			// DADOS PRINCIPAIS DO ARTIGO
			if ($tag["tag"] == "article-meta") {
				if ($tag["type"] == "open")
					$open_article = true;
				else
					$open_article = false;
			}
			
			if ($open_article) {
				if ($tag["tag"] == "pub-date") {
					if ($tag["type"] == "open" && $tag["attributes"]["pub-type"] == "pub")
						$open_pub_date = true;
					else
						$open_pub_date = false;
				}
				
				if ($open_pub_date) {
					if ($tag["tag"] == "month") {
						$item["month"] = $tag["value"]."\n";
					}
					if ($tag["tag"] == "year") {
						$item["year"] = $tag["value"]."\n";
					}
				}
				
				if ($tag["tag"] == "volume") {
					$item["volume"] = $tag["value"]."\n";					
				}
				
				if ($tag["tag"] == "numero") {
					$item["numero"] = $tag["value"]."\n";					
				}
				
				if ($tag["tag"] == "fpage") {
					$item["fpage"] = $tag["value"]."\n";					
				}
				
				if ($tag["tag"] == "lpage") {
					$item["lpage"] = $tag["value"]."\n";					
				}
				
				if ($tag["tag"] == "abstract") {
					if ($tag["attributes"]["xml:lang"]) {
						$language_abstract = $tag["attributes"]["xml:lang"];
					} else {
						$language_abstract = "pt";
					}
					if (!$tag["value"]) {
						$open_abstract = true;
					} else {
						$item["abstract"][$language_abstract]["text"] = $tag["value"];
					}
				}
				
				if ($open_abstract) {
					if ($tag["tag"] == "p") {
						$item["abstract"][$language_abstract] = $tag["value"];
						$open_abstract = false;
					}
				}
			}
			// FIM DADOS PRINCIPAIS DO ARTIGO 
			
			// ********************************
			
			// REFERÊNCIA BIBLIOGRÁFICA
			if ($tag["tag"] == "ref-list") {
				if ($tag["type"] == "open")
					$open_ref_lst = true;
				else
					$open_ref_lst = false;
			}
			
			if ($open_ref_lst) {
				if ($tag["tag"] == "ref") {
					if ($tag["type"] == "open") {
						$open_ref = true;
						$ref_id = $tag["attributes"]["id"];
					} else {
						$open_ref = false;
						$ref_id = "";
					}
				}
				
			}
		}
	//}
//}

?>