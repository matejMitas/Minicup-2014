<?php
	
	class VkladacZapasu{

		/*
			$post = [score] -> [integer] -> [score_domaci][score_hoste] // [id] -> [integer] // [action]

			$_SESSION[ID] = [score] -> [integer] -> [score_domaci][score_hoste] // [id] -> [integer] // [action]
		*/

		private $poleZapasu;  # [integer] -> [jmeno_domacich][jmeno_hostu][ID_zapasu]
		private $pocetZapasu; # integer
		private $kategorie;   # mladsi OR starsi

		# Vytáhne z databáze pole se jménem týmů a s ID zápasu
		public function __construct($kategorie, $pocetZapasu){
			define("MRAZKUV_PODELANY_PC",false);
			if (MRAZKUV_PODELANY_PC == true || $_SERVER["REMOTE_ADDR"] == "127.0.0.1") {
				require('dbWrapper.class.php'); #Může se smazat
        		dbWrapper::pripoj(); #také smazat
			}
			
        	define("BODY_VYHRA", 2);
        	define("BODY_REMIZA", 1);
        	define("BODY_PROHRA", 0);
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

		# Podle předchozích akcích zjistí jaký formulář má generovat (vkládací, ověřovací, zápis do DB)
		public function ziskejFormular($post){
			if (isset($post['action'])){
				session_start();
				if ($post['action'] == "Zapsat!"){
					$idZapasu = $_SESSION['ID']['id'];
					$score = $_SESSION['ID']['score'];
				}else{ 
					$idZapasu = $post['id'];
					$score = $post['score'];
				}
				if ($this -> byloZapsano($idZapasu) && $this -> jsouKladne($score)){
					$post['score'] = $this -> upravaUzivalelVstup($score);
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
    	       		$this -> zjistiChybu($score); # nějaká funkce co zjistí co je přesně za problém
    	       		$return = "";
    	       	}
			}else{
            	$return = $this -> vkladaciFormular();
			}
			return $return;
		}

		# Vytvoří vkládací formulář pro zadávání zápasů
		private function vkladaciFormular(){
			$formular = '<form method="post">';
			for ($zapas=0; $zapas < $this -> pocetZapasu ; $zapas++){
				if (isset($this -> poleZapasu[$zapas][2])){
					$idTeamu = $this -> poleZapasu[$zapas][2];
					$formular .= <<<HTML
						<p>{$this -> poleZapasu[$zapas][0]}:{$this -> poleZapasu[$zapas][1]}</p>
						<p>
							<input type="text" name="score[$zapas][0]">:<input type="text" name="score[$zapas][1]">
							<input type="hidden" name="id[$zapas]" value="$idTeamu">
						</p>
HTML;
				}
				
			}
			$formular .= '<p><input type="submit" value="Zkontrolovat!" name="action"><p>';
			$formular .= '</form>';
			return $formular;
		}

		# Vypíše co uživatel zadal, pak se může rozhodnout zda zápas vloží do DB, nebo ho ještě upraví
		private function overovaciFormular($post){
			$_SESSION['ID'] = $post;
			$score = $post['score'];
			$formular = "";
			for ($zapas=0; $zapas < $this -> pocetZapasu; $zapas++){
				if (isset($this -> poleZapasu[$zapas][2])){
					if (is_numeric($score[$zapas][0]) && is_numeric($score[$zapas][1])){
						$formular .= $this -> zvyrazniTymVyhercu($zapas, $score[$zapas][0], $score[$zapas][1]);
						$formular .= "<p>{$score[$zapas][0]}:{$score[$zapas][1]}</p>";
					}
				}
			}
			$formular .= <<<HTML
				<p>
					<form method='post'><input type='submit' value='Zapsat!' name='action'></form>
					<input onclick=javascript:self.history.back(); type=button value='Zpět k zadání výsledků'>
				<p>
HTML;
			return $formular;
		}

		# Do DB se zapíše odehraný zápas + se týmu, který vyhrál nebo remízoval, přičtou body
		private function zapisDB($post){
			$zapasyKategorie = "2014_zapasy_" . $this -> kategorie;
        	$tymyKategorie = "2014_tymy_" . $this -> kategorie;
        	$score = $post['score'];
			for ($zapas=0; $zapas < $this -> pocetZapasu; $zapas++){
				if (isset($this -> poleZapasu[$zapas][2])){
					if (is_numeric($score[$zapas][0]) && is_numeric($score[$zapas][1])){
						$bodyTym = $this -> ziskaneBody($score[$zapas][0], $score[$zapas][1]);
						$sql = dbWrapper::dotaz(<<<SQL
							UPDATE $zapasyKategorie 
							SET `SCR_domaci`=?,`body_domaci`=?,`SCR_hoste`=?,`body_hoste`=?,`cas_vlozeni`=NOW(),`odehrano`='1'
							WHERE `ID_zapasu`=?
SQL
						, Array($score[$zapas][0], $bodyTym[0], $score[$zapas][1], $bodyTym[1], $post['id'][$zapas]));
					}
				}
			}
			# Něco by to mělo vracet ale nenapadá mě co :D
		}

		# Zjistí kdo vyhrál, vrací pole /$body[0 => 'BODY_DOMACI'  1 => 'BODY_HOSTE']/
		private function ziskaneBody($scoreDomaci, $scoreHoste){
			if ($scoreDomaci > $scoreHoste){
				$body = array(BODY_VYHRA, BODY_PROHRA);
			}elseif ($scoreDomaci == $scoreHoste){
				$body = array(BODY_REMIZA, BODY_REMIZA);
			}elseif ($scoreDomaci < $scoreHoste){
				$body = array(BODY_PROHRA, BODY_VYHRA);
			}
			return $body;
		}

		# Zjistí kdo vyhrál a toho zvýrazní, při remíze oba týmy bez zvýraznění
		private function zvyrazniTymVyhercu($idZapasu, $scoreDomaci, $scoreHoste){
			if ($scoreDomaci > $scoreHoste){
				$formular = "<p><b>{$this -> poleZapasu[$idZapasu][0]}</b>:{$this -> poleZapasu[$idZapasu][1]}</p>";
			}elseif ($scoreDomaci == $scoreHoste){
				$formular = "<p>{$this -> poleZapasu[$idZapasu][0]}:{$this -> poleZapasu[$idZapasu][1]}</p>";
			}elseif ($scoreDomaci < $scoreHoste){
				$formular = "<p>{$this -> poleZapasu[$idZapasu][0]}:<b>{$this -> poleZapasu[$idZapasu][1]}</b></p>";
			}else{
				$formular = "";
			}
			return $formular;
		}

		# Zjistí jestli zápasy, které chce uživatel zapsat nejsou už náhodou zapsané
		private function byloZapsano($idZapasu){
			$nezapsano = True;
			for ($zapas=0; $zapas < $this -> pocetZapasu; $zapas++){
				if (isset($idZapasu[$zapas])){
					if ($idZapasu[$zapas] != $this -> poleZapasu[$zapas][2]){
						$nezapsano = False;
					}
				}
				
			}
			return $nezapsano;
		}

		# Zjistí zda jsou výsledky kladné
		private function jsouKladne($score){
			$jsouKladna = True;
			foreach ($score as $klic => $values){
            	foreach ($values as $key => $value){
            		if ($value < 0){
            			$jsouKladna = False;
            		}
            	}
        	}			
        	return $jsouKladna;
		}

		# Upraví uživatelský vstup tak že projdou jen validně zadané scóre, ostatní se nulují
		private function upravaUzivalelVstup($score){
			for ($zapas=0; $zapas < $this -> pocetZapasu; $zapas++){
				if (isset($this -> poleZapasu[$zapas][2])){
					for ($tym=0; $tym < 2; $tym++){
						if (is_numeric(trim($score[$zapas][$tym]))){
							$score[$zapas][$tym] = trim($score[$zapas][$tym]);
						}else{
							$score[$zapas][$tym] = NULL;
						}
					}
				}
			}
			return $score;
		}

		# Zjistí v vrátí UserFriendly chybové hlášení
		private function zjistiChybu($score){
			if ($this -> jsouKladne($score)){
				$chyba = "<p>Nechci ti říkat že jsi pomalý ale ten pán co tu byl před chvilkou byl rychlejší než ty a to znamená že se buď vrátíš na předchozí stránku a nebo na kontrolu zápasů. Pro příště doporučuji být rychlejší a nenastanou potíže.</p>";
			}else{				
				$chyba = "<p>Nevím jak chceš zapsat záporné skóre, ale takhle to teda nepůjde. Vrať se na předchozí stránku a zkus to znovu.</p>";
			}
			$_SESSION['chyba'] = $chyba;
		}


	}