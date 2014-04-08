<?php

use Nette\Templating\FileTemplate;
$template = new FileTemplate('sablony/admin.latte');
/*
$novinkovac = new Novinkovac();
if (isset($_POST['titulek'],$_POST['aktualita'])) {
    $novinkovac->vlozNovinku($_POST['titulek'],$_POST['aktualita']);
    $_SESSION["zprava"]="Novinka úspěšně vložena!";
    header("Location: {$_SERVER['PHP_SELF']}");
}

$template->form = $novinkovac->ziskejVkladaciFormular();
*/