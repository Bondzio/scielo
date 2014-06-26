<?php

require "common/config.php";
require "common/function/retirarAcento.php";
require "common/bizobj/class_artigos.php";
require "common/bizobj/class_bibliografia.php";
require "common/bizobj/class_bibliografia_autores.php";
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
		
        unset($biblio);
		
        $open_ref_lst = false;
        $open_ref = false;
		
        $open_authors = false;
        $open_author_name = false;
        $cnt_author = false;
        if (isset($xml->arr)) {
            foreach ($xml->arr as $tag) {
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
					
                    if ($tag["tag"] == "nlm-citation" && $tag["type"] == "open") {
                        $biblio[$ref_id]["nlm-citation"] = $tag["attributes"]["citation-type"];
                    }
					
                    if ($tag["tag"] == "publisher-name") {
                        $biblio[$ref_id]["publisher-name"] = $tag["value"];
                    }
				
                    if ($tag["tag"] == "publisher-loc") {
                        $biblio[$ref_id]["publisher-loc"] = $tag["value"];
                    }
	    			
                    if ($tag["tag"] == "article-title") {
                        $biblio[$ref_id]["article-title"] = $tag["value"];
                    }
	    			
                    if ($tag["tag"] == "source") {
                        $biblio[$ref_id]["source"] = $tag["value"];
                    }
			
                    if ($tag["tag"] == "year") {
                        $biblio[$ref_id]["year"] = $tag["value"];
                    }
					
                    if ($tag["tag"] == "page-range") {
                        $biblio[$ref_id]["page-range"] = $tag["value"];
                    }
			
                    if ($tag["tag"] == "numero") {
                        $biblio[$ref_id]["numero"] = $tag["value"];
                    }
				
                    if ($tag["tag"] == "volume") {
                        $biblio[$ref_id]["volume"] = $tag["value"];
                    }   
					
                    if ($tag["tag"] == "issue") {
                        $biblio[$ref_id]["issue"] = $tag["value"];
                    }
					
                    if ($tag["tag"] == "person-group") {
                        if ($tag["type"] == "open" && $tag["attributes"]["person-group-type"] == "author") {
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
                            $biblio[$ref_id]["authors"][$cnt_author]["surname"] = $tag["value"];
                        }
						
                        if ($tag["tag"] == "given-names") {
                            $biblio[$ref_id]["authors"][$cnt_author]["given-names"] = $tag["value"];
                        }
                    }
                }
            }
			
            /****************************************************/
            /**** INSERINDO OS DADOS DA BIBLIOGRAFIA	*****/
            /****************************************************/
            if (isset($biblio)) {
                foreach ($biblio as $item) {
                    $biblio = new class_bibliografia;

                    $biblio->bib_titulo = $item["article-title"];;
                    $biblio->bib_tipo = $item["nlm-citation"];;
                    $biblio->bib_pub_name = $item["publisher-name"];;
                    $biblio->bib_pub_loc = $item["publisher-loc"];;
                    $biblio->bib_ano = $item["year"];;
                    $biblio->bib_paginas = $item["page-range"];;
                    $biblio->bib_numero = $item["numero"];;
                    $biblio->bib_volume = $item["volume"];;
                    $biblio->bib_issue = $item["issue"];
                    $biblio->bib_source = $item["source"]; 

                    $biblio->art_id = $artigos->art_id;
                    if ($biblio->insert($sql)) {
                        if (isset($item["authors"])) {
                            foreach ($item["authors"] as $autor) {
                                $aut_biblio = new class_bibliografia_autores;

                                $aut_biblio->abi_givennames = $autor["given-names"];
                                $aut_biblio->abi_surname = $autor["surname"];
                                $aut_biblio->bib_id = $biblio->bib_id;
                                if (!$aut_biblio->insert($sql))
                                    echo $aut_biblio->error."\n";
                            }
                        }
                    } else {
                        echo $biblio->error."\n";
                    }
                }
            }
        } else {
            echo "Error: Não foi extraído nada do XML \n";
        }
    }
}

?>