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
		foreach ($xml->arr as $tag) {
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
						echo "Mês: ".$tag["value"]."\n";
					}
					if ($tag["tag"] == "year") {
						echo "Ano: ".$tag["value"]."\n";
					}
				}
				
				if ($tag["tag"] == "volume") {
					echo "Volume: ".$tag["value"]."\n";					
				}
				
				if ($tag["tag"] == "numero") {
					echo "Numero: ".$tag["value"]."\n";					
				}
				
				if ($tag["tag"] == "fpage") {
					echo "Primeira Pagina: ".$tag["value"]."\n";					
				}
				
				if ($tag["tag"] == "abstract") {
					print_r($tag);
					echo "Abstract: ".$tag["value"]."\n";					
				}
			}
		}
	//}
//}

?>