<?php

class XMLtoArray {

	var $xml;
	var $arr;
	
	var $error;
	
	function XMLtoArray($xml) {
		$this->xml = $xml;
	}
	
	function getArray() {
		
		if (trim($this->xml) == '') {
			$this->error = 'O XML está em branco';
			return false;
		}
		
		$parser = xml_parser_create(); 
		
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parse_into_struct($parser, $this->xml, $values, $index);
		xml_parser_free($parser);
		
		$this->arr = $values;
		
		return true;
	}
	
}