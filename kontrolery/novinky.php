<?php

$template = 'novinky.latte';

$novinkovac = new Novinkovac();
$parametry["novinky"] = $novinkovac->ziskejNovinky(3);