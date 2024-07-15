<?php
$phrase = $_POST['phrase'];
$image = $_POST['image'];

echo $phrase . $image;

require_once 'core/connection.php'; // Inclure votre fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $mysqli->prepare("INSERT INTO galerie (bio, link) VALUES (?, ?)");
    $stmt->bind_param("ss", $phrase, $image);    
    $stmt->execute();
}
?>