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
}






