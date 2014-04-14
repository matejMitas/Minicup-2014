<?php //netteCache[01]000384a:2:{s:4:"time";s:21:"0.78861200 1396544582";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:64:"/Users/matejmitas/Documents/Minicup 2014/sablony/informace.latte";i:2;i:1396025788;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:28:"$WCREV$ released on $WCDATE$";}}}?><?php

// source file: /Users/matejmitas/Documents/Minicup 2014/sablony/informace.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'lwmxb90th1')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block main
//
if (!function_exists($_l->blocks['main'][] = '_lb89a30479df_main')) { function _lb89a30479df_main($_l, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?><section>
<div id="content-padding">
             <h2>Informace</h2>
            <h3>Oddíl házené TJ Tatran Litovel Vás srdečně zve k účasti na 2. ročníku mezinárodního turnaje miniházené LITOVEL MINI CUP 2014, který se uskuteční v termínu 23. – 25. 5. 2014.</h3>

            <p >Turnaj je pro určen pro družstva mladší mini (2005 a mladší) a starší mini (2003 – 2004) bez rozdílu pohlaví, tzn. chlapci, dívky, smíšené týmy. Turnaje se může zúčastnit celkem 24 týmů, z toho maximálně 12 týmů v každé věkové kategorii. Vzhledem k co největší atraktivitě turnaje si pořadatel vyhrazuje právo výběru týmů.</p>

            <h3>Hrací systém:</h3>

            <p>4 + 1, každý s každým (každý tým odehraje 11 zápasů), začátek turnaje je v pátek v 13.00 hod</p>

            <h3>Hrací doba:</h3>

            <p>2 x 10 min s 2 min. přestávkou</p>
            <h3>Kde se hraje:</h3>

            <ul>
                <li>sportovní hala ZŠ Vítězná (2 hřiště)
            </li>
                <li>venkovní areál Sokolovna Litovel (2 hřiště)
            </li>
                <li>V případě nepřízně počasí budeme nuceni přesunout venkovní zápasy do sportovní haly, tzn. zkrácení hrací doby.</li>
            </ul>

            <h3>Ubytování:</h3>

            <p>ve třídách ZŠ Vítězná – karimatka, spacák</p>

            <h3>Stravování:</h3>

            <p>školní jídelna ZŠ Vítězná (snídaně, obědy, večeře)</p>

            <h3>Vyhodnocení turnaje:
            </h3>

            <ul>
                <li>1. tři týmy z každé věkové kategorie obdrží pohár, hodnotnou cenu, každý hráč z týmu medaili.</li>
                <li>Hráči všech týmů dostanou sladkou odměnu, bude vyhodnocen nejlepší střelec, hráč a brankář každé věkové kategorie</li>
            </ul>


            <h3>Program pro děti:</h3>

            <p>pátek - diskotéka, sobota – zápas dětských hvězd, trenérů, doprovodný program</p>

            <h3>Program pro dospělé:</h3>

            <p>večerní posezení v areálu Sokolovny s ochutnávkou místních specialit, doprovodný program</p>

            <h3>Doprovodný program:</h3>

            <ul>
                <li>k využití volného času Vám nabízíme zajištění - vše v dosahu 300 m od ubytování
            krytého bazénu s parní místností (ZŠ)</li>
                <li>hřiště na plážový volejbal (házenou) - areál Sokolovny</li>
                <li>tenisový kurt – areál Sokolovny</li>
                <li>restaurace se 4 bowlingovými dráhami (2 dráhy mají dětské mantinely) – 50 m od haly</li>
                <li>projížďka Litovelským Pomoravím na raftech</li>
                <li>Pokud byste měli sportovního vyžití „nad hlavu“, jsme schopni Vám zarezervovat prohlídku blízkých Mladečských jeskyní nebo pohádkového hradu Bouzov (i noční prohlídku). Samozřejmě, že dopravu autobusem jsme schopni také zarezervovat.</li>
            </ul>








            <h3>Startovné:</h3>

            <p>1 tým z oddílu – 1 500,- Kč, 2 týmy z oddílu 2 400,- Kč</p>

            <h3>Ubytování:</h3>

            <p>85,- osobu/den, ubytování ve třídách ZŠ Vítězná. Bude vybírána vratná kauce 2 000,- Kč jako záloha na případné poškození vybavení tříd.</p>

            <h3>Strava:</h3>

            <ul>
                <li>snídaně - 50,-</li>
                <li>Oběd – 75,-</li>
                <li>Večeře – 75,-</li>
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