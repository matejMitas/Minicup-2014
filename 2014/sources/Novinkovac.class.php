<?php
class Novinkovac{

public function ziskejNovinky($pocet) {
	$novinky=dbWrapper::dotaz(<<<SQL
		SELECT `aktualita`, `titulek`, `vlozeno`
		FROM `2014_aktuality`
		ORDER BY `vlozeno` DESC
		LIMIT $pocet
SQL
	,Array())->fetchAll();
	return $novinky;
}
	

public function ziskejVkladaciFormular() {
	return <<<VYSTUP
	<form action="" method="POST">
		<h3>Titulek:</h3>
		<input type="text" name="titulek" required="required">
		<br>

		<h3>Aktualita:</h3>
		<textarea style="max-width:97%;" name="aktualita" id="aktualita" placeholder="Zde vyplňte aktualitu k přidání.." cols="75" rows="8" required="required"></textarea>

		<input type="submit" value="Odeslat novinku">
	</form>	
VYSTUP;
	}
public function vlozNovinku($titulek,$aktualita){
	return dbwrapper::dotaz(<<<SQL
		INSERT INTO `2014_aktuality`(`titulek`,`aktualita`)
		VALUES (?,?)
SQL
	,Array($titulek,$aktualita));
}
}






