<?php
require_once('config.php');

$user = new \Lib\User();
$twitter = new \Lib\Twitter();
$db   = new \Lib\Db($dbFile);
$dao  = new \Lib\Dao();
$dao->setDb($db);

$page  = isset($_GET['page']) ? strip_tags($_GET['page']) : '';
$param = isset($_GET['param']) ? strip_tags($_GET['param']) : 0;

$template = null;
$vars     = array();
$baseUrl  = 'http://' . $_SERVER['HTTP_HOST'];

// Default meta data
$metadata = array(
    'ogTitle'            => 'Lolgh',
    'ogType'             => 'website',
    'ogImage'            => '',
    'ogUrl'              => '',
    'ogDescription'      => '',
    'twitterCard'        => 'summary',
    'twitterUrl'         => '',
    'twitterTitle'       => '',
    'twitterDescription' => '',
    'twitterImage'       => '',
);

// Frontend Controller
switch ($page) {
    case '':
        // Custom meta data
        $pageUrl = $baseUrl;

        $template = '@frontend/index.html';
        $vars['pageTitle']  = 'A laugh a day...';
        $vars['twitterTimeline'] = $twitter->getTimeLine();
        break;

    case 'contact':
        if (isset($_POST['email'])) {
            $subject = 'Lolgh Contact';
            $from    = strip_tags($_POST["email"]); // sender
            $name    = strip_tags($_POST["name"]);
            $message = strip_tags($_POST["msg"]);
            $message = wordwrap($message, 70);
            // send mail to us
            mail("manny.acquah@gmail.com", $subject, $message, "From: $from\n");
            $emailVars = array(
                'name' => $name,
                'msg'  => 'Thanks for getting in touch. We will be in touch shortly'
            );
            $reply     = $twig->render('@email/contact-reply.html', $emailVars);
            // send mail to user
            mail($from, $subject, $reply, "From: hello@lolgh.com\n");
            $vars['success'] = "Thank you for sending us feedback";
        }

        // Custom meta data
        $pageUrl = $baseUrl . '/contact';
        $metadata['ogTitle'] = 'Lolgh . Get in touch';
        $metadata['ogUrl'] = $pageUrl;
        $metadata['ogDescription'] = 'Lolgh . Get in touch';
        $metadata['twitterUrl'] = $pageUrl;
        $metadata['twitterTitle'] = 'Lolgh . Get in touch';
        $metadata['twitterDescription'] = 'Lolgh . Get in touch';

        $template = '@frontend/contact.html';
        $vars['pageTitle']  = 'Get in Touch!';
        break;

    case 'toon':
        $toon  = null;
        $param = (int)$param;
        if ($param > 0) {
            $toon = $dao->findById('toon', $param);
        } else {
            $toon = $dao->fetchRecentToon();
        }
        if ($toon) {
            // Custom meta data
            $pageUrl = $baseUrl . '/comic/' . $toon->getToonId();
            $metadata['ogTitle'] = $toon->getTitle();
            $metadata['ogImage'] = 'http://img.youtube.com/vi/' . $toon->getUrl() . '/mqdefault.jpg';
            $metadata['ogUrl'] = $pageUrl;
            $metadata['ogDescription'] = $toon->getTitle();
            $metadata['twitterUrl'] = $pageUrl;
            $metadata['twitterTitle'] = $toon->getTitle();
            $metadata['twitterDescription'] = $toon->getTitle();
            $metadata['twitterImage'] = 'http://img.youtube.com/vi/' . $toon->getUrl() . '/mqdefault.jpg';

            $template = '@frontend/toon.html';
            $toons    = $dao->fetchReleased('toon');
            $vars     = array(
                'pageTitle' => $toon->getTitle(),
                'toon'  => $toon,
                'toons' => $toons
            );
        }
        break;

    case 'comic':
        $comic    = null;
        $param    = (int)$param;
        $template = '@frontend/comic.html';
        if ($param > 0) {
            $comic = $dao->findById('comic', $param);
        } else {
            $comic = $dao->fetchRecentComic();
        }
        if ($comic) {
            // Custom meta data
            $pageUrl = $baseUrl . '/comic/' . $comic->getComicId();
            $metadata['ogTitle'] = $comic->getTitle();
            $metadata['ogImage'] = $comic->getUrl();
            $metadata['ogUrl'] = $pageUrl;
            $metadata['ogDescription'] = $comic->getTitle();
            $metadata['twitterUrl'] = $pageUrl;
            $metadata['twitterTitle'] = $comic->getTitle();
            $metadata['twitterDescription'] = $comic->getTitle();
            $metadata['twitterImage'] = $comic->getUrl();

            // Get page vars
            $comicId = $comic->getComicId();
            $releaseDate = $comic->getReleaseDate();
            $total   = $dao->getTotal('comic');
            $first   = $dao->fetchFirstComic();
            $last    = $dao->fetchRecentComic();
            $prev    = $dao->fetchPreviousComic($releaseDate);
            $next    = $dao->fetchNextComic($releaseDate);
            $rand    = $dao->fetchRandom('comic');
            $vars    = array(
                'pageTitle' => $comic->getTitle(),
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

// Admin Controller
if ($page == 'admin') {
    $param1 = isset($_GET['param1']) ? $_GET['param1'] : '';
    if (!isset($_SESSION['lolgh_admin'])) {
        $param = '';
    } else {
        $user         = $dao->findById('user', $_SESSION['lolgh_admin']);
        $vars['user'] = $user;
    }
    switch ($param) {
        case '':
        case 'login':
            $passwordHash = new \Lib\Password();
            if (isset($_POST['email'])) {
                array_walk_recursive($_POST, 'mysql_real_escape_string');
                $email    = $_POST['email'];
                $password = $_POST['password'];
                $userId   = $dao->authenticate($email, $password);
                if ($userId) {
                    $_SESSION['lolgh_admin'] = $userId;
                    header('Location: /admin/comic');
                    exit();
                }
                $vars['error'] = 'Invalid username and password';
            }
            $template = '@admin/login.html';
            break;

        case 'comic':
            // Check if delete is required
            if ($param1 != '' && preg_match("/(del)-[0-9]*/i", $param1)) {
                $delId = end(explode('-', $param1));
                $comic = $dao->findById('comic', $delId);
                unlink('/img/comics/' . $comic->getUrl());
                $db->delete('comic', array ('comic_id' => $delId));
                $vars['success'] = 'Comic has been successfully deleted!';
            }
            $template       = '@admin/comic.html';

            if ($param1 != '' && $param1 == 'all') {
                $comics = $dao->fetchAll('comic', null, 'ORDER BY comic_id DESC');
                $viewUrl = '/admin/comic';
                $viewTxt = 'View Latest 10';
            } else {
                $comics = $dao->fetchBatch('comic', 0, 10);
                $viewUrl = '/admin/comic/all';
                $viewTxt = 'View All';
            }

            $vars['comics'] = $comics;
            $vars['viewUrl'] = $viewUrl;
            $vars['viewTxt'] = $viewTxt;
            break;

        case 'toon':
            // Check if delete is required
            if ($param1 != '' && preg_match("/(del)-[0-9]*/i", $param1)) {
                $delId = end(explode('-', $param1));
                $db->delete('toon', array ('toon_id' => $delId));
                $vars['success'] = 'Toon has been successfully deleted!';
            }
            $template      = '@admin/toon.html';
            $toons         = $dao->fetchAll('toon', null, 'ORDER BY toon_id DESC');
            $vars['toons'] = $toons;

            break;

        case 'add-comic':
            if (isset($_POST['title'])) {
                array_walk_recursive($_POST, 'mysql_real_escape_string');
                $tmpFile = $_FILES['comic']['tmp_name'];
                list($width, $height) = getimagesize($tmpFile);
                $newWidth         = 1000;
                $imgRatio         = $newWidth / $width;
                $newHeight        = $height * $imgRatio;
                $upload           = new \Lib\Upload();
                $upload->uploadTo = 'img/comics/';

                $res = $upload->upload($_FILES['comic']);
                if ($res) {
                    // RESIZE
                    $upload->newWidth  = $newWidth;
                    $upload->newHeight = $newHeight;
                    $upload->resize();
                    $imageUrl = $upload->resizedImgName;
                    $data     = array(
                        'title'        => $_POST['title'],
                        'url'          => $imageUrl,
                        'date_added'   => time(),
                        'release_date' => strtotime($_POST['release_date'])
                    );
                    $db->insert('comic', $data);
                    header('Location: /admin/comic');
                    exit();
                }
            }

            $template = '@admin/add-comic.html';
            break;

        case 'add-toon':
            if (isset($_POST['title'])) {
                array_walk_recursive($_POST, 'mysql_real_escape_string');
                $data = array(
                    'title'        => $_POST['title'],
                    'url'          => $_POST['url'],
                    'date_added'   => time(),
                    'release_date' => strtotime($_POST['release_date'])
                );
                $db->insert('toon', $data);
                header('Location: /admin/toon');
                exit();
            }

            $template = '@admin/add-toon.html';
            break;

        case 'logout':
            unset($_SESSION['admin']);
            header('Location: /');
            exit();
            break;

    }
}

if (null === $template) {
    $vars['pageTitle']  = 'You\'re lost? Well so are we!';
    $template = '@frontend/404.html';
}

// Set current page for template
$vars['page']     = $page;
$vars['metadata'] = $metadata;

// Render template
echo $twig->render($template, $vars);