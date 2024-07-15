<?php

$params = require 'config.php';

try {
    $mysqli = new mysqli(
        $params["HOST"],
        $params["USER"],
        $params["PASSWORD"],
        $params["DATABASE"],
        $params["PORT"]
    );

} catch (Exception $e) {
    die("Mysqli fatal error".$e->getMessage());
}