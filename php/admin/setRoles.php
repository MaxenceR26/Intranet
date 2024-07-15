<?php
if (!isset($_SESSION['state'])) {
    header('Location: ../../index.html');
}

require_once '../Sample/core/connection.php';
session_start();

$idroles = $_POST['select'];
$idUserSelect = $_POST['userId'];
$roles = ['Consultant', 'Editeur', 'Administrateur'];

$sql = "UPDATE users SET poste = ?, idroles = ? WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sss", $roles[$idroles - 1], $idroles, $idUserSelect);
if ($stmt->execute()) {
    if ($idUserSelect == $_SESSION['iduser']) {
        $_SESSION['idroles'] = $idroles;
    }
}
$stmt->close();



?>