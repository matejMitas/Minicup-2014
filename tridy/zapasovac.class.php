<?php
    class zapasovac {
        
        private static $zapasy;
        
        public function nacteniZapasu($pocet){
            require('dbWrapper.class.php');
            dbWrapper::pripoj();
            $sql = dbWrapper::dotaz(<<<SQL
                    SELECT `ID_domaci`, `ID_hoste`
                    FROM `2014_zapasy_mladsi`
                    WHERE `odehrano` = 0
                    ORDER BY `cas_odehrani`
                    LIMIT $pocet
SQL
        ,Array());
            self::$zapasy = $sql->FetchAll();
        }
        
        
        public function tym($zapas, $tym){
            $parametr = self::$zapasy[$zapas][$tym];
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
            
    
}
