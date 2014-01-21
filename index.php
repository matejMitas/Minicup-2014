<?php
 function __autoload($trida)
{
        require("tridy/$trida.class.php");
}
spl_autoload_register("__autoload");
dbWrapper::pripoj();



$novinkovac = new Novinkovac();
$content = "";
if (isset($_POST['titulek'],$_POST['aktualita'])) {
	$novinkovac->vlozNovinku($_POST['titulek'],$_POST['aktualita']);
	header("Location: {$_SERVER['PHP_SELF']}");
}
$VystupZapasu = new VystupZapasu();

$content .= $novinkovac->ziskejNovinky(5);
$content .= "<hr><h2>Přidání novinky</h2>";
$content .= $novinkovac->ziskejVkladaciFormular();
$content .= "<hr><h2>Odehráné zápasy</h2>";
$content .= $VystupZapasu->ziskejOdehraneZapasy("23-05-14");




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