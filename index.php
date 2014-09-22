<?php
require_once('config.php');
//$MyPdo = new \classes\MyPDO('db/db.sqlite');
$user = new \Lib\User();
$db   = new \Lib\Db($dbFile);
$dao  = new \Lib\Dao();
$dao->setDb($db);


$page  = isset($_GET['page']) ? strip_tags($_GET['page']) : 0;
$param = isset($_GET['param']) ? (int)$_GET['param'] : '';

$template = null;
$vars     = array();

switch ($page) {
    case '':
        $template = 'index.html';
        break;
    case 'contact':
        $template = 'contact.html';
        break;
    case 'toon':
        $toon = null;
        if ($param > 0) {
            $toon = $dao->findById('toon', $param);
        } else {
            $toon = $dao->fetchRecent('toon');
        }
        if ($toon) {
            $template = 'toon.html';
            $toons = $dao->fetchAll('toon');
            $vars     = array(
                'toon' => $toon,
                'toons' => $toons
            );
        }
        break;
    case 'comic':
        $comic    = null;
        $template = 'comic.html';
        if ($param > 0) {
            $comic = $dao->findById('comic', $param);
        } else {
            $comic = $dao->fetchRecent('comic');
        }
        $vars = array(
            'comic' => $comic
        );
        break;
}

if (null === $template) {
    $template = '404.html';
}

// Render template
echo $twig->render($template, $vars);