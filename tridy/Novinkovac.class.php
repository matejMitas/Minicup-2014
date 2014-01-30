<?php
class Novinkovac{
	/** Získání textu relativního data a času
* @param int porovnávaný čas jako Unix timestamp
* @param int aktuální čas, time() při neuvedení
* @return string
*/


	function __construct() {
		
	}
	
public function ziskejNovinky($pocet) {
	$return="";
	$vysledek=dbWrapper::dotaz(<<<SQL
		SELECT *
		FROM `2014_aktuality`
		ORDER BY `2014_aktuality`.`vlozeno` DESC
		LIMIT $pocet
SQL
	,Array());
	$aNovinek = $vysledek->fetchAll();

	foreach ($aNovinek as $klic => $hodnota) {

		$relTime= $this->relativeCzechDate(strtotime($hodnota['vlozeno']));

		$return .= <<<SLOVO
<h2>{$hodnota["titulek"]}</h2>
	<p>{$hodnota["aktualita"]}</p>
	<p><i>{$relTime}</i></p>
SLOVO;
	}
	return $return;	
}
	

public function ziskejVkladaciFormular() {
	return <<<VYSTUP
	<form action="" method="POST">
		<label for="titulek">Titulek:</label>
		<input type="text" name="titulek" required="required">
		<br>

		<label for="aktualita">Aktualita:</label>
		<textarea name="aktualita" id="aktualita" placeholder="Zde vyplňte aktualitu k přidání.." cols="75" rows="8" required="required">
		</textarea>

		<input type="submit" value="Odeslat novinku">
	</form>	
<script language="javascript" type="text/javascript">
$(document).ready(function(){	
	$('textarea').jqte();
	var nahled= "";
	$('#aktualita').change(function(){
		nahled = $('#aktualita').text();
		$('#nahled').text(nahled);
	});
});
</script>
VYSTUP;
	}
public function vlozNovinku($titulek,$aktualita){
	return dbwrapper::dotaz(<<<SQL
		INSERT INTO `2014_aktuality`(`titulek`,`aktualita`)
		VALUES (?,?)
SQL
	,Array($titulek,$aktualita));
}


public function relativeCzechDate($time, $now = null) {
	if (!isset($now)) {
		$now = time();
	}
	$seconds = $now - $time;
	$minutes = floor($seconds / 60);
	$hours = floor($minutes / 60);
	$days = floor($hours / 24);
	$months = floor($days / 30);
	$years = floor($days / 365);
	if ($years >= 2) {
		return "před $years lety";
	} elseif ($years == 1) {
		return "před rokem";
	} elseif ($months >= 2) {
		return "před $months měsíci";
	} elseif ($months == 1) {
		return "před měsícem";
	} elseif ($days >= 2) {
		return "před $days dny";
	} elseif ($hours >= 2) {
		return "před $hours hodinami";
	} elseif ($hours == 1) {
		return "před hodinou";
	} elseif ($minutes >= 2) {
		return "před $minutes minutami";
	} elseif ($minutes == 1) {
		return "před minutou";
	} elseif ($seconds >= 0) {
		return "před chvílí";
	} else {
		return "v budoucnu";
	}
}
	
	

}






