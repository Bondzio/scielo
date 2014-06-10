<?php

require "common/config.php";
require "common/function/retirarAcento.php";
require "common/bizobj/class_artigos.php";
require "common/bizobj/class_autores.php";
require "common/class/class-XMLtoArray.php";

echo "[Inicio] \n";

$artigos = new class_artigos;

if ($artigos->select($sql)) {
	while ($artigos->fetch()) {
		$pid = "S".$artigos->pid.$artigos->art_num;
		$file = "files/artigos/".$artigos->art_dt_download."_".$pid.".xml";
		
		echo "\n\n -- \n\nExtraindo dados do arquivo: ".$file."\n";
		echo "ID: ".$artigos->art_id."\n";
		echo "PID: ".$artigos->pid."\n";
		echo "NUM: ".$artigos->art_num."\n";
		
		$xml = new XMLtoArray(file_get_contents($file, "r"));
		
		$xml->getArray();
		
		unset($item);
		
		$open_journal = false;
		
		$open_article = false;
		$open_pub_date = false;
		
		$open_affiliation = false;
		
		$open_abstract = false;
		$language_abstract = false;
		$language_title = false;
		
		$open_authors = false;
		$open_author_name = false;
		$cnt_author = false;
		if (isset($xml->arr)) {
			foreach ($xml->arr as $tag) {
				
				// DADOS PRINCIPAIS DO ARTIGO
				if ($tag["tag"] == "journal-meta") {
					if ($tag["type"] == "open")
						$open_journal = true;
					else
						$open_journal = false;
				}
				
				if ($open_journal) {
					if ($tag["tag"] == "publisher-name") {
						$item["publisher-name"] = $tag["value"];					
					}
				
					if ($tag["tag"] == "issn") {
						$item["issn"] = $tag["value"];					
					}
				}
				
				if ($tag["tag"] == "article-meta") {
					if ($tag["type"] == "open")
						$open_article = true;
					else
						$open_article = false;
				}
				
				if ($open_article) {
					if ($tag["tag"] == "contrib-group") {
						if ($tag["type"] == "open") {
							$open_authors = true;
							$cnt_author = -1;
						} else
							$open_authors = false;
					}
					
					if ($open_authors) {
						if ($tag["tag"] == "name" && $tag["type"] == "open") {
							$cnt_author++;
						}
						
						if ($tag["tag"] == "surname") {
							$item["authors"][$cnt_author]["surname"] = $tag["value"];
						}
						
						if ($tag["tag"] == "given-names") {
							$item["authors"][$cnt_author]["given-names"] = $tag["value"];
						}
						
						if ($tag["tag"] == "xref" && $tag["attributes"]["ref-type"] == "aff") {
							$aff = $tag["attributes"]["rid"];
							$item["authors"][$cnt_author]["affiliation"][$aff]["rid"] = $aff;
						}
					}
					
					if ($tag["tag"] == "aff") {
						if ($tag["type"] == "open") {
							$open_affiliation = true;
							$aff = $tag["attributes"]["id"];
						} else {
							$open_affiliation = false;
							$aff = false;
						}
					}
					
					if ($open_affiliation) {
						if ($tag["tag"] == "institution") {
							if (sizeof($item["authors"]) > 1) {
								for ($i = 0; $i < sizeof($item["authors"]); ++$i) {
									if (isset($item["authors"][$i]["affiliation"][$aff]))
										$item["authors"][$i]["affiliation"][$aff]["institution"] = $tag["value"];
								}
							} else {
								$item["authors"][0]["affiliation"][$aff]["rid"] = $aff;
								$item["authors"][0]["affiliation"][$aff]["institution"] = $tag["value"];
							}
						}
					}
					
					if ($tag["tag"] == "pub-date") {
						if ($tag["type"] == "open" && $tag["attributes"]["pub-type"] == "pub")
							$open_pub_date = true;
						else
							$open_pub_date = false;
					}
					
					if ($open_pub_date) {
						if ($tag["tag"] == "month") {
							$item["month"] = $tag["value"];
						}
						if ($tag["tag"] == "year") {
							$item["year"] = $tag["value"];
						}
					}
					
					if ($tag["tag"] == "article-title") {
						if ($tag["attributes"]["xml:lang"]) {
							$language_title = $tag["attributes"]["xml:lang"];
						} else {
							$language_title = "pt";
						}
						$item["title"][$language_title] = $tag["value"];
					}
					
					if ($tag["tag"] == "volume") {
						$item["volume"] = $tag["value"];					
					}
					
					if ($tag["tag"] == "numero") {
						$item["numero"] = $tag["value"];					
					}
					
					if ($tag["tag"] == "fpage") {
						$item["fpage"] = $tag["value"];					
					}
					
					if ($tag["tag"] == "lpage") {
						$item["lpage"] = $tag["value"];					
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
			}
		} else {
			echo "Erro: Problemas com o XML: Verificar o problema.";
		}
		//print_r($item);
		//print_r($biblio);
		
		/****************************************************/
		/**** ATUALIZANDO E INSERINDO OS DADOS			*****/
		/****************************************************/
		
		if (isset($item)) {
			$artigos2 = new class_artigos;
			
			//$artigos2->begin($sql);
			
			$artigos2->art_id = $artigos->art_id;
			
			if ($item["title"]["pt"])
				$artigos2->art_titulo_pt = $item["title"]["pt"];
			else
				unset($artigos2->art_titulo_pt);
			
			if ($item["title"]["fr"])
				$artigos2->art_titulo_fr = $item["title"]["fr"];
			else
				unset($artigos2->art_titulo_fr);
			
			if ($item["title"]["en"])
				$artigos2->art_titulo_en = $item["title"]["en"];
			else
				unset($artigos2->art_titulo_en);
			
			if ($item["title"]["es"])
				$artigos2->art_titulo_es = $item["title"]["es"];
			else
				unset($artigos2->art_titulo_es);
			
			if ($item["year"])
				$artigos2->art_ano = $item["year"];
			else
				unset($artigos2->art_ano);
			
			if ($item["month"])
				$artigos2->art_mes = $item["month"];
			else
				unset($artigos2->art_mes);
			
			if ($item["volume"])
				$artigos2->art_volume = $item["volume"];
			else
				unset($artigos2->art_volume);
			
			if ($item["fpage"])
				$artigos2->art_fpage = $item["fpage"];
			else
				unset($artigos2->art_fpage);
			
			if ($item["lpage"])
				$artigos2->art_lpage = $item["lpage"];
			else
				unset($artigos2->art_lpage);
			
			if ($item["issn"])
				$artigos2->art_issn = $item["issn"];
			else
				unset($artigos2->art_issn);
			
			if ($item["publisher-name"])
				$artigos2->art_publisher_name = $item["publisher-name"];
			else
				unset($artigos2->art_publisher_name);
			
			if ($item["abstract"]["pt"])
				$artigos2->art_resumo_pt = $item["abstract"]["pt"];
			else
				unset($artigos2->art_resumo_pt);
			
			if ($item["abstract"]["fr"])
				$artigos2->art_resumo_fr = $item["abstract"]["fr"];
			else
				unset($artigos2->art_resumo_fr);
			
			if ($item["abstract"]["en"])
				$artigos2->art_resumo_en = $item["abstract"]["en"];
			else
				unset($artigos2->art_resumo_en);
			
			if ($item["abstract"]["es"])
				$artigos2->art_resumo_es = $item["abstract"]["es"];
			else
				unset($artigos2->art_resumo_es);
			
			if (!$artigos2->update($sql)) {
				echo $artigos2->error."\n";
			}
			
			if (isset($item["authors"])) {
				// Inserindo os autores
				foreach ($item["authors"] as $aut) {
					$autores = new class_autores;
					
					$autores->aut_givennames = $aut["surname"];
					$autores->aut_surname = $aut["given-names"];
					$autores->art_id = $artigos->art_id;
					
					if (isset($aut["affiliation"])) {
						foreach ($aut["affiliation"] as $a) {
							$autores->aut_instituicao = $a["institution"]." | ";
						}
						$autores->aut_instituicao = trim(substr($autores->aut_instituicao, 0, -3));
					}
					if (!$autores->insert($sql)) {
						echo $autores->error."\n";
					}
				}
			} else {
				echo "Erro: Não há autores no XML \n";
			}
		} else {
			echo "Echo: Não foi extraído nada do XML \n";
		}
	}
}

echo "[Fim] \n";

?>