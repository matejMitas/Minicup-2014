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

	public function aktualizujBody(){
		$result = dbWrapper::dotaz(<<<SQL
			SELECT `id_teamu`
			FROM `2014_tymy_mladsi` 
SQL
		)->FetchAll();

		foreach ($result as $key => $id_teamu) {
			$SQL = <<<SQL
			UPDATE 2014_tymy_mladsi 
			SET body = (
				SELECT sum(body)
				FROM (
					SELECT sum(body_hoste) body, ID_domaci Team
					FROM 2014_zapasy_mladsi
					WHERE id_hoste = :id
				UNION
					SELECT sum(body_domaci) body, ID_hoste Team
					FROM 2014_zapasy_mladsi
					WHERE id_domaci = :id 
				) CLK
			)
			WHERE id_teamu = :id
SQL;
			dbWrapper::dotaz($SQL,Array(":id" => $id_teamu["id_teamu"]));


		}
	}
}