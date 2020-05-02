<?php

require "common/config.php";
require "common/function/retirarAcento.php";
require "common/bizobj/class_artigos.php";
require "common/bizobj/class_palavras_chave.php";
require "common/class/class-XMLtoArray.php";

echo "[Inicio] \n";

$artigos = new class_artigos;

if ($artigos->select($sql)) {
    while ($artigos->fetch()) {
        $pid = "S".$artigos->pid.$artigos->art_num;
        $file = "files/artigos/xml/".$pid.".xml";
        
        echo "\n\n -- \n\nExtraindo dados do arquivo: ".$file."\n";
	    echo "ID: ".$artigos->art_id."\n";
	    echo "PID: ".$artigos->pid."\n";
	    echo "NUM: ".$artigos->art_num."\n";
	
	    $xml = new XMLtoArray(file_get_contents($file, "r"));
		
        $xml->getArray();
		
        unset($kwd);
	
        $open_article = false;
        $open_kwd = false;
        $cnt_kwd = false;
        if (isset($xml->arr)) {
            foreach ($xml->arr as $tag) {
                if ($tag["tag"] == "article-meta") {
                    if ($tag["type"] == "open")
                        $open_article = true;
                    else
                        $open_article = false;
                }
				
                if ($open_article) {
                    if ($tag["tag"] == "kwd-group") {
                        if ($tag["type"] == "open") {
                            $open_kwd = true;
                            $cnt_kwd = -1;
                        } else
                            $open_kwd = false;
                    }
                    
                    if ($open_kwd) {
                        if ($tag["tag"] == "kwd" && $tag["type"] == "complete") {
                            ++$cnt_kwd;
                            $kwd[$cnt_kwd]["palavra"] = $tag["value"];
                            $kwd[$cnt_kwd]["idioma"] = $tag["attributes"]["lng"];
                        }
                    }
                }
            }
        } else
            echo "Erro: Problemas com o XML: Verificar o problema.";
				
        /****************************************************/
        /******** ATUALIZANDO E INSERINDO OS DADOS  *********/
        /****************************************************/
        if (isset($kwd)) {
            foreach ($kwd as $item) {
                $palavras = new class_palavras_chave;
            
                $palavras->pch_palavra = $item["palavra"];
                $palavras->pch_idioma = $item["idioma"];
                $palavras->art_id = $artigos->art_id;
                
                if (!$palavras->insert($sql)) {
                    echo $palavras->error."\n";
                }
            }
        }
    }       
}

echo "[Fim] \n";

?>