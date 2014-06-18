<?php

class class_artigos {
	
	var $num_row = 0;
	var $tot_row = 0;
	
	var $result;
	
	var $art_id;
	var $art_num;
	var $art_url;
	var $art_titulo_pt;
	var $art_titulo_fr;
	var $art_titulo_en;
	var $art_titulo_es;
	var $art_ano;
	var $art_resumo_pt;
	var $art_resumo_fr;
	var $art_resumo_en;
	var $art_resumo_es;
	var $art_publisher_name;
	
	var $pid;
	
	var $rev_nome;
	
	var $error;
	
	function select($sql) {
		$query = "SELECT  	a.art_id,
							pr.pid,
							a.art_num,
							a.art_url,
							a.art_titulo_pt,
							a.art_titulo_fr,
							a.art_titulo_en,
							a.art_titulo_es,
							r.rev_nome,
							a.art_ano,
							a.art_resumo_pt,
							a.art_resumo_fr,
							a.art_resumo_en,
							a.art_resumo_es,
							a.art_publisher_name
					FROM artigos a 	JOIN pid_revistas pr ON (a.pidrev_id = pr.pidrev_id) 
									JOIN revistas r ON (pr.rev_id = r.rev_id) 
						WHERE 1 = 1";
		if ($this->art_id)
			$query .= " AND art_id = ".$this->art_id;
		$query .= " ORDER BY a.art_id LIMIT 1;";
		
		if (!$this->result = $sql->query($query))
			die("Error select: ".$sql->error);
		
		return true;
	}
	
	function fetch() {
		
		if ($row = $this->result->fetch_array(MYSQLI_ASSOC)) {
			$this->art_id = $row["art_id"];
			$this->art_num = $row["art_num"];
			$this->art_url = $row["art_url"];
			$this->art_titulo_pt = $row["art_titulo_pt"];
			$this->art_titulo_fr = $row["art_titulo_fr"];
			$this->art_titulo_en = $row["art_titulo_en"];
			$this->art_titulo_es = $row["art_titulo_es"];
			$this->art_ano = $row["art_ano"];
			$this->art_resumo_pt = $row["art_resumo_pt"];
			$this->art_resumo_fr = $row["art_resumo_fr"];
			$this->art_resumo_en = $row["art_resumo_en"];
			$this->art_resumo_es = $row["art_resumo_es"];
			$this->art_publisher_name = $row["art_publisher_name"];
			$this->pid = $row["pid"];
			$this->rev_nome = $row["rev_nome"];
			
			$this->num_row += 1;
			if ($this->num_row == 1)
				$this->tot_row = $this->result->num_rows;
			
			return true;
		} else
			return false;
	}
	
}

?>