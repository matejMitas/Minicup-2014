<?php

use Nette\Templating\FileTemplate;

$template = new FileTemplate('sablony/tabulky.latte');


$template->rozlosovani = array(
    "mladsi" => array(
        $VystupVysledkuML->ziskejRozlosovaniZapasu("2014-05-23 00:00:00"),
        $VystupVysledkuML->ziskejRozlosovaniZapasu("2014-05-24 00:00:00"),
        $VystupVysledkuML->ziskejRozlosovaniZapasu("2014-05-25 00:00:00")),
    "starsi" => array(
        $VystupVysledkuST->ziskejRozlosovaniZapasu("2014-05-23 00:00:00"),
        $VystupVysledkuST->ziskejRozlosovaniZapasu("2014-05-24 00:00:00"),
        $VystupVysledkuST->ziskejRozlosovaniZapasu("2014-05-25 00:00:00"))
);
