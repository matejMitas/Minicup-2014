<?php

$time_start=microtime(True);

function __autoload($trida){
    require_once("tridy/$trida.class.php");
}
spl_autoload_register("__autoload");

require 'tridy/dbWrapper.class.php';
dbWrapper::pripoj();



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

$content .= $novinkovac->ziskejNovinky(5);
$content .= "<hr><h2>Přidání novinky</h2>";
$content .= $novinkovac->ziskejVkladaciFormular();
$content .= "<hr><h2>Detail týmu {$DetailTymu->ziskejNazevTymu()}</h2>";
$content .= $DetailTymu->ziskejOdehraneZapasy();
$content .= $DetailTymu->ziskejPoradiSkore();
$content .= "<hr><h2>Přidání zápasů</h2>";
$content .= $VkladacZapasu -> ziskejFormular($_POST);



$sablona = new FileTemplate('template.latte'); // soubor se šablonou
$sablona->name = 'John';
$sablona->render(); // vykreslí šablonu




$vystup = <<<SABLONA
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MINICUP 2014</title>
    
    <link type="text/css" rel="stylesheet" href="css/dem.css">
    <link type="text/css" rel="stylesheet" href="css/jquery-te-1.4.0.css">

    <script language="javascript" type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.js"></script>
    
    <script type="text/javascript" src="js/jquery-te-1.4.0.min.js"></script>
						

</head>
<body>
    $content
</body>
</html>
SABLONA;
echo($vystup);
echo("<i>Vygenerováno za ". number_format((microtime(True)-$time_start)*1000,2)."ms.</i>");



