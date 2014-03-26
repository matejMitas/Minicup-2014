<?php

use Nette\Templating\FileTemplate;
$template = new FileTemplate('sablony/novinky.latte');

$novinkovac = new Novinkovac();
$template->novinky = $novinkovac->ziskejNovinky(3);