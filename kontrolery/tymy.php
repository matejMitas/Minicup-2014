<?php

use Nette\Templating\FileTemplate;


if (isset($_GET["cat"],$_GET["id"])) {
    $template = new FileTemplate('sablony/detail.latte'); 
    $DetailTymu = new DetailTymu($_GET["cat"], $_GET["id"]);
    $template->jmeno = $DetailTymu->ziskejNazevTymu();
    $template->data = $DetailTymu->ziskejPoradiSkore();
    $DetailTymu->ziskejProcentualniUspech();
    $template->zapasy = $DetailTymu->ziskejOdehraneZapasy();
} else {
    $template = new FileTemplate('sablony/tymy.latte');
    $template->tymy = Array("mladsi" => DetailTymu::ziskejVsechnyTymy("mladsi"), "starsi" => DetailTymu::ziskejVsechnyTymy("starsi"));
}


// $VkladacZapasu = new VkladacZapasu('mladsi', 4);
