<?php
class VystupVysledku {
    /**
     * ziskejOdehraneZapasy
     * 
     * @param time $datum datum pro vygenerování zápasů
     *
     * @access public
     *
     * @return array[string] vrátí raw data všech zápasů
     */
	public function ziskejOdehraneZapasy($datum){
		$sql=<<<SQL
			SELECT 
			b.`jmeno`,c.`jmeno`,a.`SCR_domaci`,`SCR_hoste`
			FROM `2014_zapasy_mladsi` a
			JOIN `2014_tymy_mladsi` b ON a.`ID_domaci`=b.`ID_teamu`
			JOIN `2014_tymy_mladsi` c ON a.`ID_hoste`=c.`ID_teamu`
			WHERE a.`odehrano`=1 
			ORDER BY a.`cas_odehrani` ASC 
SQL;
		$aZapasy=dbWrapper::dotaz($sql,
		Array(strtotime($datum),strtotime($datum)+60*60*24))->fetchAll();
		
		return $aZapasy;
		}

	public function ziskejTabulkuVysledku(){
		$SQL=<<<SQL
		SELECT `poradi`,`jmeno`,ifnull(sum(D),0) dane, ifnull(sum(H),0) dostane, `body`
			FROM (
			SELECT sum(`SCR_domaci`) D, sum(`SCR_hoste`) H, `ID_domaci` Team
			FROM `2014_zapasy_mladsi`
			GROUP BY Team
			UNION
			SELECT sum(`SCR_hoste`) D, sum(`SCR_domaci`) H, `ID_hoste` Team
			FROM `2014_zapasy_mladsi`
			GROUP BY Team
		)CLK
		RIGHT JOIN 2014_tymy_mladsi TM ON CLK.Team = TM.id_teamu
		GROUP BY `id_teamu`
		ORDER BY `poradi` ASC
SQL;
	return dbWrapper::dotaz($SQL,Array())->FetchAll();
	}
	}