<?php
	
	class VkladacZapasu{

		private $poleZapasu;
		private $pocetZapasu;
		private $kategorie;


		public function __construct($kategorie, $pocetZapasu){
			require('dbWrapper.class.php'); #Může se smazat
        	dbWrapper::pripoj(); #také smazat
        	$zapasyKategorie = "2014_zapasy_" . $kategorie;
        	$tymyKategorie = "2014_tymy_" . $kategorie;
        	$sql = dbWrapper::dotaz(<<<SQL
        		SELECT 
        		b.`jmeno`,c.`jmeno`,`ID_zapasu` 
				FROM $zapasyKategorie a
  				JOIN $tymyKategorie b ON a.`ID_domaci` = b.`id_teamu`
				JOIN $tymyKategorie c ON a.`ID_hoste` = c.`id_teamu`
				WHERE a.`odehrano` = 0
				ORDER BY a.`cas_odehrani`
				LIMIT $pocetZapasu
SQL
			, Array());
			$this -> kategorie = $kategorie;
			$this -> pocetZapasu = $pocetZapasu;
			$this -> poleZapasu = $sql->FetchAll(PDO::FETCH_NUM);
		}


		public function ziskejFormular($post){
			if (isset($post['action'])){
				session_start();
				if ($post['action'] == "Zapsat!"){
					$idZapasu = $_SESSION['ID']['id'];
				}else{ 
					$idZapasu = $post['id'];
				}
				if ($this -> byloZapsano($idZapasu)){
					switch($post['action']){
						case "Zkontrolovat!":
            				$return = $this -> overovaciFormular($post);
            				break;
						case "Zapsat!":
							$this -> zapisDB($_SESSION['ID']);
							unset($_SESSION['ID']);
							$return = "Zapisovani";
							break;
					}
				}else{
    	       		$return = "<p>CHYBA</p>";
    	       	}
			}else{
            	$return = $this -> vkladaciFormular();
			}
			return $return;
		}


		public function vkladaciFormular(){
			$formular = '<form method="post">';
			for ($zapas=0; $zapas < $this -> pocetZapasu ; $zapas++) {
				$idTeamu = $this -> poleZapasu[$zapas][2];
				$formular .= <<<HTML
					<p>{$this -> poleZapasu[$zapas][0]}:{$this -> poleZapasu[$zapas][1]}</p>
					<p>
						<input type="text" name="score[$zapas][0]">:<input type="text" name="score[$zapas][1]">
						<input type="hidden" name="id[$zapas]" value="$idTeamu">
					</p>
HTML;
			}
			$formular .= '<p><input type="submit" value="Zkontrolovat!" name="action"><p>';
			$formular .= '</form>';
			return $formular;
		}


		public function overovaciFormular($post){
			$_SESSION['ID'] = $post;
			$score = $post['score'];
			$formular = "";
			for ($zapas=0; $zapas < $this -> pocetZapasu; $zapas++) { 
				if (is_numeric($score[$zapas][0]) && is_numeric($score[$zapas][1])){
					$formular .= <<<HTML
					<p>{$this -> poleZapasu[$zapas][0]}:{$this -> poleZapasu[$zapas][1]}</p>
					<p>{$score[$zapas][0]}:{$score[$zapas][1]}</p>
HTML;
				}
			}
			$formular .= "
			<p>
				<form method='post'><input type='submit' value='Zapsat!' name='action'></form>
				<input onclick='javascript:self.history.back(); type=button value='Zpět k zadání výsledků'>
			<p>";
			
			return $formular;
		}


		public function zapisDB($post){
			for ($zapas=0; $zapas < $this -> pocetZapasu; $zapas++) {
				if (is_numeric($post['score'][$zapas][0]) && is_numeric($post['score'][$zapas][1])){
					$sql = dbWrapper::dotaz(<<<SQL
						UPDATE `2014_zapasy_mladsi` 
						SET `SCR_domaci`=?,`SCR_hoste`=?,`cas_vlozeni`=NOW(),`odehrano`='1'
						WHERE `ID_zapasu`=?
SQL
					, Array($post['score'][$zapas][0], $post['score'][$zapas][1], $post['id'][$zapas]));
				}
			}
		}


		public function byloZapsano($idZapasu){
			$nezapsano = True;
			for ($zapas=0; $zapas < $this -> pocetZapasu; $zapas++) {
				if ($idZapasu[$zapas] != $this -> poleZapasu[$zapas][2]) {
					$nezapsano = False;
				}
			}
			return $nezapsano;
		}
		

	}
