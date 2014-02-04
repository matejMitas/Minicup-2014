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
require 'Nette/Latte/IMacro.php';
require 'Nette/Latte/Macros/MacroSet.php';
require 'Nette/Latte/Macros/CoreMacros.php';
require 'Nette/Templating/Helpers.php';

