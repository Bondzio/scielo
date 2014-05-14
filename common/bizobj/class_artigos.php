<?php

class class_artigos {
	
	var $num_row = 0;
	var $tot_row = 0;
	
	var $result;
	
	var $pidrev_id;
	var $rev_id;
	var $pid;
	var $pidrev_dt_download;
	var $rev_nome;

	var $error;
	
	function select($sql) {
		$query = "SELECT pd.pidrev_id,
						pd.rev_id,
						pd.pid,
						pd.pidrev_dt_download,
						r.rev_nome
							FROM pid_revistas pd JOIN revistas r ON (pd.rev_id = r.rev_id)
								WHERE 1 = 1";
		if ($this->pidrev_id)
			$query .= " AND pidrev_id = ".$this->pidrev_id;
		if ($this->rev_id)
			$query .= " AND rev_id = ".$this->rev_id;
		if ($this->pid)
			$query .= " AND pid = '".$this->pid."' ORDER BY pd.pidrev_id";
		$query .= ";";
		
		if (!$this->result = $sql->query($query))
			die("Error select: ".$sql->error);
		
		return true;
	}
	
	function insert($sql) {
		$query = "INSERT INTO pid_revistas (
										rev_id, 
										pid) 
									VALUES (".
										$this->rev_id.", '"
										.$this->pid."');";
		if (!$sql->query($query)) {
			$this->error = "Error insert: ".$sql->error;
			return false;
		}
		
		return true;
	}
	
	function fetch() {
		
		if ($row = $this->result->fetch_array(MYSQLI_ASSOC)) {
			$this->pidrev_id = $row["pidrev_id"];
			$this->rev_id = $row["rev_id"];
			$this->pid = $row["pid"];
			$this->pidrev_dt_download = $row["pidrev_dt_download"];
			$this->rev_nome = $row["rev_nome"];
			
			$this->num_row += 1;
			if ($this->num_row == 1)
				$this->tot_row = $this->result->num_rows;
			
			return true;
		} else
			return false;
	}
	
	function begin(&$sql){
		if (!$sql->query("BEGIN;"))
			die("Error BEGIN: ".$sql->error);
		return true;
	}
	
	function commit(&$sql) {
		if (!$sql->query("COMMIT;"))
			die("Error COMMIT: ".$sql->error);
		return true;
	}
	
	function rollback(&$sql) {
		if (!$sql->query("ROLLBACK;"))
			die("Error ROLLBACK: ".$sql->error);
		return true;
	}
	
	function update($sql) {
		
		if (!is_numeric($this->pidrev_id))
			die("O cуdigo para alteraзгo й invбlido. Classe: class_pid_revistas.");
		
		$query = "UPDATE pid_revistas SET ";
		if ($this->pidrev_dt_download)
			$query .= " pidrev_dt_download = '".$this->pidrev_dt_download."'";
		else
			die("Problema com a data de download do arquivo");
		
		$query .= " WHERE pidrev_id = ".$this->pidrev_id.";";
		
		if (!$sql->query($query))
			die("Error update: ".$sql->error);
			
		return true;
	}
	
}

?>