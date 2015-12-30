<?php
// CONFIGURATIONS
define("kDefaultAPIVersion",    "1.0");
define("kDefaultFileName",      "BaseAPI.php");
define("kDefaultClassName",     "API");
define("kDefaultMethodName",    "main");
define("kPlaceholder",          "%_PLACEHOLDER_%");
define("kRootFolder",           dirname(dirname(dirname(__DIR__))));
define("kApplicationFolder",    kRootFolder."/application/");
define("kResourcesFolder",      kApplicationFolder . kPlaceholder . "/Resources/");
define("kModelsFolder",         kApplicationFolder . kPlaceholder . "/Models/");
// PACKAGE DETAILS
define('kPackageName', "Taurus");
define('kPackageShortName', "Ta");
define('kPackageDescription', "Small API Framework");
define('kPackageVersion', "1.0.1");
