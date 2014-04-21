<?php
$time_start=microtime(True);
function __autoload($trida){
    require_once("tridy/$trida.class.php");
}
spl_autoload_register("__autoload");
require 'NetteInit.php';
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

if (isset($_GET["controller"])) {
    if (isset($controllers[$_GET["controller"]])) {
        include "kontrolery/". $_GET["controller"] .".php";
        $title = $controllers[$_GET["controller"]];
    } elseif (in_array($_GET["controller"], Array("login","administrace"))) {
        include "kontrolery/". $_GET["controller"] .".php";
    } else {
        header("Location: /minicup/2014/");
    }
} else {
    include "kontrolery/novinky.php";
    $title = null;
}

$template->tabulka = array("mladsi" => $VystupVysledkuML->ziskejTabulkuVysledku(),
                            "starsi" => $VystupVysledkuST->ziskejTabulkuVysledku());
$template->praveHrane = array("mladsi" => $VystupVysledkuML->ziskejPraveHraneZapasy(),
                            "starsi" => $VystupVysledkuST->ziskejPraveHraneZapasy());
$template->nasledujici = array("mladsi" => $VystupVysledkuML->ziskejNasledujiciZapasy(),
                            "starsi" => $VystupVysledkuST->ziskejNasledujiciZapasy());
$template->title = $title;
$template->basePath = "Minicup-2014";





$template->setCacheStorage(new Nette\Caching\Storages\PhpFileStorage('sablony/cache'));
$template->onPrepareFilters[] = function($template) {
    $template->registerFilter(new Nette\Latte\Engine);
};
$template->registerHelperLoader('Nette\Templating\Helpers::loader');
$template->registerHelper('relDateCZ', function ($time) {
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

$template->menu = $controllers;


$template->time = number_format((microtime(True)-$time_start)*1000,0);

$template->render();

