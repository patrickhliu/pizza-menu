<?php
/**************************************************************************************************
config.php
    This file defines variable constants for use
***************************************************************************************************/

    DEFINED('DS')    ? null : define('DS'  , DIRECTORY_SEPARATOR);
    DEFINED('ROOT')  ? null : define('ROOT', DS.'xampp'.DS.'htdocs'.DS.'port'.DS.'pizza');
    DEFINED('LIB')   ? null : define('LIB' , ROOT.DS.'lib');
    DEFINED('VIEW')  ? null : define('VIEW', ROOT.DS.'views');   
   