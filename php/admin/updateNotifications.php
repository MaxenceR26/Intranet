<?php
session_start();
require '../Sample/core/connection.php';

$sql_update = "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC LIMIT 4";
$stmt_update = $mysqli->prepare($sql_update);
$stmt_update->bind_param("s", $_SESSION['email']); // $email est l'email de l'utilisateur actuel, assurez-vous de le définir correctement
$stmt_update->execute();
$stmt_update->close();
?>