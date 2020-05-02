<?php

class class_revistas {
	
	var $num_row = 0;
	var $tot_row = 0;
	
	var $result;
	
	var $rev_id;
	var $rev_nome;
	var $rev_url;
	var $rev_dt_download;
	
	function select($sql) {
		$query = "SELECT r.rev_id,
						r.rev_nome,
						r.rev_url,
						r.rev_dt_download
							FROM revistas r
								WHERE 1 = 1";
		if ($this->rev_id)
			$query .= " AND rev_id = ".$this->rev_id;
		if ($this->rev_nome)
			$query .= " AND rev_nome = '".$this->rev_nome."'";
		if ($this->rev_dt_download)
			$query .= " AND rev_dt_download = '".$this->rev_dt_download."'";
		$query .= " ORDER BY r.rev_id;";
		
		if (!$this->result = $sql->query($query))
			die("Error select: ".$sql->error);
		
		return true;
	}
	
	function update($sql) {
		
		if (!is_numeric($this->rev_id))
			die("O codigo para alteracao eh invalido. Classe: class_revistas.");
		
		$query = "UPDATE revistas SET ";
		if ($this->rev_dt_download)
			$query .= " rev_dt_download = '".$this->rev_dt_download."'";
		else
			die("Problema com a data de download do arquivo");
		
		$query .= " WHERE rev_id = ".$this->rev_id.";";
		
		if (!$sql->query($query))
			die("Error update: ".$sql->error);
			
		return true;
	}
	
	function insert($sql) {
		$query = "INSERT INTO revistas (rev_nome,
										rev_url) 
									VALUES ('".
										$this->rev_nome."', '"
										.$this->rev_url."');";
		if (!$sql->query($query)) {
			$this->error = "Error insert: ".$sql->error;
			return false;
		}
		
		return true;
	}

	function fetch() {
		
		if ($row = $this->result->fetch_array(MYSQLI_ASSOC)) {
			$this->rev_id = $row["rev_id"];
			$this->rev_nome = $row["rev_nome"];
			$this->rev_url = $row["rev_url"];
			$this->rev_dt_download = $row["rev_dt_download"];
			
			$this->num_row += 1;
			if ($this->num_row == 1)
				$this->tot_row = $this->result->num_rows;
			
			return true;
		} else
			return false;
	}
	
}

?>