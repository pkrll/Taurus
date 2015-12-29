<?php

define("kDefaultAPIVersion",    "1.0");
define("kDefaultFileName",      "Base.php");
define("kDefaultClassName",     "API");
define("kDefaultMethodName",    "main");
define("kPlaceholder"           "%_PLACEHOLDER_%");

define("kRootFolder",           dirname(__DIR__));
define("kLibraryFolder",        kRootFolder."/library/");
define("kApplicationFolder",    kRootFolder."/application/");
define("kResourcesFolder",      kApplicationFolder . kPlaceholder . "/Resources/");
define("kModelsFolder",         kApplicationFolder . kPlaceholder . "/Models/");
