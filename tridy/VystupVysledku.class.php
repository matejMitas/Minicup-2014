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

    public function ziskejNasledujiciZapasy($limit = 4) {
        $sql = <<<SQL
			SELECT 
			b.`jmeno`,c.`jmeno`,a.`ID_domaci`,a.`ID_hoste`
			FROM `2014_zapasy_{$this->kategorie}` a
                            JOIN `2014_tymy_{$this->kategorie}` b ON a.`ID_domaci`=b.`ID_teamu`
                            JOIN `2014_tymy_{$this->kategorie}` c ON a.`ID_hoste`=c.`ID_teamu`
			WHERE a.`odehrano`= 0 AND a.`cas_odehrani` > NOW()
			ORDER BY a.`cas_odehrani` ASC
			LIMIT $limit
SQL;
        return dbWrapper::dotaz($sql, Array())->fetchAll();
    }

    public function ziskejOdehraneZapasy($datum) {
        $sql = <<<SQL
                        SELECT 
			b.`jmeno`,c.`jmeno`, a.`SCR_domaci`,a.`SCR_hoste`,a.`cas_odehrani`
			FROM `2014_zapasy_{$this->kategorie}` a
				JOIN `2014_tymy_{$this->kategorie}` b ON a.`ID_domaci`=b.`ID_teamu`
				JOIN `2014_tymy_{$this->kategorie}` c ON a.`ID_hoste`=c.`ID_teamu`
			WHERE a.`odehrano` = 1 
				AND UNIX_TIMESTAMP(a.`cas_odehrani`) > UNIX_TIMESTAMP(:datum) 
				AND UNIX_TIMESTAMP(a.`cas_odehrani`) < (UNIX_TIMESTAMP(:datum) + 24*60*60)
			ORDER BY a.`ID_zapasu` ASC
SQL;
        return dbWrapper::dotaz($sql, Array("datum" => $datum))->fetchAll();
    }

    public function ziskejPraveHraneZapasy($limit = 2) {
        $sql = <<<SQL
			SELECT 
			b.`jmeno`,c.`jmeno`,a.`ID_domaci`,a.`ID_hoste`
			FROM `2014_zapasy_{$this->kategorie}` a
                            JOIN `2014_tymy_{$this->kategorie}` b ON a.`ID_domaci`=b.`ID_teamu`
                            JOIN `2014_tymy_{$this->kategorie}` c ON a.`ID_hoste`=c.`ID_teamu`
			WHERE a.`odehrano` = 0 AND a.`cas_odehrani` < NOW() AND a.`cas_odehrani`+60*60*30 > NOW()
			ORDER BY a.`ID_zapasu` ASC
			LIMIT $limit
SQL;
        return dbWrapper::dotaz($sql, Array())->fetchAll();
    }

    public function ziskejTabulkuVysledku() {
        $SQL = <<<SQL
        SELECT `poradi`,`jmeno`,ifnull(SUM(D),0) dane, ifnull(SUM(H),0) dostane, `body`, `id_teamu`, IFNULL(SUM(KEDLUBEN),0) pocet
            FROM (
            SELECT SUM(`SCR_domaci`) D, SUM(`SCR_hoste`) H, `ID_domaci` Team, SUM(`odehrano`) KEDLUBEN
            FROM `2014_zapasy_{$this->kategorie}`
            WHERE `odehrano` = 1
            GROUP BY Team
            UNION ALL
            SELECT SUM(`SCR_hoste`) D, SUM(`SCR_domaci`) H, `ID_hoste` Team, SUM(`odehrano`) KEDLUBEN
            FROM `2014_zapasy_{$this->kategorie}`
            WHERE `odehrano` = 1
            GROUP BY Team
        ) CLK
        RIGHT JOIN `2014_tymy_{$this->kategorie}` TM ON CLK.Team = TM.id_teamu
        GROUP BY `id_teamu`
        ORDER BY `poradi` ASC
SQL;
        return dbWrapper::dotaz($SQL, Array())->FetchAll();
    }

    public function ziskejRozlosovaniZapasu($datum) {
        $SQL = <<<SQL
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
        return dbWrapper::dotaz($SQL, Array("datum" => $datum))->FetchAll();
    }

}
