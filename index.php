<?php
$time_start=microtime(True);
function __autoload($trida){
    require_once("tridy/$trida.class.php");
}
spl_autoload_register("__autoload");

dbWrapper::pripoj();

require 'NetteInit.php';


$controllers = array(
    "novinky" => "novinky",
    "informace" => "informace",
    "tabulky" => "tabulky",
    "tymy" => "týmy",
    "fotogalerie" => "fotogalerie",
    "sponzori" => "sponzoři",
    "kontakt" => "kontakt"
    );

if (isset($_GET["controller"], $controllers[$_GET["controller"]])) {
    $controllerPath = "kontrolery/". $_GET["controller"] .".php";
    if (file_exists($controllerPath)) {
        include $controllerPath;
    }
} else {
    include "kontrolery/novinky.php";
}

$VystupVysledkuML = new VystupVysledku("mladsi");
$VystupVysledkuST = new VystupVysledku("starsi");
$template->tabulka = array("mladsi" => $VystupVysledkuML->ziskejTabulkuVysledku());
$template->praveHrane = array("mladsi" => $VystupVysledkuML->ziskejPraveHraneZapasy());




/* if (isset($_POST['titulek'],$_POST['aktualita'])) {
    $novinkovac->vlozNovinku($_POST['titulek'],$_POST['aktualita']);
    $_SESSION["zprava"]="Novinka úspěšně vložena!";
    header("Location: {$_SERVER['PHP_SELF']}");
} */




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
if (isset($_SESSION["zprava"])){
    $template->alert = "Nějaká zpráva: {$_SESSION['zprava']}";
    unset($_SESSION["zprava"]);
}


$template->menu = $controllers;



$VkladacZapasu = new VkladacZapasu('mladsi', 4);
$DetailTymu = new DetailTymu('mladsi', rand(1,12));
$Prepocet = new Prepocet("mladsi");




$Prepocet->aktualizujBody();
$Prepocet->serad();

$template->title = $DetailTymu->ziskejNazevTymu();

$template->asideTop = $DetailTymu->ziskejPoradiSkore()." Úspěšnost: ".$DetailTymu->ziskejProcentualniUspech()." %";
$template->asideContent = $DetailTymu->ziskejOdehraneZapasy();

$template->time = "<i>Vygenerováno za ". number_format((microtime(True)-$time_start)*1000,2)."ms.</i>";

$template->render(); // vykreslí šablonu

