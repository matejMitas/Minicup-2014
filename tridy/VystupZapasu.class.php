<?php
class VystupZapasu {
    /**
     * ziskejOdehraneZapasy
     * 
     * @param time $datum datum pro vygenerování zápasů
     *
     * @access public
     *
     * @return string html výstup zápasů
     */
	public function ziskejOdehraneZapasy($datum){
		$sql=<<<SQL
			SELECT 
			b.`jmeno`,c.`jmeno`,a.`SCR_domaci`,`SCR_hoste`
			FROM `2014_zapasy_mladsi` a
			JOIN `2014_tymy_mladsi` b ON a.`ID_domaci`=b.`ID_teamu`
			JOIN `2014_tymy_mladsi` c ON a.`ID_hoste`=c.`ID_teamu`
			WHERE a.`odehrano`=1 
SQL;
		$aZapasy=dbWrapper::dotaz($sql,Array("min" => strtotime($datum),"max" => strtotime($datum)+60*60*24))->fetchAll();
		$return="";
		foreach ($aZapasy as $klic => $radekZapasu) {
			$return.=<<<ZAPAS
		<h4>{$radekZapasu[0]} - {$radekZapasu[1]}</h4>
		<p><i>{$radekZapasu[2]}:{$radekZapasu[3]}</i></p>
ZAPAS;
		}		
		return $return;
		}
	}