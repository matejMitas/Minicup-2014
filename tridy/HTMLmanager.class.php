<?php
class HTMLmanager {

	private $stranka;
	//stranka udava aktualni stranku pro generovani
	private $vystup = "";
	
	private $sablona;
	
	public function __construct($stranka = 'zapasy') {
		$this -> stranka = $stranka;
		$sablona= "<!DOCTYPE HTML>
		<HEAD>
    		<META charset='UTF-8'>
    		<TITLE>MINICUP 2014 \$titulek </TITLE>
  		</HEAD>
  		<BODY>
     		\$vystup
  		</BODY>
	</HTML>";
	
	}

	public function vystupSablona() {//vrátí zformatovaný content
		return $this->sablona;}
	
	public function vystupTitulek() {
		return 'Zápasy';
	}
	public function vystupObsah(){
				$vystupZapasy = new vystupZapasy();
				$vysledek = $vystupZapasy -> vystupArray();

				$vystup = '<table border=1>';
				While ($radek = $vysledek -> fetch(PDO::FETCH_ASSOC)) {//odchyt řádku
					$vystup .= '<tr>';
					foreach ($radek as $klic => $hodnota) {//odchyt hodnot z řádku
						$vystup .= "<td> $hodnota </td>";
					}
					$vystup .= '</tr>';
				}
				$vystup .= '</table>';
				
				return $vystup;
		
	}
}
