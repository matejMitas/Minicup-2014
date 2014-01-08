<?php
class Novinkovac{
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
		$return .= <<<SLOVO
<h2>{$hodnota["titulek"]}</h2>
	<p>{$hodnota["aktualita"]}</p>
	<p><i>{$hodnota["vlozeno"]}</i></p>
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

<section id="nahled">
</section>

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
		INSERT INTO `2014_aktuality`(`aktualita`, `titulek`)
		VALUES (?,?)
SQL
,Array($titulek,$aktualita));
	}
	
	
	
	
}






