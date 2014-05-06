<?php

$template = 'fotogalerie.latte';
$foto = Array();
foreach (Array("patek", "sobota", "nedele") as $key => $den) {
    if (file_exists(__DIR__ . "/../upload/$den/$den.txt") AND FALSE) {
        $foto[$den] = file_get_contents(__DIR__ . "/../upload/$den/$den.txt");
    } else {
        $latte = new Latte\Engine;
        $params["den"] = $den;

        $album = dir(__DIR__ . "/../upload/$den"); 
        while ($fotka = $album->read()) { 
            if ($fotka == "." || $fotka == "..") continue; //nadrazena
            if ($fotka=="$den.txt") continue; //info soubor
            if (substr($fotka, -1) == "t") continue; //part
            $params["fotoList"][] = $fotka;
        }
        $params["basePath"] = $basePath;
        $html = $latte->renderToString(__DIR__ . "/../sablony/fotoPart.latte", $params);
        file_put_contents(__DIR__ . "/../upload/$den/$den.txt", $html);
        $foto[$den] = $html;
    }
}
$parametry["foto"] = $foto;
