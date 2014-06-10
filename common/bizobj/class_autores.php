<?php

class class_autores {
	
	var $num_row = 0;
	var $tot_row = 0;
	
	var $aut_id;
	var $aut_givennames;
	var $aut_surname;
	var $aut_urllattes;
	var $aut_instituicao;
	
	var $art_id;
	
	function select($sql) {
		$query = "SELECT a.aut_id,
						a.aut_givennames,
						a.aut_surname,
						a.aut_urllattes,
						a.aut_instituicao,
						a.art_id
							FROM autores a
								WHERE 1 = 1";
		if ($this->aut_id)
			$query .= " AND aut_id = ".$this->aut_id;
		if ($this->aut_givennames)
			$query .= " AND aut_givennames = '".$this->aut_givennames."'";
		if ($this->aut_surname)
			$query .= " AND aut_surname = '".$this->aut_surname."'";
		$query .= " ORDER BY a.aut_id;";
		
		if (!$this->result = $sql->query($query))
			die("Error select: ".$sql->error);
		
		return true;
	}
	
	function insert($sql) {
		$query = "INSERT INTO autores (
										aut_givennames,
										aut_surname,
										aut_instituicao, 
										art_id) 
									VALUES ('".
										addslashes(utf8_decode($this->aut_givennames))."', '"
										.addslashes(utf8_decode($this->aut_surname))."', '"
										.addslashes(utf8_decode($this->aut_instituicao))."', "
										.$this->art_id.");";
		if (!$sql->query($query)) {
			$this->error = "Error insert: ".$sql->error." | QUERY: ".$query;
			return false;
		} 
		
		return true;
	}
	
	function fetch() {
		if ($row = $this->result->fetch_array(MYSQLI_ASSOC)) {
			$this->aut_id = $row["aut_id"];
			$this->aut_givennames = $row["aut_givennames"];
			$this->aut_surname = $row["aut_surname"];
			$this->aut_urllattes = $row["aut_urllattes"];
			$this->aut_instituicao = $row["aut_instituicao"];
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