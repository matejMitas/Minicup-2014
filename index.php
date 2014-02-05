<?php
$time_start=microtime(True);

function __autoload($trida){
    require_once("tridy/$trida.class.php");
}
spl_autoload_register("__autoload");

/*
require 'tridy/dbWrapper.class.php';
require 'tridy/Novinkovac.class.php';
require 'tridy/VystupZapasu.class.php';
require 'tridy/DetailTymu.class.php';
require 'tridy/Prepocet.class.php';
require 'tridy/VkladacZapasu.class.php';
*/
dbWrapper::pripoj();

require 'NetteInit.php';


$zprava="";
if (isset($_SESSION["zprava"])){
    $zprava=<<<JAVASCRIPT
    <script type="text/javascript">
        alert("Nějaká zpráva: {$_SESSION["zprava"]}");
    </script>
JAVASCRIPT;
    unset($_SESSION["zprava"]);
}


$novinkovac = new Novinkovac();
$content = "";
if (isset($_POST['titulek'],$_POST['aktualita'])) {
    $novinkovac->vlozNovinku($_POST['titulek'],$_POST['aktualita']);
    $_SESSION["zprava"]="Novinka úspěšně vložena!";
    header("Location: {$_SERVER['PHP_SELF']}");
}

$VystupZapasu = new VystupZapasu();
$VkladacZapasu = new VkladacZapasu('mladsi', 4);
$DetailTymu = new DetailTymu('mladsi',1);
$Prepocet = new Prepocet("mladsi");
$Prepocet->aktualizujBody();
$Prepocet->serad();

$content .= $novinkovac->ziskejNovinky(3);
$content .= "<hr><h2>Přidání novinky</h2>";
$content .= $novinkovac->ziskejVkladaciFormular();
$content .= "<hr><h2>Detail týmu {$DetailTymu->ziskejNazevTymu()}</h2>";

$content .= "<hr><h2>Přidání zápasů</h2>";
$content .= $VkladacZapasu->ziskejFormular($_POST);
$content .= $VystupZapasu->ziskejOdehraneZapasy("2014-05-23 00:00:00");








// echo($vystup);
// echo("<i>Vygenerováno za ". number_format((microtime(True)-$time_start)*1000,2)."ms.</i>");

use Nette\Templating\FileTemplate;

$template = new FileTemplate('sablony/template.latte'); // soubor se šablonou
$template->setCacheStorage(new Nette\Caching\Storages\PhpFileStorage('sablony/cache'));
$template->onPrepareFilters[] = function($template) {
    $template->registerFilter(new Nette\Latte\Engine);
};
$template->registerHelperLoader('Nette\Templating\Helpers::loader');
$template->title = 'John';
$template->content = $content;
$template->asideTop = $DetailTymu->ziskejPoradiSkore();
$template->asideContent = $DetailTymu->ziskejOdehraneZapasy();
$template->title = $DetailTymu->ziskejNazevTymu();
$template->render(); // vykreslí šablonu

