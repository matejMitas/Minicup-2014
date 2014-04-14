<?php

use Nette\Templating\FileTemplate;
$template = new FileTemplate('sablony/tymy.latte');


// $VkladacZapasu = new VkladacZapasu('mladsi', 4);
$DetailTymu = new DetailTymu('mladsi', rand(1,12));
$DetailTymu->ziskejNazevTymu();
$DetailTymu->ziskejPoradiSkore()." Úspěšnost: ".$DetailTymu->ziskejProcentualniUspech()." %";
$DetailTymu->ziskejOdehraneZapasy();
