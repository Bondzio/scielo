<?php

require "common/class/class-XMLtoArray.php";

$dir    	= "./files/artigos/xml/";
$output["pt"] 	= "./files/resumos/pt/";
$output["en"] 	= "./files/resumos/en/";
$output["fr"] 	= "./files/resumos/fr/";

$files 	= scandir($dir);
$total = sizeof($files)-1;
for ($i = 2; $i < $total; ++$i) {
	$file = $dir.$files[$i];
	
	$xml = new XMLtoArray(file_get_contents($file, "r"));	
    $xml->getArray();
	
	unset($item);
	$open_abstract = false;
	if (isset($xml->arr)) {
		foreach ($xml->arr as $tag) {
			if ($tag["tag"] == "abstract" && $tag["type"] == "open") {
				if ($tag["attributes"]["xml:lang"]) {
					$language_abstract = $tag["attributes"]["xml:lang"];
				} else {
					$language_abstract = "pt";
				}
				
				if (!isset($tag["value"])) {
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
		if (isset($item)) {
			create_file($item, $files[$i], $output);
		}
	}
}

function create_file($item, $file, $path) {
	if (isset($item["abstract"])) {
		while (list($k, $v) = each($item["abstract"])) {
			if ($k == "en" || $k == "pt" || $k == "fr") {
				$tmp = $path[$k].substr($file, 0, -4)."-abstract-".$k.".txt";
				$handle = fopen($tmp, 'w+');
				if ($handle) {
					fwrite($handle, $v);
				}
				fclose($handle);
			}
		}
	}
}

?>