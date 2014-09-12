<?php
function __autoload($class_name) {
  include 'classes/ ' . $class_name . '.php';
}

$config = parse_ini_file('config/config.ini');
//var_dump($config); die();
$twigLib =  $config['twig']['lib'];
$twigTemplateDir = $config['twig']['template_dir'];
$twigCacheDir = $config['twig']['cache_dir'];

//die();
require_once $twigLib . '/Twig/Autoloader.php';


Twig_Autoloader::register();



$loader = new Twig_Loader_Filesystem($twigTemplateDir);
$twig = new Twig_Environment($loader, array(
    'cache' => $twigCacheDir,
));


use \Classes\User as User;
//$MyPdo = new \classes\MyPDO('db/db.sqlite');
$user = new User();
die();