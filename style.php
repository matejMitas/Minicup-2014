<?php
require "css/scss.inc.php";
$scssCompiler = new scssc();

$slozka = "css";

if (!isset($_GET['file']) || !file_exists("$slozka/{$_GET['file']}")) die("Soubor neexistuje nebo není zadan v parametru!");

$scss = file_get_contents("$slozka/{$_GET['file']}");

echo $scssCompiler->compile("$scss");