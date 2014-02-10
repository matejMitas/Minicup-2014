<?php
/**
 * Třída k získání kompletního detailu o jednom týmu
 */
class DetailTymu {
	/**
	 * kategorie daneho tymu
	 * @var string
	 */
	private $kategorie;

	/**
	 * id tymu pro generovani tohoto detailu
	 * @var integer
	 */
	private $idTymu;

	/**
	 * nazev tymu pro generovani tohoto detailu
	 * @var string
	 */
	private $nazevTymu;

	/**
	 * Konstruktor pro daný detail
	 * @param string $kategorie 'mladsi','starsi' pro danou kategorii tymu
	 * @param int $idTymu    	id tymu pro generovani detailu
	 */
	public function __construct($kategorie,$idTymu) {
		$this->kategorie = $kategorie;
		$this->idTymu = $idTymu;

		$SQL = <<<SQL
			SELECT `jmeno` 
			FROM `2014_tymy_{$this->kategorie}` 
			WHERE `id_teamu`=? 
SQL;
		$result = dbWrapper::dotaz($SQL,Array($idTymu))->fetchAll();
		$this->nazevTymu = $result[0][0];	
	}



	/**
	 * Ziska zapasy zvoleneho typu
	 * @return string html tabulka odehranych zapasu
	 */		
	public function ziskejOdehraneZapasy(){
		$return = "";
		$SQL = <<<SQL
		SELECT 
			b.`jmeno`, a.`SCR_domaci`, a.`SCR_hoste`, c.`jmeno`, 
			UNIX_TIMESTAMP(a.`cas_odehrani`), a.`odehrano` 
		FROM `2014_zapasy_{$this->kategorie}` a
		INNER JOIN `2014_tymy_{$this->kategorie}` b ON a.`ID_domaci` = b.`ID_teamu`
		INNER JOIN `2014_tymy_{$this->kategorie}` c ON a.`ID_hoste` = c.`ID_teamu`
		WHERE a.`ID_domaci`= :id OR a.`ID_hoste`= :id
SQL;
		$poleZapasu = dbWrapper::dotaz($SQL, Array("id" => $this->idTymu))->fetchAll();
		foreach ($poleZapasu as $klic => $zapas) {
			$cas = date("H:i:s",$zapas[4]);
			$den = date("j. n.",$zapas[4]);
			if ($zapas[5] == 1) {
				$stav = "Odehráno $den v $cas";
				$oddelovac =":";
				if ($zapas[1]>$zapas[2]) {
					$zapas[0] = "<b>{$zapas[0]}</b>";
				} elseif($zapas[1]<$zapas[2]) {
					$zapas[3] = "<b>{$zapas[3]}</b>";
				}
			} else {
				$stav = "Odehraje se $den v $cas";
				$oddelovac = "";
			}
			$return .= <<<HTML
\n			<tr>
				<td>{$zapas[0]}</td>
				<td>{$zapas[1]}$oddelovac{$zapas[2]}</td>
				<td>{$zapas[3]}</td>
				<td>$stav</td>
				<td></td>
			</tr>
HTML;
		}
		return "<table>\n$return\n</table>";
	}

	/**
	 * vrátí název týmu
	 * @return string název týmu tohoto detailu
	 */
	public function ziskejNazevTymu() {
		return $this->nazevTymu;
	}


    /**
     * získá aktuální pořadí, počet bodů a skóre jednoho týmu
     * 
     * @access public
     *
     * @return string html výstup
     */
	public function ziskejPoradiSkore() {
		$SQL = <<<SQL
		SELECT `poradi`, `body`, ifnull(sum(D),0) dane, ifnull(sum(H),0) dostane, `id_teamu`
			FROM (
				SELECT sum(`SCR_domaci`) D, sum(`SCR_hoste`) H, `ID_domaci` Team
					FROM `2014_zapasy_{$this->kategorie}`
					GROUP BY Team
				UNION
				SELECT sum(`SCR_hoste`) D, sum(`SCR_domaci`) H, `ID_hoste` Team
					FROM `2014_zapasy_{$this->kategorie}`
					GROUP BY Team
			) CLK
			RIGHT JOIN 2014_tymy_{$this->kategorie} TM ON CLK.Team = TM.id_teamu
			WHERE `id_teamu` = :id
			GROUP BY `id_teamu`
			ORDER BY `poradi` ASC
SQL;
		$result = dbWrapper::dotaz($SQL,Array("id" => $this->idTymu))->fetch();
		if ($result["body"] == 0) {
			$body = "{$result["body"]} bodů";
		} elseif ($result["body"] == 1) {
        	$body = "{$result["body"]} bod";
    	} elseif ($result["body"] <= 4) {
        	$body = "{$result["body"]} body";
    	} else {
        	$body = "{$result["body"]} bodů";
    	}

		$return = <<<HTML
		{$result["poradi"]}. místo, $body, skóre {$result["dane"]}:{$result["dostane"]}
HTML;
		return $return;
	}

    /**
     * uspesnost = získané body/všechny potenciálně získané body ze všech zápasů
     * 
     * @access public
     *
     * @return int uspesnost
     */
	public function ziskejProcentualniUspech(){
		$SQL = <<<SQL
			SELECT b.`body`/(COUNT(a.`ID_zapasu`)*2)
			FROM `2014_zapasy_{$this->kategorie}` a
			INNER JOIN `2014_tymy_{$this->kategorie}` b ON b.`ID_teamu` = :id
			WHERE a.`ID_domaci` = :id OR a.`ID_hoste` = :id
SQL;
		$uspesnost = dbWrapper::dotaz($SQL,array("id" => $this->idTymu))->fetchAll()[0][0];
		return round($uspesnost*100);
	}
}