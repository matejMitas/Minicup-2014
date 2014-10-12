<?php
$template = "tabulky.latte";


$parametry["rozlosovani"] = array(
    "mladsi" => array(
        $VystupVysledkuML->ziskejRozlosovaniZapasu("2014-05-23 00:00:00"),
        $VystupVysledkuML->ziskejRozlosovaniZapasu("2014-05-24 00:00:00"),
        $VystupVysledkuML->ziskejRozlosovaniZapasu("2014-05-25 00:00:00")),
    "starsi" => array(
        $VystupVysledkuST->ziskejRozlosovaniZapasu("2014-05-23 00:00:00"),
        $VystupVysledkuST->ziskejRozlosovaniZapasu("2014-05-24 00:00:00"),
        $VystupVysledkuST->ziskejRozlosovaniZapasu("2014-05-25 00:00:00"))
);

$parametry["odehraneZapasy"] = array(
    "mladsi" => array(
        $VystupVysledkuML->ziskejOdehraneZapasy("2014-05-23 00:00:00"),
        $VystupVysledkuML->ziskejOdehraneZapasy("2014-05-24 00:00:00"),
        $VystupVysledkuML->ziskejOdehraneZapasy("2014-05-25 00:00:00")),
    "starsi" => array(
        $VystupVysledkuST->ziskejOdehraneZapasy("2014-05-23 00:00:00"),
        $VystupVysledkuST->ziskejOdehraneZapasy("2014-05-24 00:00:00"),
        $VystupVysledkuST->ziskejOdehraneZapasy("2014-05-25 00:00:00"))
);
