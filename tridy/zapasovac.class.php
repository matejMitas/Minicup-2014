<?php
    class zapasovac {
        
        private $tymy;
        
        public function nacteniZapasu($pocet){
            require('dbWrapper.class.php');
            $vysledek = dbWrapper::dotaz(<<<SQL
                    SELECT `ID_domaci`, `ID_hoste`
                    FROM `2014_aktuality`
                    WHERE `odehrano` = 0
                    ORDER BY `cas_odehrani`
                    LIMIT ?
SQL
        ,Array($pocet));
            $tymy = $vysledek->FetchAll();
        }
        
        
        public function tym($zapas, $tym){
            $team = $tymy[$index][$team];
            
            $vysledek = dbWrapper::dotaz(<<<SQL
                    SELECT jmeno
                    FROM `2014_aktuality`
                    WHERE id_teamu = ?
SQL
        ,Array($team));
            $vysledek = $vysledek->FetchAll();
            
            $team = $vysledek[0]['jmeno'];
            
            return $team;
        }
            
    
}
