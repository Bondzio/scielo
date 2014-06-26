<?php

class class_palavras_chave {
	
	var $num_row = 0;
	var $tot_row = 0;
	
	var $pch_id;
	var $pch_palavra;
	var $pch_idioma;
	
	var $art_id;
	
	function select($sql) {
            $query = "SELECT p.pch_id,
                            p.pch_palavra,
                            p.pch_idioma,
                            p.art_id
                                FROM palavras_chave p
                                    WHERE 1 = 1";
            if ($this->pch_id)
                    $query .= " AND pch_id = ".$this->pch_id;
            if ($this->art_id)
                    $query .= " AND art_id = ".$this->art_id;
            if ($this->pch_palavra)
                    $query .= " AND pch_palavra = '".$this->pch_palavra."'";
            if ($this->pch_idioma)
                    $query .= " AND pch_idioma = '".$this->pch_idioma."'";
            $query .= " ORDER BY p.pch_id;";
            if (!$this->result = $sql->query($query))
                    die("Error select: ".$sql->error);

            return true;
	}
	
	function insert($sql) {
            $query = "INSERT INTO palavras_chave (
                                pch_palavra,
                                pch_idioma,
                                art_id) 
                            VALUES ('".
                                addslashes(utf8_decode($this->pch_palavra))."', '"
                                .addslashes(utf8_decode($this->pch_idioma))."', "
                                .$this->art_id.");";
            if (!$sql->query($query)) {
                $this->error = "Error insert: ".$sql->error." | QUERY: ".$query;
                return false;
            } 

            return true;
	}
	
	function fetch() {
            if ($row = $this->result->fetch_array(MYSQLI_ASSOC)) {
                $this->pch_id = $row["pch_id"];
                $this->pch_palavra = $row["pch_palavra"];
                $this->pch_idioma = $row["pch_idioma"];
                $this->art_id = $row["art_id"];
			
                $this->num_row += 1;
                if ($this->num_row == 1)
                    $this->tot_row = $this->result->num_rows;
			
                return true;
            } else
                return false;
	}
	
}

?>