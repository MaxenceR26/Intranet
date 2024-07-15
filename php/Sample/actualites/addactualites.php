<?php

require '../core/connection.php';

session_start();
if (!isset($_SESSION['state'])) {
    session_destroy();
    header('Location: ../../../index.html');
    exit();
}

$title = $_POST["title"];
$head = $_POST["head"];
$body = $_POST["body"];
$text = nl2br($_POST["text"]);
$date = date("Y-m-d");

$imagePath = null;

// Vérifier si un fichier a été téléchargé
if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $uploadDir = '../../../images/';
    $imageName = 'actu_' . basename($_FILES['image']['name']);
    $imagePath = $uploadDir . $imageName;

    // Vérifier si l'image existe déjà
    if (file_exists($imagePath)) {
        echo "Erreur : une image avec le même nom existe déjà.";
        exit();
    }

    // Déplacer le fichier téléchargé vers le répertoire des images
    if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        $imagePath = 'images/' . $imageName; // Chemin relatif à partir de la racine du site
    } else {
        echo "Erreur lors du téléchargement de l'image.";
        exit();
    }
}

$sql = "INSERT INTO actualites (titre, head, body, texts, dates, `image`) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);

$stmt->bind_param("ssssss", $title, $head, $body, $text, $date, $imagePath);

if ($stmt->execute()) {
    echo "L'actualité a été ajoutée avec succès. $imagePath";
} else {
    echo "Erreur lors de l'ajout de l'actualité : " . $mysqli->error;
}

$stmt->close();
?>
