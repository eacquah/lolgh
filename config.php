<?php
function __autoload($class_name) {
  include 'classes/ ' . $class_name . '.php';
}

$MyPdo = new \classes\MyPDO();