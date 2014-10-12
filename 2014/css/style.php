<?php
require "scss.inc.php";
$scssCompiler = new scssc();



if (!isset($_GET['file']) || !file_exists("{$_GET['file']}")) {
	throw new Exception("Soubor neexistuje nebo není zadan v parametru!",1);
}

if ($_SERVER['SERVER_ADDR'] <> "127.0.0.1") {
	$scssCompiler->setFormatter("scss_formatter_compressed");
}


$scss = file_get_contents("{$_GET['file']}");
header('Content-type: text/css');
echo $scssCompiler->compile($scss);