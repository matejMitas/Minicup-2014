<?php
    class dbWrapper {

    private static $spojeni;

    private static $nastaveni = Array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'");

    public static function pripoj() {
    	include_once './settings.php';
        self::$spojeni = new PDO("mysql:host=$host;dbname=$databaze",$uzivatel,$heslo,self::$nastaveni);
    }

    public static function query($sql_string, $parametry = Array()) {
        $dotaz = self::$spojeni->prepare($sql_string);
        $dotaz->execute($parametry);
        return $dotaz;
    }

}
?>
