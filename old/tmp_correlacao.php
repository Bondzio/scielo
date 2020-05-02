<?php
    
$html_path = "files/artigos/html/";
$xml_path = "files/artigos/xml/";

$out_file = "files/artigos/".date("Y-m-d")."_correlacao.csv";
    
$files = scandir($html_path);
foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) == "html") {
        $aux_file = trim(substr($file, 0, -5));
        //$aux_pid = explode("_", $aux_file);
        
        $handle = @fopen($html_path.$file, "r");
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);

                //$pattern = "/<a id=\"thumbnail\" class=\"(.*)\" aria-hidden=\"(.*)\" tabindex=\"(.*)\" href=\"(https:\/\/www\.youtube\.com\/watch\?v=(.*))\">/";
                //<a href="http://www.scielo.br/scieloOrg/php/articleXML.php?pid=S0104-44782012000100015&amp;lang=pt" rel="nofollow" target="xml"><img src="/img/pt/iconXMLDocument.gif">Artigo em XML</a>
                //$pattern = "/<a href=\"(https:\/\/www\.youtube\.com\/watch\?v=(.*))\">/";
                //$pattern = "/<a href=\"(.*)\" rel=\"nofollow\" target=\"xml\">/";
                $pattern = "/http:\/\/www\.scielo\.br\/scieloOrg\/php\/articleXML\.php\?pid=(.*)&amp;lang=pt/";
                
                if (preg_match($pattern, $buffer, $xml)) {
                    echo $aux_file."; ".$xml[1]."\n";
                }
            }
        }
    }
}

?>