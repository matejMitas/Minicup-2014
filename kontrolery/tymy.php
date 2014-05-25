<?php

if (isset($_GET["cat"],$_GET["id"])) {
    $template = 'detail.latte'; 
    $DetailTymu = new DetailTymu($_GET["cat"], $_GET["id"]);

    $parametry["jmeno"] = $DetailTymu->ziskejNazevTymu();
    $parametry["data"] = $DetailTymu->ziskejPoradiSkore();
    $DetailTymu->ziskejProcentualniUspech();
    $parametry["zapasy"] = $DetailTymu->ziskejZapasy();

} else {
    $template = 'tymy.latte';
    $parametry["tymy"] = Array("mladsi" => DetailTymu::ziskejVsechnyTymy("mladsi"), "starsi" => DetailTymu::ziskejVsechnyTymy("starsi"));
}


// $VkladacZapasu = new VkladacZapasu('mladsi', 4);
