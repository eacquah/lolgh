<?php
require_once('config.php');

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
        if (isset($_POST['email'])) {
          $subject = 'Lolgh Contact';
          $from = strip_tags($_POST["email"]); // sender
          $name = strip_tags($_POST["name"]);
          $message = strip_tags($_POST["msg"]);
          $message = wordwrap($message, 70);
          // send mail to us
          mail("manny.acquah@gmail.com", $subject, $message, "From: $from\n");
          $emailVars = array(
            'name' => $name,
            'msg' => 'Thanks for getting in touch. We will be in touch shortly'
          );
          $reply = $twig->render('@email/contact-reply.html', $emailVars);
          // send mail to user
          mail($from, $subject, $reply,"From: hello@lolgh.com\n");
          $vars['success'] = "Thank you for sending us feedback";
        }
        $template = '@frontend/contact.html';
        break;

    case 'toon':
        $toon  = null;
        $param = (int)$param;
        if ($param > 0) {
            $toon = $dao->findById('toon', $param);
        } else {
            $toon = $dao->fetchRecent('toon');
        }
        if ($toon) {
            $template = '@frontend/toon.html';
            $toons    = $dao->fetchReleased('toon');
            $vars     = array(
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
    $param1 = isset($_GET['param1']) ? $_GET['param1'] : '';
    if (!isset($_SESSION['lolgh_admin'])) {
        $param = '';
    } else {
        $user = $dao->findById('user', $_SESSION['lolgh_admin']);
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
                $vars['error'] ='Invalid username and password';
            }
            $template = '@admin/login.html';
            break;

        case 'comic':
            // Check if delete is required
            if($param1 != '' && preg_match("/(del)-[0-9]*/i", $param1)) {
                $delId = end(explode('-', $param1));
                $db->delete('comic', 'WHERE comic_id = "' . $delId . '"');
            }
            $template = '@admin/comic.html';
            $comics   = $dao->fetchAll('comic', null, 'ORDER BY comic_id DESC');
            $vars['comics'] = $comics;;
            break;

        case 'toon':
            $template = '@admin/toon.html';
            $toons    = $dao->fetchAll('toon', null, 'ORDER BY toon_id DESC');
            $vars['toons'] = $toons;

            break;

        case 'add-comic':
            if (isset($_POST['title'])) {
                array_walk_recursive($_POST, 'mysql_real_escape_string');
                $tmpFile = $_FILES['comic']['tmp_name'];
                list($width, $height) = getimagesize($tmpFile);
                $newWidth = 1000;
                $imgRatio = $newWidth / $width;
                $newHeight = $height * $imgRatio;
                $upload           = new \Lib\Upload();
                $upload->uploadTo = 'img/comics/';

                $res              = $upload->upload($_FILES['comic']);
                if ($res) {
                    // RESIZE
                    $upload->newWidth  = $newWidth;
                    $upload->newHeight = $newHeight;
                    $upload->resize();
                    $imageUrl          = $upload->resizedImgName;
                    $data = array(
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
            header('Location: /admin');
            exit();
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