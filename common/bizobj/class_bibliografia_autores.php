<?php

class class_bibliografia_autores {
	
	var $num_row = 0;
	var $tot_row = 0;
	
	var $abi_id;
	var $abi_givennames;
	var $abi_surname;
	
	var $bib_id;
	
	function select($sql) {
		$query = "SELECT ab.abi_id,
						ab.abi_givennames,
						ab.abi_surname,
						ab.bib_id
							FROM autores_bibliografia ab
								WHERE 1 = 1";
		if ($this->abi_id)
			$query .= " AND abi_id = ".$this->abi_id;
		if ($this->abi_givennames)
			$query .= " AND abi_givennames = '".$this->abi_givennames."'";
		if ($this->abi_surname)
			$query .= " AND abi_surname = '".$this->abi_surname."'";
		$query .= " ORDER BY a.abi_id;";
		
		if (!$this->result = $sql->query($query))
			die("Error select: ".$sql->error);
		
		return true;
	}
	
	function insert($sql) {
		$query = "INSERT INTO autores_bibliografia (
										abi_givennames,
										abi_surname,
										bib_id) 
									VALUES ('".
										addslashes(utf8_decode($this->abi_givennames))."', '"
										.addslashes(utf8_decode($this->abi_surname))."', "
										.$this->bib_id.");";
		if (!$sql->query($query)) {
			$this->error = "Error insert: ".$sql->error;
			return false;
		}
		
		return true;
	}
	
	function fetch() {
		if ($row = $this->result->fetch_array(MYSQLI_ASSOC)) {
			$this->abi_id = $row["abi_id"];
			$this->abi_givennames = $row["abi_givennames"];
			$this->abi_surname = $row["abi_surname"];
			
			$this->bib_id = $row["bib_id"];
			
			$this->num_row += 1;
			if ($this->num_row == 1)
				$this->tot_row = $this->result->num_rows;
			
			return true;
		} else
			return false;
	}
	
}

?>