<?php
/**
 * Created by PhpStorm.
 * User: manny
 * Date: 07/11/14
 * Time: 15:04
 */
require_once('../config.php');

$db = new \Lib\Db($dbFile);
$dao = new \Lib\Dao();
$dao->setDb($db);
$twitter = new \Lib\Twitter();
$api = new \Lib\Api();
$api->setDao($dao);
$api->setTwitter($twitter);

$method = $_SERVER['REQUEST_METHOD'];
$params = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));

$api->setMethod($method)
    ->setParams($params)
    ->processApi();