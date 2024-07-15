<?php

$link = $_POST['link'];

require_once 'core/connection.php'; 
$sql = "DELETE FROM galerie WHERE link = ?";
$stmt = $mysqli->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $link);
    $stmt->execute();
    $stmt->close();
}

?>