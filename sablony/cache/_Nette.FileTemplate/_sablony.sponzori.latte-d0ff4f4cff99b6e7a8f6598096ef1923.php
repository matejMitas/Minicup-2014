<?php //netteCache[01]000383a:2:{s:4:"time";s:21:"0.09095100 1396544857";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:63:"/Users/matejmitas/Documents/Minicup 2014/sablony/sponzori.latte";i:2;i:1396544852;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:28:"$WCREV$ released on $WCDATE$";}}}?><?php

// source file: /Users/matejmitas/Documents/Minicup 2014/sablony/sponzori.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'zfrgqqle66')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block main
//
if (!function_exists($_l->blocks['main'][] = '_lbc7611db67b_main')) { function _lbc7611db67b_main($_l, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?><section>
		<div id="content-padding">

			<h2>Sponzoři</h2>

			<ul class="sponzori boxes">
				<li><a href="%"><img src="img/pics_sponzori/CeskaPojistovna.png" alt=""></a></li>
				<li><img src="img/pics_sponzori/EZ.png" alt=""></li>
				<li><img src="img/pics_sponzori/Hummel.png" alt=""></li>
				<li><img src="img/pics_sponzori/Lesy.png" alt=""></li>
				<li><img src="img/pics_sponzori/Nutrend.png" alt=""></li>
				<li><img src="img/pics_sponzori/OlomouckyKraj.png" alt=""></li>
				<li><img src="img/pics_sponzori/Whirpool.png" alt=""></li>
				<li><img src="img/pics_sponzori/ZSVitezna.png" alt=""></li>
				<li><img src="img/pics_sponzori/MestoLitovel.png" alt=""></li>
				<li><img src="img/pics_sponzori/aaa.png" alt=""></li>
				<li><img src="img/pics_sponzori/Pojistovna.png" alt=""></li>
				<li><img src="img/pics_sponzori/Metal.png" alt=""></li>
				<li><img src="img/pics_sponzori/Pento.png" alt=""></li>
				<li><img src="img/pics_sponzori/Solac.png" alt=""></li>
				<li><img src="img/pics_sponzori/Sev.png" alt=""></li>
				<li><img src="img/pics_sponzori/Albi.png" alt=""></li>
			</ul>
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