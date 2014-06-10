<?php

class class_bibliografia {
	
	var $num_row = 0;
	var $tot_row = 0;
	
	var $bib_id;
	var $bib_titulo;
	var $bib_tipo;
	var $bib_pub_name;
	var $bib_pub_loc;
	var $bib_ano;
	var $bib_paginas;
	var $bib_numero;
	var $bib_volume;
	var $bib_issue;
	var $bib_source;
	
	var $art_id;
	
	function select($sql) {
		$query = "SELECT b.bib_id,
						b.bib_titulo,
						b.bib_tipo,
						b.bib_pub_name,
						b.bib_pub_loc,
						b.bib_ano,
						b.bib_paginas,
						b.bib_numero,
						b.bib_volume,
						b.bib_issue,
						b.bib_source,
						b.art_id
							FROM bibliografia b
								WHERE 1 = 1";
		if ($this->bib_id)
			$query .= " AND bib_id = ".$this->bib_id;
		if ($this->bib_titulo)
			$query .= " AND bib_titulo = '".$this->bib_titulo."'";
		if ($this->bib_pub_name)
			$query .= " AND bib_pub_name = '".$this->bib_pub_name."'";
		$query .= " ORDER BY b.bib_id;";
		
		if (!$this->result = $sql->query($query))
			die("Error select: ".$sql->error);
		
		return true;
	}
	
	function insert($sql) {
		$query = "INSERT INTO bibliografia (
										bib_titulo,
										bib_tipo,
										bib_pub_name,
										bib_pub_loc,
										bib_ano,
										bib_paginas,
										bib_numero,
										bib_volume,
										bib_issue,
										bib_source,
										art_id) 
									VALUES ('".
										addslashes(utf8_decode($this->bib_titulo))."', '"
										.addslashes(utf8_decode($this->bib_tipo))."', '"
										.addslashes(utf8_decode($this->bib_pub_name))."', '"
										.addslashes(utf8_decode($this->bib_pub_loc))."', '"
										.addslashes(utf8_decode($this->bib_ano))."', '"
										.addslashes(utf8_decode($this->bib_paginas))."', '"
										.addslashes(utf8_decode($this->bib_numero))."', '"
										.addslashes(utf8_decode($this->bib_volume))."', '"
										.addslashes(utf8_decode($this->bib_issue))."', '"
										.addslashes(utf8_decode($this->bib_source))."', "
										.$this->art_id.");";
		
		if (!$sql->query($query)) {
			$this->error = "Error update: ".$sql->error. " | QUERY: ".$query;
			return false;
		} else {
			$query = "SELECT MAX(bib_id) as bib_id FROM bibliografia";
			if ($result = $sql->query($query)) {
				if ($row = $result->fetch_array(MYSQLI_ASSOC)) {
					$this->bib_id = $row["bib_id"];
				}
			}
			return true;
		}
	}
	
	function fetch() {
		if ($row = $this->result->fetch_array(MYSQLI_ASSOC)) {
			$this->bib_id = $row["bib_id"];
			$this->bib_titulo = $row["bib_titulo"];
			$this->bib_tipo = $row["bib_tipo"];
			$this->bib_pub_name = $row["bib_pub_name"];
			$this->bib_pub_loc = $row["bib_pub_loc"];
			$this->bib_ano = $row["bib_ano"];
			$this->bib_paginas = $row["bib_paginas"];
			$this->bib_numero = $row["bib_numero"];
			$this->bib_volume = $row["bib_volume"];
			$this->bib_issue = $row["bib_issue"];
			$this->bib_source = $row["bib_source"];
			
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