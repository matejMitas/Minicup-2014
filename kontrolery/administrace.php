<?php

use Nette\Templating\FileTemplate;
$template = new FileTemplate('sablony/administrace.latte');


if (!isset($_SESSION["logged"]) || $_SESSION["logged"] === false) {
	header("Location: login");
}


$novinkovac = new Novinkovac();
if (isset($_POST['titulek'],$_POST['aktualita'])) {
    $novinkovac->vlozNovinku($_POST['titulek'],$_POST['aktualita']);
    header("Location: novinky");
}

$template->NN = $novinkovac->ziskejVkladaciFormular();


$Prepocet = new Prepocet("mladsi");
$Prepocet->aktualizujBody();
$Prepocet->serad();

$Prepocet = new Prepocet("starsi");
$Prepocet->aktualizujBody();
$Prepocet->serad();