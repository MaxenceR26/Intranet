<?php

require '../core/connection.php';
session_start();

if (!isset($_SESSION['state'])) {
    session_destroy();
    header('Location: ../../../index.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identifiant = trim($_POST['identifiants']); // Récupérer l'email de l'utilisateur spécifique

    if (empty($identifiant)) {
        echo "Erreur: Page ou identifiant non spécifié.";
        exit();
    }

    // Supprimer l'actualité avec l'ID le plus élevé
    $sql = "SELECT id FROM actualites WHERE id = (SELECT MAX(id) FROM actualites);";
    $result = $mysqli->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        $delete_sql = "DELETE FROM actualites WHERE id = ?";
        $delete_stmt = $mysqli->prepare($delete_sql);
        $delete_stmt->bind_param('i', $row['id']);

        if ($delete_stmt->execute()) {
            // Supprimer le ticket correspondant
            $stmt = $mysqli->prepare("DELETE FROM tickets WHERE foreignkey = ?");
            $stmt->bind_param("s", $identifiant);

            if ($stmt->execute()) {
                unset($_SESSION['titre']);
                unset($_SESSION['head']);
                unset($_SESSION['body']);
                unset($_SESSION['texts']);
                unset($_SESSION['dates']);
                unset($_SESSION['image_actu']);
                echo "Ticket supprimé avec succès";
            } else {
                echo "Erreur lors de la suppression du ticket : " . $identifiant;
            }

            $stmt->close();

            // Réinitialiser l'AUTO_INCREMENT après la suppression réussie
            $mysqli->query("ALTER TABLE actualites AUTO_INCREMENT = 1;");
            exit();
        } else {
            echo "Erreur lors de la suppression de l'article : " . $delete_stmt->error;
        }

        $delete_stmt->close();
    } else {
        echo "Aucune actualité trouvée.";
    }
} else {
    echo "Méthode de requête non valide.";
}

$mysqli->close();
?>
