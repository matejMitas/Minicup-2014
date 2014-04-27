<?php

/**
* Prepocet zapasu
*/
class Prepocet{
	/**
	 * Kategorie pro přepočítání
	 * @var string
	 */
	private $kategorie;


	function __construct($kategorie){
		$this->kategorie=$kategorie;
	}

	public function serad(){
		$sql = "SELECT * FROM `2014_tymy_mladsi` ORDER BY body DESC";
		$vysledek = dbWrapper::dotaz($sql,Array());
		$this->serad_obecne($sql,1,$vysledek->rowCount());
    }

	public function aktualizujBody(){
		$result = dbWrapper::dotaz(<<<SQL
			SELECT `id_teamu`
			FROM `2014_tymy_mladsi` 
SQL
		)->FetchAll();

		foreach ($result as $key => $id_teamu) {
			$SQL = <<<SQL
			UPDATE `2014_tymy_mladsi` 
			SET `body` = (
				SELECT sum(body)
				FROM (
					SELECT sum(`body_hoste`) body
					FROM `2014_zapasy_mladsi`
					WHERE `id_hoste` = :id
				UNION ALL
					SELECT sum(`body_domaci`) body
					FROM `2014_zapasy_mladsi`
					WHERE `id_domaci` = :id 
				) CLK
			)
			WHERE `id_teamu` = :id
SQL;
			dbWrapper::dotaz($SQL,Array(":id" => $id_teamu["id_teamu"]));


		}

	}
	private function serad_dane($array_teamy,$poradi){
$in_list = implode(", ",$array_teamy);
$query=<<<SQL
SELECT ifnull(SUM(D),0) dane, ifnull(SUM(H),0) dostane, `id_teamu`, `jmeno`
FROM (
    SELECT SUM( `SCR_domaci` ) D, SUM(`SCR_hoste`) H, `ID_domaci` team
    FROM `2014_zapasy_mladsi`
    GROUP BY team
    UNION
    SELECT SUM(`SCR_hoste`) D, SUM(`SCR_domaci`) H, `ID_hoste` team
    FROM `2014_zapasy_mladsi`
    GROUP BY team
) CLK
RIGHT JOIN `2014_tymy_mladsi` TM ON CLK.team = TM.`id_teamu`
WHERE `id_teamu`IN ($in_list)
GROUP BY `id_teamu`
ORDER BY SUM(D) DESC
SQL;

$max=count($array_teamy)+$poradi;
$vysledek=dbWrapper::dotaz($query,Array());
$radek=$vysledek->fetch(PDO::FETCH_BOTH);
while($poradi< $max) {
    $array=array();
    $dane=$radek[1];
    $i=0;
    while($dane==$radek[1]) {
        $array[$i]=$radek[2];
    $radek=$vysledek->fetch(PDO::FETCH_BOTH); 
    $i++;
if ($radek==False){
    break;
}
}
if(count($array)==1) {
    $sql_update=<<<SQL
        UPDATE `2014_tymy_mladsi`
        SET poradi= $poradi 
        WHERE `id_teamu`=?
SQL;
    dbWrapper::dotaz($sql_update,Array($array[0]));
    $poradi++;
} else {
for($i=0;$i< count($array);$i++){
$sql_update=<<<SQL
UPDATE `2014_tymy_mladsi`
SET poradi= $poradi 
WHERE `id_teamu`= ?
SQL;

dbWrapper::dotaz($sql_update,Array($array[$i]));
        }
    $poradi+=count($array);
    }
}}






    /**
     * serad_score
     * 
     * @param mixed $array_teamy Description.
     * @param mixed $poradi      Description.
     *
     * @access private
     *
     * @return mixed Value.
     */ 
private function serad_score($array_teamy,$poradi){ //seřadí trojuhelnik podle score
$in_list = implode(", ",$array_teamy);
$query="SELECT 
ifnull(SUM(dane),0) dane, ifnull(SUM(dostane),0) dostane,`id_teamu`, `jmeno`
FROM (
    SELECT SUM(`SCR_domaci`) dane, SUM(`SCR_hoste`) dostane, `ID_domaci` team
    FROM `2014_zapasy_mladsi`
    GROUP BY team
    UNION
    SELECT SUM(`SCR_hoste`) dane, SUM(`SCR_domaci`) dostane, `ID_hoste` team
    FROM `2014_zapasy_mladsi`
    GROUP BY team
)CLK
RIGHT JOIN `2014_tymy_mladsi` TM ON CLK.team = TM.`id_teamu`
WHERE `id_teamu` IN ($in_list)
GROUP BY `id_teamu`
ORDER BY SUM(dane)-SUM(dostane) DESC";

$max=count($array_teamy)+$poradi; // přiděluje místa od <$poradi,$max)
$vysledek=dbWrapper::dotaz($query,Array());

$radek=$vysledek->fetch(PDO::FETCH_BOTH);
while($poradi< $max) {
    $array=array();
    $bilance=$radek[0]-$radek[1]; //rozdíl score dane-dostane jednoho tymu
    $i=0;
    while($bilance==$radek[0]-$radek[1]) { //do arraye dá všechny týmy se stejnou bilancí score tzn. vytvoří skupiny
        $array[$i]=$radek[2];
        $radek=$vysledek->fetch(PDO::FETCH_BOTH);
        $i++;
        if ($radek==False){
            break;
        }
    }
if(count($array)==1) { //je jediný ve skupině? jednoznačné umísteni
$update_sql="UPDATE `2014_tymy_mladsi`
SET poradi= ? 
WHERE `id_teamu`= ?";
dbWrapper::dotaz($update_sql,Array($poradi,$array[0]));

$poradi++;
} else { //není jediný - stejná bilenace více týmu - seřazení podle daných golu
    $this->serad_dane($array,$poradi);
    $poradi+=count($array); //inkrementuje umístení pro přirazení
    }
}}





private function serad_obecne ($query,$poradi_Z,$poradi_K){
    $vysledek=dbWrapper::dotaz($query,Array());
    $radek=$vysledek->fetch(PDO::FETCH_BOTH);
while($poradi_Z<=$poradi_K) {
    $array_teamy=array();
    $body=$radek[2];
    $i=0;

    while($radek[2]==$body) { //vytvoření arraye týmů se stejnými body - $array_teamy skupina týmů se stejnými body
        $array_teamy[$i]=$radek[0];
        $radek = $vysledek->fetch(PDO::FETCH_BOTH);
        $i++;
    }

if(count($array_teamy)==1) { //pokud jsou jeho body jedinečné
    
    $update_sql = "UPDATE `2014_tymy_mladsi` 
    SET `poradi`= ?
    WHERE `id_teamu`= ?"; //nastaví se na 1. místo ve skupině, resp. $poradi_Z
    dbWrapper::dotaz($update_sql,Array($poradi_Z, $array_teamy[0]));
    $poradi_Z++;
} 
elseif (count($array_teamy)==($poradi_K-$poradi_Z+1)) {  //pokud mají všichni stejně bodů... pokud trojuhelnik
        $this->serad_score($array_teamy,$poradi_Z);
        $poradi_Z=$poradi_Z+count($array_teamy);
} 
else { //pokud není sám && podskupina k seřazení
$in_list = implode(", ",$array_teamy); //vytvoří zemnšeninu tabulky pouze s body mezi sebou
$select=<<<SQL
SELECT `id_teamu`, `jmeno`, ifnull(SUM(D),0) body
FROM (
    SELECT SUM(`body_domaci`) D, `ID_domaci` Team
        FROM `2014_zapasy_mladsi`
        WHERE `ID_domaci` IN ($in_list) AND `ID_hoste` IN ($in_list)
        GROUP BY Team

    UNION ALL

    SELECT SUM(`body_hoste`) D, `ID_hoste` Team
        FROM `2014_zapasy_mladsi`
        WHERE `ID_hoste` IN ($in_list) AND `ID_domaci` IN ($in_list)
        GROUP BY Team
) CLK
RIGHT JOIN (
    SELECT `id_teamu`, `jmeno`
    FROM `2014_tymy_mladsi`
    WHERE `id_teamu` IN ($in_list)
) TM
ON CLK.Team = TM.`id_teamu`
GROUP BY `id_teamu`
ORDER BY body DESC
SQL;
$this->serad_obecne($select,$poradi_Z,$poradi_Z+count($array_teamy)-1);    
$poradi_Z+=count($array_teamy);
}
}}}
