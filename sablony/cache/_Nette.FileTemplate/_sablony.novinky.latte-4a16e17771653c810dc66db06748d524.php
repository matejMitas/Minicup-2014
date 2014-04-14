<?php //netteCache[01]000382a:2:{s:4:"time";s:21:"0.88501300 1396544565";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:62:"/Users/matejmitas/Documents/Minicup 2014/sablony/novinky.latte";i:2;i:1396025788;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:28:"$WCREV$ released on $WCDATE$";}}}?><?php

// source file: /Users/matejmitas/Documents/Minicup 2014/sablony/novinky.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'kie1dfzyb6')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block main
//
if (!function_exists($_l->blocks['main'][] = '_lb18c11f65ad_main')) { function _lb18c11f65ad_main($_l, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?><div id="article-wraper">
<?php $iterations = 0; foreach ($novinky as $novinka) { ?>
	<article>
		<div id="content-padding">
			<div class="article-text">
				<h4>přidáno <span class="article-date"><?php echo Nette\Templating\Helpers::escapeHtml($template->relDateCZ($novinka["vlozeno"]), ENT_NOQUOTES) ?></span></h4>

				<h2><?php echo Nette\Templating\Helpers::escapeHtml($novinka["titulek"], ENT_NOQUOTES) ?></h2>

				<h3><?php echo $novinka["aktualita"] ?></h3>
			</div>
			
			<div class="article-icon">
				<svg width="100" height="100">
	  				<image xlink:href="img/prispevky-icon/foto.svg" src="img/logo.png" width="100" height="100"></image>
				</svg>
			</div>	
		</div>
	</article>
<?php $iterations++; } ?>
</div>
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
// ?>

<?php if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['main']), $_l, get_defined_vars()) ; 