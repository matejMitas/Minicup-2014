<?php //netteCache[01]000386a:2:{s:4:"time";s:21:"0.84697300 1396544585";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:66:"/Users/matejmitas/Documents/Minicup 2014/sablony/fotogalerie.latte";i:2;i:1396025788;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:28:"$WCREV$ released on $WCDATE$";}}}?><?php

// source file: /Users/matejmitas/Documents/Minicup 2014/sablony/fotogalerie.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 't48hjeha41')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block main
//
if (!function_exists($_l->blocks['main'][] = '_lbae7075c67a_main')) { function _lbae7075c67a_main($_l, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?><section>
<div id="content-padding">
<h2>Bude zveřejněno!</h2>
</div>
</section>
<?php
}}

//
// end of blocks
//

// template extending and snippets support

$_l->extends = '@layout.latte'; $template->_extended = $_extended = TRUE;


if ($_l->extends) {
	ob_start();

} elseif (!empty($_control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($_control, $_l, get_defined_vars());
}

//
// main template
//
 if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['main']), $_l, get_defined_vars()) ; 