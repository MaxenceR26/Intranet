<?php

error_reporting(E_ALL); ini_set('display_errors', 1);
require_once 'connection.php';
session_start();

$sql = "SELECT nom, ajouter, modifier, supprimer, acpanel  FROM roles WHERE idroles = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('s', $_SESSION['idroles']);
$stmt->execute();
$stmt->bind_result($nom, $ajouter, $modifier, $supprimer, $acpanel);

while ($stmt->fetch()) {
    $_SESSION['roles'] = $nom;
    $_SESSION['ajouter'] = $ajouter;
    $_SESSION['modifier'] = $modifier;
    $_SESSION['supprimer'] = $supprimer;
    $_SESSION['acpanel'] = $acpanel;
}