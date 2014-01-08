<?php
 function __autoload($trida)
{
        require("tridy/$trida.class.php");
}
spl_autoload_register("__autoload");

dbWrapper::pripoj();



$novinkovac = new novinkovac();
$content = "";
if (isset($_POST['titulek'],$_POST['aktualita'])) {
	$novinkovac->vlozNovinku($_POST['titulek'],$_POST['aktualita']);
	header("Location: {$_SERVER['PHP_SELF']}");
}

$content .= $novinkovac->ziskejNovinky(3);
$content .= "<hr><h2>Přidání novinky</h2>";
$content .= $novinkovac->ziskejVkladaciFormular();



$vystup = <<<SABLONA
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MINICUP 2014</title>
    
    <link type="text/css" rel="stylesheet" href="css/dem.css">
    <link type="text/css" rel="stylesheet" href="css/jquery-te-1.4.0.css">

    <script language="javascript" type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.js"></script>
    
    <script type="text/javascript" src="js/jquery-te-1.4.0.js"></script>
						

</head>
<body>
    $content
</body>
</html>
SABLONA;
echo($vystup);