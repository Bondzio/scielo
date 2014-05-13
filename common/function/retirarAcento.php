<?php
	function retirarAcento($string) {
		return strtr($string, "¥µְֱֲֳִֵֶַָֹÊֻּֽ־ֿ׀ׁׂ׃װױײ״ÙÚÛÜÝßאבגדהוזחטיךכלםמןנסעףפץצרשתûü‎ÿ", "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy"); 
	}
?>