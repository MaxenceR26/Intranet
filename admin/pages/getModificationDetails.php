<?php
require_once '../../php/Sample/core/connection.php';
session_start();

if (!isset($_SESSION['state'])) {
    session_destroy();
    header('Location: ../../index.html');
    exit();
}

if (isset($_POST['identifiants'])) {
    $identifiants = $mysqli->real_escape_string($_POST['identifiants']);
    $sql = "SELECT * FROM ticketsmodif WHERE foreignkey = '$identifiants'";
    $result = $mysqli->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $default_values = [
            'email' => 'default@example.com',
            'numport' => '0000000000',
            'numext' => '0000000000',
            'dirfixe' => '0',
            'adresse_postale' => 'Unknown'
        ];

        foreach ($row as $key => $value) {
            // Vérifier si la valeur n'est pas par défaut
            if (!array_key_exists($key, $default_values) || $value !== $default_values[$key]) {
                if ($_POST['page'] != 'services') {
                    // Si différent, afficher 'ville' au lieu de 'nomAnnuaire'
                    if ($key == 'nomAnnuaire') {
                        $details .= "<li><strong>Ville:</strong> " . htmlspecialchars($value) . "</li>";
                    } elseif ($key == 'prenomAnnuaire') {
                        // Si différent, afficher 'ville' au lieu de 'prenomAnnuaire'
                        $details .= "";
                    } else {
                        // Pour les autres clés, afficher normalement
                        $details .= "<li><strong>" . htmlspecialchars($key) . ":</strong> " . htmlspecialchars($value) . "</li>";
                    }
                } else {
                    // Si $_POST['page'] est 'services', afficher normalement
                    $details .= "<li><strong>" . htmlspecialchars($key) . ":</strong> " . htmlspecialchars($value) . "</li>";
                }
            }
        }

        $details .= "</ul>";

        echo $details;
    } else {
        echo "Aucune modification trouvée pour cet identifiant.";
    }

    $mysqli->close();
} else {
    echo "Identifiant non fourni.";
}
?>