<?php

$template = 'fotogalerie.latte';
$foto = Array();
$i=0;
set_time_limit(60*60);
include __DIR__ .'/../sources/Utils/Object.php';
include __DIR__ .'/../sources/Utils/Image.php';

foreach (Array("patek", "sobota", "nedele", "vyhlaseni") as $key => $den) {
    if (file_exists(__DIR__ . "/../upload/$den/$den.txt")) { //pokud existuje HTML s fotkama
        $foto[$den] = file_get_contents(__DIR__ . "/../upload/$den/$den.txt");
    } else { //nevygenerovane -> nutnost vytvořit z fotek HTML
        $latte = new Latte\Engine;
        $params["den"] = $den;

        file_put_contents(__DIR__ . "/../upload/$den/$den.txt", "");

        $album = dir(__DIR__ . "/../upload/$den"); 
        while ($fotka = $album->read()) { //iterace vsech fotek ve slozce
            if ($fotka == "." || $fotka == "..") continue; //nadrazena
            if ($fotka=="$den.txt") continue; //info soubor
            if (substr($fotka, -5) == ".part") continue; //part
            if (substr($fotka, 0, 5) == "full_") continue; //full na produkci
            if (substr($fotka, 0, 6) == "thumb_") continue; //thumb na produkci
            $i++;
            
            $image = Image::fromFile(__DIR__ . "/../upload/$den/$fotka");
            $image->sharpen()->resize(1280, 1024, Image::SHRINK_ONLY)->save(__DIR__ . "/../upload/$den/full_$i.jpg",70,Image::JPEG); //full
            
            $image->resize(400/1.5, 270/1.5, Image::EXACT)->save(__DIR__ . "/../upload/$den/thumb_$i.jpg",100,Image::JPEG); //thumb
            $image = null;

            $params["fotoList"][] = Array("full_$i.jpg", "thumb_$i.jpg");
        }
        $params["chyba"] = count($params["fotoList"]) == 1;
        $params["basePath"] = $basePath;
        $html = $latte->renderToString(__DIR__ . "/../sablony/fotoPart.latte", $params);
        file_put_contents(__DIR__ . "/../upload/$den/$den.txt", $html);
        $foto[$den] = $html;
    }
}
$parametry["foto"] = $foto;
