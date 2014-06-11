<?php

class class_bibliografia_autores {
	
	var $num_row = 0;
	var $tot_row = 0;
	
	var $abi_id;
	var $abi_givennames;
	var $abi_surname;
	var $bib_id;
	
	var $art_id;
	
	function select($sql) {
		$query = "SELECT a.abi_id, a.abi_givennames, a.abi_surname, a.bib_id 
						FROM bibliografia b JOIN autores_bibliografia a ON (a.bib_id = b.bib_id) 
								WHERE 1 = 1";
		if ($this->art_id)
			$query .= " AND b.art_id = ".$this->art_id;
		$query .= " ORDER BY abi_id;";
		
		if (!$this->result = $sql->query($query))
			die("Error select: ".$sql->error);
		
		return true;
	}
	
	function fetch() {
		if ($row = $this->result->fetch_array(MYSQLI_ASSOC)) {
			$this->abi_id = $row["abi_id"];
			$this->abi_givennames = $row["abi_givennames"];
			$this->abi_surname = $row["abi_surname"];
			$this->bib_id = $row["bib_id"];
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