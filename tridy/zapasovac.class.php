<?php
    class zapasovac {
        
    private $zapasy;
        
    public function nacteniZapasu($pocet){
        require('dbWrapper.class.php'); #Může se smazat
        dbWrapper::pripoj();
        $sql = dbWrapper::dotaz(<<<SQL
                SELECT `ID_domaci`, `ID_hoste`
                FROM `2014_zapasy_mladsi`
                WHERE `odehrano` = 0
                ORDER BY `cas_odehrani`
                LIMIT $pocet
SQL
        ,Array());
        $this -> zapasy = $sql->FetchAll();
    }
        
        
    public function tym($zapas, $tym){
        $parametr = $this -> zapasy[$zapas][$tym];
        $sql = dbWrapper::dotaz(<<<SQL
                SELECT jmeno
                FROM `2014_tymy_mladsi`
                WHERE id_teamu = ?
SQL
        ,Array($parametr));
        $vysledek = $sql->FetchAll();
        $team = $vysledek[0]['jmeno'];
        return $team;
    }


    public function zjiskejOverovaciFormular($pole){
        $formular = <<<FORMULAR
            <table>
FORMULAR;
        for ($zapas=0; $zapas < 4; $zapas++) {
            echo $zapas;
            if (preg_match('/^[0-9]+$/', $_POST['score'][$zapas][0])){
                if (preg_match('/^[0-9]+$/', $_POST['score'][$zapas][1])){
                    $domaci = $this->tym($zapas,0);
                    $hoste = $this->tym($zapas,1);
                    $skoreDomaci = $_POST['score'][$zapas][0];
                    $skoreHoste = $_POST['score'][$zapas][1];
                    $formular .= <<<FORMULAR
                        <tr>
                            <td><p>$domaci</p></td>
                            <td><p>$hoste</p></td>
                        </tr>        
                        <tr>
                            <td><p>$skoreDomaci</p></td>
                            <td><p>$skoreHoste</p></td>
                        </tr>
FORMULAR;
                }
            }
        }
        $formular .= <<<FORMULAR
            </table>
FORMULAR;
        return $formular;
    }
}