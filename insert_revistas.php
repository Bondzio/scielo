<?php

require "common/config.php";
require "common/function/retirarAcento.php";
require "common/bizobj/class_revistas.php";

echo "[Inicio] \n";

echo "Verificando se há novas revistas adicionadas. Lendo: 'input/revistas.csv' \n\n";

$row = 1;
if (($handle = fopen("input/revistas.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        echo trim($data[0])." - ".trim($data[1]).'...';
       
        $rev = new class_revistas;
				
        $rev->rev_nome = trim(utf8_decode($data[1]));
        $rev->rev_url = trim($data[2]);
        $rev->insert($sql);

        echo "[OK] \n\n";
    }
    fclose($handle);
}


?>