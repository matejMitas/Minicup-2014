<?php
/**
* 
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
		$this->nazevTymu = $result[0];	
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
			} else {
				$stav = "Odehraje se $den v $cas";
				$oddelovac = "";
			}
			
			$return .= <<<HTML
\n			<tr>
				<td>{$zapas[0]}</td>
				<td>{$zapas[1]}{$oddelovac}{$zapas[2]}</td>
				<td>{$zapas[3]}</td>
				<td>$stav</td>
			</tr>
HTML;
		}
		return "<table>\n$return\n</table>";
	}
}