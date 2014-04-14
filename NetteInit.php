<?php

// inicializuje základní třídy Nette + Latte compiler
require 'Nette/common/Object.php';
require 'Nette/Caching/IStorage.php';
require 'Nette/Caching/Storages/FileStorage.php';
require 'Nette/Loaders/RobotLoader.php';
require 'Nette/Caching/Cache.php';
require 'Nette/Utils/Callback.php';
require 'Nette/Utils/Finder.php';
require 'Nette/Iterators/Filter.php';
require 'Nette/Iterators/RecursiveFilter.php';
require 'Nette/common/ObjectMixin.php';
require 'Nette/common/Framework.php';
require 'Nette/common/exceptions.php';
require 'Nette/Templating/ITemplate.php';
require 'Nette/Templating/IFileTemplate.php';
require 'Nette/Templating/Template.php';
require 'Nette/Templating/FileTemplate.php';
require 'Nette/Caching/Storages/PhpFileStorage.php';
require 'Nette/Utils/LimitedScope.php';
require 'Nette/Templating/Helpers.php';
require 'Nette/Utils/TokenIterator.php';
require 'Nette/Latte/IMacro.php';
require 'Nette/Latte/Macros/MacroSet.php';
require 'Nette/Latte/Macros/CoreMacros.php';
require 'Nette/Latte/Engine.php';
require 'Nette/Latte/Parser.php';
require 'Nette/Latte/Compiler.php';
require 'Nette/Latte/Macros/CacheMacro.php';
require 'Nette/Latte/Macros/UIMacros.php';
require 'Nette/Latte/Macros/FormMacros.php';
require 'Nette/Latte/Token.php';
require 'Nette/Latte/MacroNode.php';
require 'Nette/Latte/MacroTokens.php';
require 'Nette/Latte/PhpWriter.php';
require 'Nette/Latte/HtmlNode.php';
require 'Nette/Utils/Html.php';
require 'Nette/Utils/Tokenizer.php';
require 'Nette/Utils/Strings.php';
require 'Nette/Utils/Random.php';
require 'Nette/Utils/Json.php';
require 'Nette/Iterators/CachingIterator.php';

require 'Nette/Security/IIdentity.php';
require 'Nette/Security/Identity.php';