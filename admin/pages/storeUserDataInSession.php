<?php
session_start();
$current_page = $_SERVER['PHP_SELF'];
if (!isset($_SESSION['state'])) {
    header('Location: ..\..\index.html');
}
$_SESSION["nomAnnuaire"] = $_POST["nom"];
$_SESSION["prenomAnnuaire"] = $_POST["prenom"];
?>
