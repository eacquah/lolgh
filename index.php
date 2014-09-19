<?php
require_once('config.php');
//$MyPdo = new \classes\MyPDO('db/db.sqlite');
$user = new \Lib\User();
$db   = new \Lib\Db($dbFile);
$dao  = new \Lib\Dao();
$dao->setDb($db);
$allComics = $dao->fetchAll('comic');

echo $twig->render('index.html', array('the' => 'variables', 'go' => 'here'));