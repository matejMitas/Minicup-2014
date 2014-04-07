<?php
class VystupVysledku {

    /**
     * kategorie pro generovani tohoto vystupu
     * @var string
     * @access private
     */
	private $kategorie;


    /**
     * ziskejOdehraneZapasy
     * @param time $datum datum pro vygenerování zápasů
     * @access public
     * @return array[string] vrátí raw data všech zápasů
     */
    
    function __construct($kategorie) {
    	$this->kategorie = $kategorie;
    }
	public function ziskejOdehraneZapasy($datum) {
		$sql=<<<SQL
			SELECT 
			b.`jmeno`,c.`jmeno`,a.`SCR_domaci`,`SCR_hoste`
			FROM `2014_zapasy_{$this->kategorie}` a
			JOIN `2014_tymy_{$this->kategorie}` b ON a.`ID_domaci`=b.`ID_teamu`
			JOIN `2014_tymy_{$this->kategorie}` c ON a.`ID_hoste`=c.`ID_teamu`
			WHERE a.`odehrano`=1 
			ORDER BY a.`cas_odehrani` ASC 
SQL;
		$aZapasy=dbWrapper::dotaz($sql,
		Array(strtotime($datum),strtotime($datum)+60*60*24))->fetchAll();
		
		return $aZapasy;
	}

	public function ziskejPraveHraneZapasy($limit = 2) {
		$sql=<<<SQL
			SELECT 
			b.`jmeno`,c.`jmeno`
			FROM `2014_zapasy_{$this->kategorie}` a
				JOIN `2014_tymy_{$this->kategorie}` b ON a.`ID_domaci`=b.`ID_teamu`
				JOIN `2014_tymy_{$this->kategorie}` c ON a.`ID_hoste`=c.`ID_teamu`
			WHERE a.`odehrano` = 0 AND a.`cas_odehrani` > NOW() AND a.`cas_odehrani` < (NOW() + 60*60*30)
			ORDER BY a.`ID_zapasu` ASC
			LIMIT $limit
SQL;
		return dbWrapper::dotaz($sql, Array())->fetchAll();
	}

	public function ziskejTabulkuVysledku() {
		$SQL=<<<SQL
		SELECT `poradi`,`jmeno`,ifnull(sum(D),0) dane, ifnull(sum(H),0) dostane, `body`
			FROM (
			SELECT sum(`SCR_domaci`) D, sum(`SCR_hoste`) H, `ID_domaci` Team
			FROM `2014_zapasy_{$this->kategorie}`
			GROUP BY Team
			UNION
			SELECT sum(`SCR_hoste`) D, sum(`SCR_domaci`) H, `ID_hoste` Team
			FROM `2014_zapasy_{$this->kategorie}`
			GROUP BY Team
		)CLK
		RIGHT JOIN 2014_tymy_mladsi TM ON CLK.Team = TM.id_teamu
		GROUP BY `id_teamu`
		ORDER BY `poradi` ASC
SQL;
	return dbWrapper::dotaz($SQL,Array())->FetchAll();
	}

	public function ziskejRozlosovaniZapasu($datum) {
		$SQL=<<<SQL
		SELECT 
			b.`jmeno`,c.`jmeno`, a.`cas_odehrani`
			FROM `2014_zapasy_{$this->kategorie}` a
				JOIN `2014_tymy_{$this->kategorie}` b ON a.`ID_domaci`=b.`ID_teamu`
				JOIN `2014_tymy_{$this->kategorie}` c ON a.`ID_hoste`=c.`ID_teamu`
			WHERE a.`odehrano` = 0 
				AND UNIX_TIMESTAMP(a.`cas_odehrani`) > UNIX_TIMESTAMP(:datum) 
				AND UNIX_TIMESTAMP(a.`cas_odehrani`) < (UNIX_TIMESTAMP(:datum) + 24*60*60)
			ORDER BY a.`ID_zapasu` ASC
SQL;
		return dbWrapper::dotaz($SQL,Array("datum" => $datum))->FetchAll();
	}
}