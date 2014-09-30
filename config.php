<?php
session_start();
// Set app constants
define('BASE_PATH', realpath(dirname(__FILE__)));

// Autoload Lib
function __autoload($class) {
  include(BASE_PATH . '/lib/' . str_replace('\\', '/', $class) . '.php');
}

// Get config from ini file
$config = parse_ini_file(BASE_PATH . '/config/config.ini');

// Set config params
$dbFile          = BASE_PATH . $config['db']['file'];
$twigLib         = BASE_PATH . $config['twig']['lib'];
$twigTemplateDir = BASE_PATH . $config['twig']['template_dir'];
$twigCacheDir    = BASE_PATH . $config['twig']['cache_dir'];

// Set up Twig templating
require_once $twigLib . '/Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($twigTemplateDir);
$loader->addPath($twigTemplateDir . '/frontend', 'frontend');
$loader->addPath($twigTemplateDir . '/admin', 'admin');
$twig = new Twig_Environment($loader, array(
  //    'cache' => $twigCacheDir,
));

// Temp inc since autoloader not working
include('lib/User.php');
include('lib/Comic.php');
include('lib/Db.php');
include('lib/Toon.php');
include('lib/Dao.php');
include('lib/Password.php');

