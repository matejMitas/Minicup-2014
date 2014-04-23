<?php

use Nette\Templating\FileTemplate;

$template = new FileTemplate("sablony/administrace.latte");

if (!isset($_SESSION["logged"]) || $_SESSION["logged"] === false) {
    header("Location: login");
}

if (isset($_GET["cat"])) {
    $template->title = "VKLÁDÁNÍ výsledků kategorie {$_GET["cat"]}";
    try {
        $vkladac = new VkladacZapasu($_GET["cat"], 6);
        $template->form = $vkladac->ziskejFormular($_POST);
    } catch (Exception $e) {
        $template->form = $e->getMessage();
    }
    
    
    $Prepocet = new Prepocet($_GET["cat"]);
    $Prepocet->aktualizujBody();
    $Prepocet->serad();
} else {
    $novinkovac = new Novinkovac();
    if (isset($_POST['titulek'], $_POST['aktualita'])) {
        $novinkovac->vlozNovinku($_POST['titulek'], $_POST['aktualita']);
        header("Location: novinky");
    }
    $template->form = $novinkovac->ziskejVkladaciFormular();
    $template->title = "vkládání novinky";
}


