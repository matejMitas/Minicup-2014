<?php //netteCache[01]000382a:2:{s:4:"time";s:21:"0.95582500 1396544565";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:62:"/Users/matejmitas/Documents/Minicup 2014/sablony/@layout.latte";i:2;i:1396202197;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:28:"$WCREV$ released on $WCDATE$";}}}?><?php

// source file: /Users/matejmitas/Documents/Minicup 2014/sablony/@layout.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'cxhr2ewzdl')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block main
//
if (!function_exists($_l->blocks['main'][] = '_lbbbce13e7fc_main')) { function _lbbbce13e7fc_main($_l, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
;
}}

//
// end of blocks
//

// template extending and snippets support

$_l->extends = empty($template->_extended) && isset($_control) && $_control instanceof Nette\Application\UI\Presenter ? $_control->findLayoutTemplateFile() : NULL; $template->_extended = $_extended = TRUE;


if ($_l->extends) {
	ob_start();

} elseif (!empty($_control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($_control, $_l, get_defined_vars());
}

//
// main template
//
?>
<!doctype html>
<html lang="en">
<head>
    <title>Minicup 2014</title>

    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false">
    </script>

    <script src="js/map.js">
    </script>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">  
    
    <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,700&subset=latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/style.php?file=complete.scss">  


    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js">
    </script>
        
    <script>
        $(function() {
            var pull        = $('#pull');
                menu        = $('nav ul');
                menuHeight  = menu.height();

            $(pull).on('click', function(e) {
                e.preventDefault();
                menu.slideToggle();
            });

            $(window).resize(function(){
                var w = $(window).width();
                if(w > 320 && menu.is(':hidden')) {
                    menu.removeAttr('style');
                }
            });
        });
    </script>
    
    <!--[if lt IE 9]>
        <script>
          document.createElement('header');
          document.createElement('nav');
          document.createElement('section');
          document.createElement('aside');
          document.createElement('footer');
        </script>
    <![endif]-->

</head>
<body>
    <header>
        <div class="center">
            <div id="logo">
                <svg width="275" height="275">
                    <image xlink:href="img/logo.svg" src="img/logo.png" width="275" height="275"></image>
                </svg>
            </div>
            <div id="slogan">
                <h2>23. - 25. května</h2>
                <h3>2. ročník mezinárodního turnaje v miniházené</h3>
            </div>
            <div id="barton">
                <a href="http://www.barton-a-partner.cz/"><img src="img/barton.png" alt=""></a>
            </div>
        </div>
    </header>

    <nav class="clearfix">
        <div class="center">
            <ul class="clearfix">
<?php $iterations = 0; foreach ($menu as $polozka => $cz) { ?>                <li>
                    <a href="<?php echo htmlSpecialChars($template->safeurl($polozka)) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($template->upper($cz), ENT_NOQUOTES) ?></a>
                </li>
<?php $iterations++; } ?>
            </ul>
            <a href="#" id="pull">Menu</a> 
        </div>
    </nav>
<div class="center">

<?php if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['main']), $_l, get_defined_vars())  ?>
<aside>
                    <div class="toogle">
                            <span class="toggle-src" data-src="mladsi">Mladší</span>
                            /
                            <span class="toggle-src" data-src="starsi">Starší</span> 

                    </div>
                    
                    <div class="aside-padding tog toggle-target" data-target="mladsi">
                        <div class="hrane">
                            <h2>aktuálně hrané</h2>
<?php $iterations = 0; foreach ($praveHrane as $zapas) { ?>
                                <div class="zapas">
                                    <span class="domaci"><h3><?php echo Nette\Templating\Helpers::escapeHtml($zapas["0"], ENT_NOQUOTES) ?></h3></span>
                                    <span class="hoste"><h3><?php echo Nette\Templating\Helpers::escapeHtml($zapas["1"], ENT_NOQUOTES) ?></h3></span>
                                </div>
<?php $iterations++; } ?>
                        </div>
                        <div class="nasledujici">
                            <h2>následující zápasy</h2>
<?php $iterations = 0; foreach ($nasledujici as $zapas) { ?>
                                <div class="zapas">
                                    <span class="domaci"><h3><?php echo Nette\Templating\Helpers::escapeHtml($zapas["0"], ENT_NOQUOTES) ?></h3></span>
                                    <span class="hoste"><h3><?php echo Nette\Templating\Helpers::escapeHtml($zapas["1"], ENT_NOQUOTES) ?></h3></span>
                                </div>
<?php $iterations++; } ?>
                        </div>  

                        <div class="poradi">
                            <h2 class="h2">pořadí v tabulce</h2>
                            <ul class="poradi-head">
                                <li>Pořadí</li>
                                <li>Tým</li>
                                <li>Skóre</li>
                                <li>Bodů</li>
                            </ul>
                            
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($tabulka) as $klic => $tym) { ?>
                            <ul <?php if ($iterator->getCounter() == 1) { ?>class="gold"<?php } ?>

                                <?php if ($iterator->getCounter() == 2) { ?>class="silver"<?php } ?>

                                <?php if ($iterator->getCounter() == 3) { ?>class="bronze"<?php } ?>

                            >
                                <li><?php echo Nette\Templating\Helpers::escapeHtml($tym["poradi"], ENT_NOQUOTES) ?>.</li>
                                <li><?php echo Nette\Templating\Helpers::escapeHtml($tym["jmeno"], ENT_NOQUOTES) ?></li>
                                <li><?php echo Nette\Templating\Helpers::escapeHtml($tym["dane"], ENT_NOQUOTES) ?>
:<?php echo Nette\Templating\Helpers::escapeHtml($tym["dostane"], ENT_NOQUOTES) ?></li>
                                <li><?php echo Nette\Templating\Helpers::escapeHtml($tym["body"], ENT_NOQUOTES) ?></li>
                            </ul>
<?php $iterations++; } array_pop($_l->its); $iterator = end($_l->its) ?>
                            
                        </div>
                    </div>
                    </aside>
                    <script>
                        /** trida pro tlacitka */
                        var toggleButtons = ".toggle-src";

                        /** trida pro toggle elementy */
                        var toggleTargets = ".toggle-target";

                        /** trida pro highlight tlacitek */
                        var toggleActive = "active";

                        /** ovladani togglu: 
                                <element class="toggle-src" data-src="mujcil">
                            ovladane elementy:
                                <element class="toggle-target" data-target="mujcil">
                        */


                        $(toggleButtons).click(function(event){
                            var src = $(this).data("src");

                            $(toggleButtons).each(function(){
                                $(this).removeClass(toggleActive);
                            });
                            $(this).addClass(toggleActive);


                            $(toggleTargets).each(function(){
                                var target = $(this).data("target");
                                if (target == src) {
                                    $(this).show();
                                } else {
                                    $(this).hide();
                                }
                            });
                        });

                        /* $("toggle-src")
                        $("toggle-target") */


                    </script>
                

        </div>
    
<div id="mapDiv"></div>
    <footer>
            <div class="date">
                <h2>23. - 25. května</h2>
                <h3>2. ročník mezinárodního turnaje v miniházené </h3>
            </div>
            <div class="authors">Matěj Mitaš, Josef Kolář a spousta dalších lidí</div>
    </footer>
</body>
</html>