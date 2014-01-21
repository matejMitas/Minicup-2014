<?php
	
	class VkladacZapasu{

		private $poleZapasu;


		public function ziskejFormular($kategorie, $pocetZapasu, $post){
			print_r($post);
			echo "<br>";
			if (isset($post['action'])){
            	if ($post['action'] == "Zkontrolovat!"){
            		$this -> ziskejZapasy($kategorie, $pocetZapasu);
            		if ($this -> byloUzZapsano($post['id'])) {
            			$return = $this -> overovaciFormular($post['score']);
            		}else{
            			$return = "<p>CHYBA</p>";
            		}         		
            	}elseif ($post['action'] == "Zapsat!") {
                	
            	}
        	}else{
            	$this -> ziskejZapasy($kategorie, $pocetZapasu);
            	$return = $this -> vkladaciFormular();
			}
        	return $return;
		}


		public function ziskejZapasy($kategorie, $pocetZapasu){
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
			$this -> pocetZapasu = $pocetZapasu;
			$this -> poleZapasu = $sql->FetchAll(PDO::FETCH_NUM);
			print_r($this -> poleZapasu);
		}


		public function vkladaciFormular(){
			$formular = '<form method="post">';
			for ($zapas=0; $zapas < $this -> pocetZapasu ; $zapas++) {
				$idTeamu = $this -> poleZapasu[$zapas][2];
				$formular .= <<<HTML
					<p>{$this -> poleZapasu[$zapas][0]} : {$this -> poleZapasu[$zapas][1]}</p>
					<p>
						<input type="text" name="score[$zapas][0]"> : <input type="text" name="score[$zapas][1]">
						<input type="hidden" name="id[$zapas]" value="$idTeamu">
					</p>
HTML;
			}
			$formular .= '<p><input type="submit" value="Zkontrolovat!" name="action"><p>';
			return $formular;
		}


		public function overovaciFormular($score){
			$formular = "";
			for ($zapas=0; $zapas < $this -> pocetZapasu; $zapas++) { 
				if (is_numeric($score[$zapas][0]) and is_numeric($score[$zapas][1])){
					$formular .= <<<HTML
					<p>{$this -> poleZapasu[$zapas][0]} : {$this -> poleZapasu[$zapas][1]}</p>
					<p>{$score[$zapas][0]} : {$score[$zapas][1]}</p>
HTML;
				}
			}
			return $formular;
		}


		public function byloUzZapsano($idZapasu){
			$nezapsano = True;
			for ($zapas=0; $zapas < $this -> pocetZapasu; $zapas++) {
				if ($idZapasu[$zapas] != $this -> poleZapasu[$zapas][2]) {
					$nezapsano = False;
				}
			}
			return $nezapsano;
		}
		

	}