<?php
	
	class VkladacZapasu{

		private $poleZapasu;
		private $pocetZapasu;
		private $kategorie;


		public function __construct($kategorie, $pocetZapasu){
			require('dbWrapper.class.php'); #Může se smazat
        	dbWrapper::pripoj(); #také smazat
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


		public function overovaciFormular($post){
			$_SESSION['ID'] = $post;
			print_r($_SESSION);
			$formular = "";
			for ($zapas=0; $zapas < $this -> pocetZapasu; $zapas++){
				if (isset($this -> poleZapasu[$zapas][2])){
					$scoreDomaci = trim($post['score'][$zapas][0]);
					$scoreHoste = trim($post['score'][$zapas][1]);
					if (is_numeric($scoreDomaci) && is_numeric($scoreHoste)){
						$formular .= $this -> zvyrazniTymVyhercu($zapas, $scoreDomaci, $scoreHoste);
						$formular .= "<p>{$scoreDomaci}:{$scoreHoste}</p>";
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


		public function zapisDB($post){
			$zapasyKategorie = "2014_zapasy_" . $this -> kategorie;
        	$tymyKategorie = "2014_tymy_" . $this -> kategorie;
        	$idTeamu = array("ID_domaci", "ID_hoste");
			for ($zapas=0; $zapas < $this -> pocetZapasu; $zapas++){
				if (isset($this -> poleZapasu[$zapas][2])){
					$scoreDomaci = trim($post['score'][$zapas][0]);
					$scoreHoste = trim($post['score'][$zapas][1]);
					if (is_numeric($scoreDomaci) && is_numeric($scoreHoste)){
						$bodyTym = $this -> ziskaneBody($scoreDomaci, $scoreHoste);
						$sql = dbWrapper::dotaz(<<<SQL
							UPDATE $zapasyKategorie 
							SET `SCR_domaci`=?,`body_domaci`=?,`SCR_hoste`=?,`body_hoste`=?,`cas_vlozeni`=NOW(),`odehrano`='1'
							WHERE `ID_zapasu`=?
SQL
						, Array($scoreDomaci, $bodyTym[0], $scoreHoste, $bodyTym[1], $post['id'][$zapas]));
						for ($tym=0; $tym < 2; $tym++){ 
							if ($bodyTym[$tym] != 0){
								$sql = dbWrapper::dotaz(<<<SQL
									UPDATE `2014_tymy_mladsi` a
									INNER JOIN `2014_zapasy_mladsi` b ON
										a.`id_teamu` = b.{$idTeamu[$tym]}
									SET `body`=`body`+ ?
									WHERE b.`ID_zapasu` = ?
SQL
								, array($bodyTym[$tym], $post['id'][$zapas]));
							}
						}
					}
				}
			}
		}


		public function ziskaneBody($scoreDomaci, $scoreHoste){
			if ($scoreDomaci > $scoreHoste){
				$body = array(BODY_VYHRA, BODY_PROHRA);
			}elseif ($scoreDomaci == $scoreHoste){
				$body = array(BODY_REMIZA, BODY_REMIZA);
			}elseif ($scoreDomaci < $scoreHoste){
				$body = array(BODY_PROHRA, BODY_VYHRA);
			}
			return $body;
		}


		public function zvyrazniTymVyhercu($idZapasu, $scoreDomaci, $scoreHoste){
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


		public function byloZapsano($idZapasu){
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


	}