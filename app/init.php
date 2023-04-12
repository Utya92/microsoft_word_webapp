<?php
include "config/config.php";

spl_autoload_register(function ($class) {
    require $class . ".php";
});

function debug($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

$main = new \boot\Main();
$main->run($_POST, $_FILES);