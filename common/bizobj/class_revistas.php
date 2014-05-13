<?php

class class_revistas {
	
	var $num_row = 0;
	var $tot_row = 0;
	
	var $result;
	
	var $rev_id;
	var $rev_nome;
	var $rev_url;
		
	function select($sql) {
		$query = "SELECT r.rev_id,
						r.rev_nome,
						r.rev_url
							FROM revistas r
								WHERE 1 = 1";
		if ($this->rev_id)
			$query .= " AND rev_id = ".$this->rev_id;
		if ($this->rev_nome)
			$query .= " AND rev_nome = '".$this->rev_nome."' ORDER BY r.rev_id";
		$query .= ";";
		
		if (!$this->result = $sql->query($query))
			die("Error select: ".$sql->error);
		
		return true;
	}
	
	function fetch() {
		
		if ($row = $this->result->fetch_array(MYSQLI_ASSOC)) {
			$this->rev_id = $row["rev_id"];
			$this->rev_nome = $row["rev_nome"];
			$this->rev_url = $row["rev_url"];
			
			$this->num_row += 1;
			if ($this->num_row == 1)
				$this->tot_row = $this->result->num_rows;
			
			return true;
		} else
			return false;
	}
	
}

?>