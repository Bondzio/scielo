<?php

class class_artigos {
	
	var $num_row = 0;
	var $tot_row = 0;
	
	var $result;
	
	var $art_id;
	var $art_num;
	var $art_titulo_pt;
	var $art_titulo_fr;
	var $art_titulo_en;
	var $art_titulo_es;
	var $art_ano;
	var $art_mes;
	var $art_volume;
	var $art_url;
	var $art_resumo_pt;
	var $art_resumo_fr;
	var $art_resumo_en;
	var $art_resumo_es;
	var $art_fpage;
	var $art_lpage;
	var $art_tipo;
	var $art_issn;
	var $art_publisher_name;
	var $art_dt_download;
	
	var $pidrev_id;
	
	var $pid;
	
	var $error;
	
	function select($sql) {
		$query = "SELECT a.art_id,
						a.art_num,
						a.art_titulo_pt,
						a.art_titulo_fr,
						a.art_titulo_en,
						a.art_titulo_es,
						a.art_ano,
						a.art_mes,
						a.art_volume,
						a.art_url,
						a.art_resumo_pt,
						a.art_resumo_fr,
						a.art_resumo_en,
						a.art_resumo_es,
						a.art_fpage,
						a.art_lpage,
						a.art_tipo,
						a.art_issn,
						a.art_publisher_name,
						a.art_dt_download,
						a.pidrev_id,
						r.pid
							FROM artigos a 	JOIN pid_revistas r ON (a.pidrev_id = r.pidrev_id)
								WHERE 1 = 1 AND art_id = 7681";
		if ($this->art_id)
			$query .= " AND art_id = ".$this->art_id;
		if ($this->pidrev_id)
			$query .= " AND pidrev_id = ".$this->pidrev_id;
		if ($this->art_num)
			$query .= " AND art_num = '".$this->art_num."' ORDER BY a.art_id";
		$query .= ";";
		if (!$this->result = $sql->query($query))
			die("Error select: ".$sql->error);
		
		return true;
	}
	
	function insert($sql) {
		$query = "INSERT INTO artigos (
										art_num,
										art_url,
										pidrev_id) 
									VALUES ('".
										$this->art_num."', '"
										.$this->art_url."', "
										.$this->pidrev_id.");";
		if (!$sql->query($query)) {
			$this->error = "Error insert: ".$sql->error;
			return false;
		}
		
		return true;
	}
	
	function fetch() {
		
		if ($row = $this->result->fetch_array(MYSQLI_ASSOC)) {
			$this->art_id = $row["art_id"];
			$this->art_num = $row["art_num"];
			$this->art_titulo_pt = $row["art_titulo_pt"];
			$this->art_titulo_fr = $row["art_titulo_fr"];
			$this->art_titulo_en = $row["art_titulo_en"];
			$this->art_titulo_es = $row["art_titulo_es"];
			$this->art_ano = $row["art_ano"];
			$this->art_mes = $row["art_mes"];
			$this->art_volume = $row["art_volume"];
			$this->art_url = $row["art_url"];
			$this->art_resumo_pt = $row["art_resumo_pt"];
			$this->art_resumo_fr = $row["art_resumo_fr"];
			$this->art_resumo_en = $row["art_resumo_en"];
			$this->art_resumo_es = $row["art_resumo_es"];
			$this->art_fpage = $row["art_fpage"];
			$this->art_lpage = $row["art_lpage"];
			$this->art_tipo = $row["art_tipo"];
			$this->art_issn = $row["art_issn"];
			$this->art_publisher_name = $row["art_publisher_name"];
			$this->art_dt_download = $row["art_dt_download"];
			
			$this->pidrev_id = $row["pidrev_id"];
			$this->pid = $row["pid"];
			
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
	
	function update2($sql) {
		
		if (!is_numeric($this->art_id))
			die("O cуdigo para alteraзгo й invбlido. Classe: class_pid_revistas.");
		
		$query = "UPDATE artigos SET ";
		if ($this->art_dt_download)
			$query .= " art_dt_download = '".$this->art_dt_download."'";
		else
			die("Problema com a data de download do arquivo");
		
		$query .= " WHERE art_id = ".$this->art_id.";";
		
		if (!$sql->query($query))
			die("Error update: ".$sql->error);
			
		return true;
	}
	
	
	function update($sql) {
		if (!is_numeric($this->art_id))
			die("O cуdigo para alteraзгo й invбlido. Classe: class_pid_revistas.");
		
		$query = "UPDATE artigos SET ";
		if ($this->art_titulo_pt)
			$query .= " art_titulo_pt = '".addslashes($this->art_titulo_pt)."', ";
		if ($this->art_titulo_fr)
			$query .= " art_titulo_fr = '".addslashes($this->art_titulo_fr)."', ";
		if ($this->art_titulo_en)
			$query .= " art_titulo_en = '".addslashes($this->art_titulo_en)."', ";
		if ($this->art_titulo_es)
			$query .= " art_titulo_es = '".addslashes($this->art_titulo_es)."', ";
		if ($this->art_ano)
			$query .= " art_ano = '".addslashes($this->art_ano)."', ";
		if ($this->art_mes)
			$query .= " art_mes = '".addslashes($this->art_mes)."', ";
		if ($this->art_volume)
			$query .= " art_volume = '".addslashes($this->art_volume)."', ";
		if ($this->art_resumo_pt)
			$query .= " art_resumo_pt = '".addslashes($this->art_resumo_pt)."', ";
		if ($this->art_resumo_fr)
			$query .= " art_resumo_fr = '".addslashes($this->art_resumo_fr)."', ";
		if ($this->art_resumo_en)
			$query .= " art_resumo_en = '".addslashes($this->art_resumo_en)."', ";
		if ($this->art_resumo_es)
			$query .= " art_resumo_es = '".addslashes($this->art_resumo_es)."', ";
		if ($this->art_fpage)
			$query .= " art_fpage = '".addslashes($this->art_fpage)."', ";
		if ($this->art_lpage)
			$query .= " art_lpage = '".addslashes($this->art_lpage)."', ";
		if ($this->art_tipo)
			$query .= " art_tipo = '".addslashes($this->art_tipo)."', ";
		if ($this->art_issn)
			$query .= " art_issn = '".addslashes($this->art_issn)."', ";
		if ($this->art_publisher_name)
			$query .= " art_publisher_name = '".addslashes($this->art_publisher_name)."', ";
		
		$query = substr($query, 0, -2);
		$query .= " WHERE art_id = ".$this->art_id.";";
		
		if (!$sql->query($query))
			die("Error update: ".$sql->error);
			
		return true;
	}
	
}

?>