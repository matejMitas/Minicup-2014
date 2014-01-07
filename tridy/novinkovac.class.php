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
SQL
		,Array("pocet" => $pocet)
		);
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

<form action="">
	<label for="titulek">Titulek:</label>
	<input type="text" name="titulek">
	
	<label for="aktualita">Aktualita:</label>

<script>
$(document).ready(function(){
	$('.editor').jqte();	
});
</script>

	<textarea name="aktualita" id="editor" cols="75" rows="8">
	Zde vyplňte novinku k přidání...
	</textarea>
</form>	

VYSTUP;




	}
	
	
	
	
}






