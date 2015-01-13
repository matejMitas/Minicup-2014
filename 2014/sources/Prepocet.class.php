<?php

/**
 * Prepocet zapasu
 */
class Prepocet {
    /**
     * Kategorie pro přepočítání
     * @var string
     */
    private $category;


    /**
     * @param string $category
     */
    public function __construct($category) {
        $this->category = $category;
    }

    public function order() {
        $sql = "SELECT * FROM `2014_tymy_{$this->category}` ORDER BY body DESC";
        $result = dbWrapper::query($sql, Array());
        $this->commonOrder($sql, 1, $result->rowCount());
    }

    public function refreshPoints() {
        $result = dbWrapper::query(<<<SQL
			SELECT `id_teamu`
			FROM `2014_tymy_{$this->category}`
SQL
        )->FetchAll();

        foreach ($result as $key => $row) {
            $SQL = <<<SQL
			UPDATE `2014_tymy_{$this->category}`
			SET `body` = (
				SELECT sum(body)
				FROM (
					SELECT sum(`body_hoste`) body
					FROM `2014_zapasy_{$this->category}`
					WHERE `id_hoste` = :id
				UNION ALL
					SELECT sum(`body_domaci`) body
					FROM `2014_zapasy_{$this->category}`
					WHERE `id_domaci` = :id 
				) CLK
			)
			WHERE `id_teamu` = :id
SQL;
            dbWrapper::query($SQL, Array(":id" => $row["id_teamu"]));


        }

    }

    private function orderByScoreGoals($teams, $order) {
        $in_list = implode(", ", $teams);
        $query = <<<SQL
SELECT ifnull(SUM(D),0) dane, ifnull(SUM(H),0) dostane, `id_teamu`, `jmeno`
FROM (
    SELECT SUM( `SCR_domaci` ) D, SUM(`SCR_hoste`) H, `ID_domaci` team
    FROM `2014_zapasy_{$this->category}`
    GROUP BY team
    UNION
    SELECT SUM(`SCR_hoste`) D, SUM(`SCR_domaci`) H, `ID_hoste` team
    FROM `2014_zapasy_{$this->category}`
    GROUP BY team
) CLK
RIGHT JOIN `2014_tymy_{$this->category}` TM ON CLK.team = TM.`id_teamu`
WHERE `id_teamu`IN ($in_list)
GROUP BY `id_teamu`
ORDER BY SUM(D) DESC
SQL;

        $max = count($teams) + $order;
        $result = dbWrapper::query($query, Array());
        $row = $result->fetch(PDO::FETCH_BOTH);
        while ($order < $max) {
            $array = array();
            $dane = $row[1];
            $i = 0;
            while ($dane == $row[1]) {
                $array[$i] = $row[2];
                $row = $result->fetch(PDO::FETCH_BOTH);
                $i++;
                if ($row == False) {
                    break;
                }
            }
            if (count($array) == 1) {
                $sql_update = <<<SQL
        UPDATE `2014_tymy_{$this->category}`
        SET poradi= $order
        WHERE `id_teamu`=?
SQL;
                dbWrapper::query($sql_update, Array($array[0]));
                $order++;
            } else {
                for ($i = 0; $i < count($array); $i++) {
                    $sql_update = <<<SQL
UPDATE `2014_tymy_{$this->category}`
SET poradi= $order
WHERE `id_teamu`= ?
SQL;

                    dbWrapper::query($sql_update, Array($array[$i]));
                }
                $order += count($array);
            }
        }
    }


    /**
     * serad_score
     *
     * @param mixed $teams Description.
     * @param mixed $order Description.
     *
     * @access private
     *
     * @return mixed Value.
     */
    private function orderByScore($teams, $order) { //seřadí trojuhelnik podle score
        $in_list = implode(", ", $teams);
        $query = "SELECT
ifnull(SUM(dane),0) dane, ifnull(SUM(dostane),0) dostane,`id_teamu`, `jmeno`
FROM (
    SELECT SUM(`SCR_domaci`) dane, SUM(`SCR_hoste`) dostane, `ID_domaci` team
    FROM `2014_zapasy_{$this->category}`
    GROUP BY team
    UNION
    SELECT SUM(`SCR_hoste`) dane, SUM(`SCR_domaci`) dostane, `ID_hoste` team
    FROM `2014_zapasy_{$this->category}`
    GROUP BY team
)CLK
RIGHT JOIN `2014_tymy_{$this->category}` TM ON CLK.team = TM.`id_teamu`
WHERE `id_teamu` IN ($in_list)
GROUP BY `id_teamu`
ORDER BY SUM(dane)-SUM(dostane) DESC";

        $max = count($teams) + $order; // přiděluje místa od <$poradi,$max)
        $result = dbWrapper::query($query, Array());

        $row = $result->fetch(PDO::FETCH_BOTH);
        while ($order < $max) {
            $array = array();
            $difference = $row[0] - $row[1]; //rozdíl score dane-dostane jednoho tymu
            $i = 0;
            while ($difference == $row[0] - $row[1]) { //do arraye dá všechny týmy se stejnou bilancí score tzn. vytvoří skupiny
                $array[$i] = $row[2];
                $row = $result->fetch(PDO::FETCH_BOTH);
                $i++;
                if ($row == False) {
                    break;
                }
            }
            if (count($array) == 1) { //je jediný ve skupině? jednoznačné umísteni
                $update_sql = "UPDATE `2014_tymy_{$this->category}`
SET poradi= ? 
WHERE `id_teamu`= ?";
                dbWrapper::query($update_sql, Array($order, $array[0]));

                $order++;
            } else { //není jediný - stejná bilenace více týmu - seřazení podle daných golu
                $this->orderByScoreGoals($array, $order);
                $order += count($array); //inkrementuje umístení pro přirazení
            }
        }
    }


    private function commonOrder($query, $order_start, $order_end) {
        $result = dbWrapper::query($query, Array());
        $row = $result->fetch(PDO::FETCH_BOTH);
        while ($order_start <= $order_end) {
            $teams = [];
            $points = $row[2];
            $i = 0;

            while ($row[2] == $points) { //vytvoření arraye týmů se stejnými body - $array_teamy skupina týmů se stejnými body
                $teams[$i] = $row[0];
                $row = $result->fetch(PDO::FETCH_BOTH);
                $i++;
            }

            if (count($teams) == 1) { //pokud jsou jeho body jedinečné

                $update_sql = "UPDATE `2014_tymy_{$this->category}`
    SET `poradi`= ?
    WHERE `id_teamu`= ?"; //nastaví se na 1. místo ve skupině, resp. $order_start
                dbWrapper::query($update_sql, Array($order_start, $teams[0]));
                $order_start++;
            } elseif (count($teams) == ($order_end - $order_start + 1)) {  //pokud mají všichni stejně bodů... pokud trojuhelnik
                $this->orderByScore($teams, $order_start);
                $order_start = $order_start + count($teams);
            } else { //pokud není sám && podskupina k seřazení
                $in_list = implode(", ", $teams); //vytvoří zemnšeninu tabulky pouze s body mezi sebou
                $select = <<<SQL
SELECT `id_teamu`, `jmeno`, ifnull(SUM(D),0) body
FROM (
    SELECT SUM(`body_domaci`) D, `ID_domaci` Team
        FROM `2014_zapasy_{$this->category}`
        WHERE `ID_domaci` IN ($in_list) AND `ID_hoste` IN ($in_list)
        GROUP BY Team

    UNION ALL

    SELECT SUM(`body_hoste`) D, `ID_hoste` Team
        FROM `2014_zapasy_{$this->category}`
        WHERE `ID_hoste` IN ($in_list) AND `ID_domaci` IN ($in_list)
        GROUP BY Team
) CLK
RIGHT JOIN (
    SELECT `id_teamu`, `jmeno`
    FROM `2014_tymy_{$this->category}`
    WHERE `id_teamu` IN ($in_list)
) TM
ON CLK.Team = TM.`id_teamu`
GROUP BY `id_teamu`
ORDER BY body DESC
SQL;
                $this->commonOrder($select, $order_start, $order_start + count($teams) - 1);
                $order_start += count($teams);
            }
        }
    }
}
