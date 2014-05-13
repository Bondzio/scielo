<?php

class class_pid_revistas {
	
	var $num_row = 0;
	var $tot_row = 0;
	
	var $result;
	
	var $pidrev_id;
	var $rev_id;
	var $pid;
	
	function select($sql) {
		$query = "SELECT pd.pidrev_id,
						pd.rev_id,
						pd.pid
							FROM pid_revistas pd
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
		$query = "INSERT INTO pid_revistas (rev_id, pid) VALUES (".$this->rev_id.", '".$this->pid."')";
		
		if (!$sql->query($query))
			die("Error insert: ".$sql->error);
		
		return true;
	}
	
	function fetch() {
		
		if ($row = $this->result->fetch_array(MYSQLI_ASSOC)) {
			$this->pidrev_id = $row["pidrev_id"];
			$this->rev_id = $row["rev_id"];
			$this->pid = $row["pid"];
			
			$this->num_row += 1;
			if ($this->num_row == 1)
				$this->tot_row = $this->result->num_rows;
			
			return true;
		} else
			return false;
	}
	
}

?>