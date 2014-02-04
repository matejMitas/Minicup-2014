<?php //netteCache[01]000350a:2:{s:4:"time";s:21:"0.04325000 1391544666";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:30:"C:\XAMPP\htdocs\template.latte";i:2;i:1391543616;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:28:"$WCREV$ released on $WCDATE$";}}}?><?php

// source file: C:\XAMPP\htdocs\template.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'l45404s7mp')
;
// prolog Nette\Latte\Macros\UIMacros

// snippets support
if (!empty($_control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($_control, $_l, get_defined_vars());
}

//
// main template
//
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MINICUP 2014</title>
    
    <link type="text/css" rel="stylesheet" href="css/dem.css">
    <link type="text/css" rel="stylesheet" href="css/jquery-te-1.4.0.css">

    <script language="javascript" type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="js/jquery-te-1.4.0.min.js"></script>
</head>
<body>
    <?php echo Nette\Templating\Helpers::escapeHtml($content, ENT_NOQUOTES) ?>

</body>
</html>