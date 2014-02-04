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
	 * Konstruktor pro dený detail
	 * @param string $kategorie 'mladsi','starsi' pro danou kategorii tymu
	 * @param int $idTymu    	id tymu pro generovani detailu
	 */
	function __construct($kategorie,$idTymu) {
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
	 * @return string      html tabulka odehranych zapasu
	 */		
	public function ziskejOdehraneZapasy(){
		$return = "";
		$SQL = <<<SQL
		SELECT 
			b.`jmeno`, a.`SCR_domaci`, a.`SCR_hoste`, c.`jmeno`, 
			UNIX_TIMESTAMP(a.`cas_odehrani`), a.`odehrano` 
		FROM `2014_zapasy_mladsi` a
		INNER JOIN `2014_tymy_mladsi` b ON a.`ID_domaci` = b.`ID_teamu`
		INNER JOIN `2014_tymy_mladsi` c ON a.`ID_hoste` = c.`ID_teamu`
		WHERE a.`ID_domaci`=:id OR a.`ID_hoste`=:id
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


	public function ziskejPoradiSkore() {
		$SQL = <<<SQL
		SELECT poradi,body,ifnull(sum(D),0) dane, ifnull(sum(H),0) dostane, id_teamu
			FROM (
				SELECT sum(SCR_domaci) D, sum(SCR_hoste) H, ID_domaci Team
					FROM 2014_zapasy_mladsi
					GROUP BY Team
				UNION
				SELECT sum(SCR_hoste) D, sum(SCR_domaci) H, ID_hoste Team
					FROM 2014_zapasy_mladsi
					GROUP BY Team
			) CLK
			RIGHT JOIN 2014_tymy_mladsi TM ON CLK.Team = TM.id_teamu
			WHERE id_teamu = :id
			GROUP BY id_teamu
			ORDER BY poradi ASC
SQL;
		$result = dbWrapper::dotaz($SQL,Array("id" => $this->idTymu))->fetch();
		$return = <<<HTML
{$result["poradi"]}. místo, {$result["body"]} bodů, skóre {$result["dane"]}:{$result["dostane"]}
HTML;
		return $return;



	}
}