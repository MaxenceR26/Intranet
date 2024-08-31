<?php
session_start();
require 'core/connection.php';

// Vérification de la session utilisateur
if (!isset($_SESSION['state'])) {
    header('Location: ../../index.html');
    exit();
}

// Récupération de l'email de l'utilisateur connecté
$email = $_SESSION['email'];

// Initialisation des résultats de recherche
$result_users = [];
$result_employes = [];
$result_mairies = [];

// Traitement de la recherche
if (isset($_GET['q'])) {
    $query = htmlspecialchars($_GET['q'], ENT_QUOTES, 'UTF-8');

    // Requête pour rechercher des utilisateurs par nom d'utilisateur
    $stmt_users = $mysqli->prepare("SELECT username, lastname, firstname, img, email FROM users WHERE lastname LIKE ? OR firstname LIKE ? LIMIT 10");
    $search_term = "%$query%";
    $stmt_users->bind_param('ss', $search_term, $search_term);
    $stmt_users->execute();
    $result_users = $stmt_users->get_result();

    // Requête pour rechercher des employés par nom ou prénom
    $stmt_employes = $mysqli->prepare("SELECT nom, prenom, email, poste, numPort, numExt, dirFixe FROM services WHERE nom LIKE ? OR prenom LIKE ? LIMIT 10");
    $stmt_employes->bind_param('ss', $search_term, $search_term);
    $stmt_employes->execute();
    $result_employes = $stmt_employes->get_result();

    // Requête pour rechercher des mairies par nom de commune
    $stmt_mairies = $mysqli->prepare("SELECT commune, maire, adresse_postale, telephone, mail FROM communes WHERE commune LIKE ? LIMIT 10");
    $stmt_mairies->bind_param('s', $search_term);
    $stmt_mairies->execute();
    $result_mairies = $stmt_mairies->get_result();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet Osartis | Recherche</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.1/mdb.min.css">
</head>
<body>

<!-- Barre de Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="../../images/logo.jpg" height="35" alt="Logo" loading="lazy">
        </a>
        <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="../../intranet.php">Osartis Intranet</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenu principal -->
<div class="container mt-4">
    <div class="box-search mb-4">
        <!-- Formulaire de recherche -->
        <form action="search.php" method="GET">
            <div class="input-group">
                <input type="search" class="form-control" name="q" placeholder="Rechercher un utilisateur, employé ou mairie" required>
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>

    <!-- Affichage des résultats de la recherche -->
    <div class="box">
        <?php if ($result_users->num_rows > 0): ?>
            <h2>Résultats Utilisateurs</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result_users->fetch_assoc()): ?>
                    <tr class="userRow" data-email="<?php echo $row['email']; ?>">
                        <td style="cursor: pointer;"><img src="../../<?php echo $row['img']; ?>" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;"></td>
                        <td style="cursor: pointer;"><?php echo $row['firstname']; ?></td>
                        <td style="cursor: pointer;"><?php echo $row['lastname']; ?></td>
                        <td style="cursor: pointer;"><?php echo $row['email']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
                <?php echo "</form>" ?>
            </table>
        <?php endif; ?>

        <?php if ($result_employes->num_rows > 0): ?>
            <h2>Résultats Employés</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Poste</th>
                        <th>Numéro Portable</th>
                        <th>Numéro Externe</th>
                        <th>Directeur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_employes->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['nom']; ?></td>
                            <td><?php echo $row['prenom']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['poste']; ?></td>
                            <td><?php echo $row['numPort']; ?></td>
                            <td><?php echo $row['numExt']; ?></td>
                            <td><?php echo $row['dirFixe']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if ($result_mairies->num_rows > 0): ?>
            <h2>Résultats Mairies</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Commune</th>
                        <th>Maire</th>
                        <th>Adresse postale</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_mairies->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['commune']; ?></td>
                            <td><?php echo $row['maire']; ?></td>
                            <td><?php echo $row['adresse_postale']; ?></td>
                            <td><?php echo $row['telephone']; ?></td>
                            <td><?php echo $row['mail']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Message si aucune correspondance trouvée -->
        <?php if ($result_users->num_rows === 0 && $result_employes->num_rows === 0 && $result_mairies->num_rows === 0): ?>
            <p>Aucun résultat trouvé pour "<?php echo htmlspecialchars($query); ?>"</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.1/mdb.min.js"></script>
<script>
        document.addEventListener("DOMContentLoaded", function() {
            var userRows = document.querySelectorAll(".userRow");
            
            userRows.forEach(function(row) {
                row.addEventListener("click", function() {
                    var email = row.dataset.email;
                    window.location.href = '../../pages/users.php?email=' + email;
                });
            });
        });
    </script>
</body>
</html>
