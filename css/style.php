<?php
require "scss.inc.php";
$scssCompiler = new scssc();


if (!isset($_GET['file']) || !file_exists("{$_GET['file']}")) throw new Exception("Soubor neexistuje nebo není zadan v parametru!",1);

$scss = file_get_contents("{$_GET['file']}");
header('Content-type: text/css');
echo $scssCompiler->compile($scss);