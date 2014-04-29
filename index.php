<?php

$basePath = "Minicup-2014";
function __autoload($trida){
    require_once("sources/$trida.class.php");
}
spl_autoload_register("__autoload");



include("sources/latte.php");
use Tracy\Debugger;
include("sources/tracy.php");
Debugger::enable(Debugger::DETECT);


session_start();
dbWrapper::pripoj();
$controllers = array(
    "novinky" => "novinky",
    "informace" => "informace",
    "tabulky" => "tabulky",
    "tymy" => "týmy",
    "fotogalerie" => "fotogalerie",
    "sponzori" => "sponzoři",
    "kontakt" => "kontakt"
    );



$VystupVysledkuML = new VystupVysledku("mladsi");
$VystupVysledkuST = new VystupVysledku("starsi");
//Debugger::barDump(true);

if (isset($_GET["controller"])) {
    if (isset($controllers[$_GET["controller"]])) {
        if (file_exists("kontrolery/". $_GET["controller"] .".php")) {
           include "kontrolery/". $_GET["controller"] .".php";
        } else {
            $template = $_GET["controller"].".latte";
        }
        $title = $controllers[$_GET["controller"]];
    } elseif (in_array($_GET["controller"], Array("login","administrace","logout"))) {
        include "kontrolery/". $_GET["controller"] .".php";
    } else {
        header("HTTP/1.0 404 Not Found");
        header("Location: /{$basePath}/");
        die();
    }
} else {
    include "kontrolery/novinky.php";
    $title = null;
}

$parametry["basePath"] = $basePath;
$parametry["tabulka"] = array("mladsi" => $VystupVysledkuML->ziskejTabulkuVysledku(),
                            "starsi" => $VystupVysledkuST->ziskejTabulkuVysledku());
$parametry["praveHrane"] = array("mladsi" => $VystupVysledkuML->ziskejPraveHraneZapasy(),
                            "starsi" => $VystupVysledkuST->ziskejPraveHraneZapasy());
$parametry["nasledujici"] = array("mladsi" => $VystupVysledkuML->ziskejNasledujiciZapasy(6),
                            "starsi" => $VystupVysledkuST->ziskejNasledujiciZapasy(6));
$parametry["title"] = !isset($parametry["title"]) ? $title : $parametry["title"];

$parametry["menu"] = $controllers;

$latte = new Latte\Engine;
$latte->addFilter('relDateCZ', function ($time) {
        $seconds = time() - strtotime($time);
        $minutes = floor($seconds / 60);
        $hours = floor($minutes / 60);
        $days = floor($hours / 24);
        $months = floor($days / 30);
        $years = floor($days / 365);
        if ($years >= 2) {
            return "před $years lety";
        } elseif ($years == 1) {
            return "před rokem";
        } elseif ($months >= 2) {
            return "před $months měsíci";
        } elseif ($months == 1) {
            return "před měsícem";
        } elseif ($days >= 2) {
            return "před $days dny";
        } elseif ($hours >= 2) {
            return "před $hours hodinami";
        } elseif ($hours == 1) {
            return "před hodinou";
        } elseif ($minutes >= 2) {
            return "před $minutes minutami";
        } elseif ($minutes == 1) {
            return "před minutou";
        } elseif ($seconds >= 0) {
            return "před chvílí";
		} else {
            return "v budoucnu";
        }});

$latte->render("sablony/".$template, $parametry);

?>