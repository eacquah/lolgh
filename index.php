<?php
require_once('config.php');
//$MyPdo = new \classes\MyPDO('db/db.sqlite');
$user = new \Lib\User();
$db   = new \Lib\Db($dbFile);
$dao  = new \Lib\Dao();
$dao->setDb($db);


$page  = isset($_GET['page']) ? strip_tags($_GET['page']) : '';
$param = isset($_GET['param']) ? strip_tags($_GET['param']) : 0;

$template = null;
$vars     = array();

switch ($page) {
    case '':
        $template = '@frontend/index.html';
        break;

    case 'contact':
        $template = '@frontend/contact.html';
        break;

    case 'toon':
        $toon = null;
        $param = (int) $param;
        if ($param > 0) {
            $toon = $dao->findById('toon', $param);
        } else {
            $toon = $dao->fetchRecent('toon');
        }
        if ($toon) {
            $template = '@frontend/toon.html';
            $toons    = $dao->fetchAll('toon');
            $vars     = array(
                'toon'  => $toon,
                'toons' => $toons
            );
        }
        break;

    case 'comic':
        $comic    = null;
        $param = (int) $param;
        $template = '@frontend/comic.html';
        if ($param > 0) {
            $comic = $dao->findById('comic', $param);
        } else {
            $comic = $dao->fetchRecent('comic');
        }
        if ($comic) {
            $comicId = $comic->getComicId();
            $total   = $dao->getTotal('comic');
            $first   = $dao->fetchFirst('comic');
            $last    = $dao->fetchRecent('comic');
            $prev    = $dao->fetchPrevious('comic', $comicId);
            $next    = $dao->fetchNext('comic', $comicId);
            $rand    = $dao->fetchRandom('comic');
            $vars    = array(
                'comic' => $comic,
                'first' => $first,
                'last'  => $last,
                'prev'  => $prev,
                'next'  => $next,
                'rand'  => $rand,
                'total' => $total,
            );
        }
        break;
}

if ($page == 'admin') {
    $param1 = isset($_GET['param1']) ? (int)$_GET['param1'] : 0;
    switch($param) {
        case '':
            $template = '@admin/login.html';
            break;

        case 'comic':
            $template = '@admin/comic.html';
            $comics    = $dao->fetchAll('comic');
            $vars = array(
                'comics' => $comics
            );
            break;

        case 'toon':
            $template = '@admin/toon.html';
            $toons    = $dao->fetchAll('toon');
            $vars = array(
                'toons' => $toons
            );

            break;

        case 'add-comic':
            $template = '@admin/add-comic.html';
            break;
    }
}

if (null === $template) {
    $template = '@frontend/404.html';
}

// Set current page for template
$vars['page'] = $page;

// Render template
echo $twig->render($template, $vars);