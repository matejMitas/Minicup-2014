<?php
$template = 'administrace.latte';

if (!isset($_SESSION["logged"]) || $_SESSION["logged"] === false) {
    header("Location: login");
}

if (isset($_GET["cat"]) && in_array($_GET["cat"], Array("mladsi","starsi"))) {
    $parametry["title"] = "VKLÁDÁNÍ výsledků kategorie {$_GET["cat"]}";
    try {
        $vkladac = new VkladacZapasu($_GET["cat"], 6);
        $parametry["form"] = $vkladac->ziskejFormular($_POST);
    } catch (Exception $e) {
        $parametry["form"] = $e->getMessage();
    }
    
    
    $Prepocet = new Prepocet($_GET["cat"]);
    $Prepocet->refreshPoints();
    $Prepocet->order();
} elseif (isset($_GET["cat"]) && $_GET["cat"] == "fotky" ) {
    $parametry["title"] = "vložení fotek";
    $template = 'fotky.latte';
} else {
    $novinkovac = new Novinkovac();
    if (isset($_POST['titulek'], $_POST['aktualita'])) {
        $novinkovac->vlozNovinku($_POST['titulek'], $_POST['aktualita']);
        header("Location: novinky");
    }
    $parametry["form"] = $novinkovac->ziskejVkladaciFormular();
    $parametry["title"] = "vkládání novinky";
}


