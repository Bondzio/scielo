<?php

class class_artigos {
	
	var $num_row = 0;
	var $tot_row = 0;
	
	var $result;
	
	var $art_id;
	var $art_num;
	var $art_titulo;
	var $art_ano;
	var $art_volume;
	var $art_paginas;
	var $art_url;
	var $art_resumo_br;
	var $art_resumo_fr;
	var $art_resumo_en;
	var $art_resumo_es;
	
	var $pidrev_id;
	
	var $tart_id;
	var $tart_nome;

	var $error;
	
	function select($sql) {
		$query = "SELECT a.art_id,
						a.art_num,
						a.art_titulo,
						a.art_ano,
						a.art_volume,
						a.art_paginas,
						a.art_url,
						a.art_resumo_br,
						a.art_resumo_fr,
						a.art_resumo_en,
						a.art_resumo_es,
						a.pidrev_id,
						t.tart_id
						t.tart_nome
							FROM artigos a LEFT JOIN tipo_artigo t ON (a.tart_id = t.tart_id)
								WHERE 1 = 1";
		if ($this->art_id)
			$query .= " AND art_id = ".$this->art_id;
		if ($this->pidrev_id)
			$query .= " AND pidrev_id = ".$this->pidrev_id;
		if ($this->tart_id)
			$query .= " AND tart_id = ".$this->tart_id;
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
			$this->art_titulo = $row["art_titulo"];
			$this->art_ano = $row["art_ano"];
			$this->art_volume = $row["art_volume"];
			$this->art_paginas = $row["art_paginas"];
			$this->art_url = $row["art_url"];
			$this->art_resumo_br = $row["art_resumo_br"];
			$this->art_resumo_fr = $row["art_resumo_fr"];
			$this->art_resumo_en = $row["art_resumo_en"];
			$this->art_resumo_es = $row["art_resumo_es"];
			
			$this->pidrev_id = $row["pidrev_id"];
			$this->tart_id = $row["tart_id"];
			$this->tart_nome = $row["tart_nome"];
			
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