<?php
session_start();
if (!isset($_SESSION['state'])) {
    header('Location: ..\index.html');
}else {
    if ($_SESSION["idroles"] == 2) {
        header('Location: pages/newActualites.php');
    } else if ($_SESSION["idroles"] == 1) {
        header('Location: ../intranet.php');
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/panel-nav.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <script src="https://kit.fontawesome.com/11b0336c50.js" crossorigin="anonymous"></script>
    <title>Admin Panel</title>
    <link rel="icon" href="../images/1719307876_logo.ico"/>
   
</head>
<body>
    <nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fas fa-bars"></i>
        </label>
        <label class="logo">Panel Admin</label>
        <ul>
            <?php
            if ($_SESSION["idroles"] == "3") {
                echo "<li><a class='active' href=''>Gestion utilisateurs</a></li>";
            }

            ?>
            <li><a href="pages/newActualites.php">Ajouter une actualité</a></li>
            <li><a href="pages/communes.php">Annuaire mairies</a></li>
            <li><a href="pages/services.php">Annuaire services</a></li>
            <?php
            if ($_SESSION["idroles"] == "3") {
                echo "<li><a href='pages/tickets.php'>Tickets</a></li>
                <li><a href='pages/archive.php'>Tickets Archivés</a></li>";
            }

            ?>
            <li><a href="../intranet.php">Intranet</a></li>
        </ul>
    </nav>
    <div class="box">
        <table id="animated-table">
            <thead>
                <tr>
                <th>ID</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Nom d'utilisateur</th>
                <th>E-mail</th>
                <th>Roles</th>
                <th>Dernière connexion</th>
                <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once '../php/Sample/core/connection.php';

                $sql = "SELECT * FROM users";
                $result = $mysqli->query($sql);

                if ($result) {
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $id = $row['id']; $firstname = $row['firstname']; $lastname = $row['lastname']; $username = $row['username']; $email = $row['email']; $poste = $row['poste'];$last_conn = $row['last-conn'];
                            
                            echo "<tr>
                            <td>$id</td>
                            <td>$firstname</td>
                            <td>$lastname</td>
                            <td>$username</td>
                            <td>$email</td>
                            <td>$poste</td>
                            <td>$last_conn</td>
                            <td class='iconButton'><i class='fa-solid fa-pen'></i></td>
                            </tr>";
                        }
                    } else {
                        echo "Aucun utilisateur trouvé.";
                    }
                } else {
                    echo "Erreur dans l'exécution de la requête : " . $mysqli->error;
                }

                $mysqli->close();
                
                ?>
            </tbody>
        </table>
    </div>
    <div id="myModal" class="modal">
        <div class="modal-content" style="height: 200px;">
            <span class="close">&times;</span>
            <div class="elementModal">
                <h2 id="modalTitle">Modifier les rôles de</h2>
                <form action="../php/admin/setRoles.php" id="roleForm" method="POST">
                    <p>Selections du rôles</p>
                    <select name="select" id="select">
                        <option value="1">Consultant</option>
                        <option value="2">Editeur</option>
                        <option value="3">Administrateur</option>
                    </select>
                    <button type="submit">Valider</button>
                </form>
            </div>
            
        </div>
    </div>
</body>
<script src="js/tableau.js"></script>

</html>