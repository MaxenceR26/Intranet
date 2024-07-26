<?php
session_start();
require '../php/Sample/core/connection.php';
if (!isset($_SESSION['state'])) {
    header('Location: ../index.html');
}
$email = $_SESSION['email'];

// Requête SQL pour compter les notifications de l'utilisateur
$sql_count = "SELECT COUNT(*) as notification_count FROM notifications WHERE (user_id = ? OR user_id = 'general') AND is_read = 0 ORDER BY created_at DESC LIMIT 4";
$stmt = $mysqli->prepare($sql_count);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($notification_count);
$stmt->fetch();
$stmt->close();


// Requête SQL pour récupérer les 5 dernières notifications de l'utilisateur par email
$sql_messages = "SELECT message FROM notifications WHERE (user_id = ? OR user_id = 'general') AND is_read = 0 ORDER BY created_at DESC LIMIT 4";
$stmt = $mysqli->prepare($sql_messages);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($message);

$messages = [];
while ($stmt->fetch()) {
    $messages[] = $message;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/styles.css">
    <script src="https://kit.fontawesome.com/11b0336c50.js" crossorigin="anonymous"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.1/mdb.min.css" rel="stylesheet"/>
    <title>Intranet Osartis | Utilisateur</title>
    <link rel="icon" href="../images/1719307876_logo.ico"/>
    <script src="../js/popup.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary">
        <!-- Container wrapper -->
        <div class="container-fluid">
        <!-- Toggle button -->
        <button
            data-mdb-collapse-init
            class="navbar-toggler"
            type="button"
            data-mdb-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <i class="fas fa-bars"></i>
        </button>
    
        <!-- Collapsible wrapper -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Navbar brand -->
            <a class="navbar-brand mt-2 mt-lg-0" href="#">
            <img
                src="../images/logo.jpg"
                height="35"
                alt="MDB Logo"
                loading="lazy"
            />
            </a>
            <!-- Left links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <div class="nav-item">
                    <a class="nav-link" href="../intranet.php">Osartis Intranet</a>
                </div>
            
            </ul>
            <!-- Left links -->
        </div>
        <!-- Collapsible wrapper -->
    
        <!-- Right elements -->
        <div class="d-flex align-items-center">
    
            <!-- Notifications -->
            <div class="dropdown">
            <a
                data-mdb-dropdown-init
                class="text-reset me-3 dropdown-toggle hidden-arrow"
                href="#"
                id="navbarDropdownMenuLink"
                role="button"
                aria-expanded="false"
            >
                <i class="fas fa-bell"></i>
                <?php if ($notification_count > 0 ):?>
                <span class="badge rounded-pill badge-notification bg-danger" id="notification-count"><?php if ($notification_count >= 9) { echo '+9';} else { echo $notification_count;} ?></span>
                <?php endif ?>
            </a>
            <ul
                class="dropdown-menu dropdown-menu-end"
                aria-labelledby="navbarDropdownMenuLink"
            >
            <span class="d-flex justify-content-center" style="color:#464646; font-weight: bold; font-size: 18px">Notifications</span>
            <hr class="m-0">
            <?php if (count($messages) > 0): ?>
                <?php foreach ($messages as $msg): ?>
                    <li>
                        <a class="dropdown-item" href="#" style="color:#979797"><?php echo '<span style="font-weight:bold; color:#464646">></span> '.htmlspecialchars($msg); ?></a>
                    </li>
                <?php endforeach; ?>
                <a href="#" class="d-flex justify-content-center" onclick="allIsRead('../')">Tous lu</a>
            <?php else: ?>
                <li><a class="dropdown-item" href="#">Vous n'avez pas de notifications</a></li>
            <?php endif; ?>
            </ul>
            </div>
            <!-- Avatar -->
            <div class="dropdown">
            <a
                data-mdb-dropdown-init
                class="dropdown-toggle d-flex align-items-center hidden-arrow"
                href="#"
                id="navbarDropdownMenuAvatar"
                role="button"
                aria-expanded="false"
            >
                <div class="profile-pic-navbar">
                    <img
                        src="<?php echo '../'.$_SESSION['image'];?>"
                        alt="Profile Picture"
                        loading="lazy"
                        />
                </div>
            </a>
            <ul
                class="dropdown-menu dropdown-menu-end"
                aria-labelledby="navbarDropdownMenuAvatar"
            >
            <li>
                <a class="dropdown-item" href="parametre.php">Mes paramètres</a>
                </li>
                <li>
                <a class="dropdown-item" href="myTickets.php">Mes Tickets</a>
                <a class="dropdown-item" href="link.php">Mes Liens</a>
                </li>
                <li><a class="dropdown-item" href="newTickets.php">Nouveau ticket</a></li>
                <?php
                    if ($_SESSION['idroles'] >= 2) {
                        echo "<li>
                                <a class='dropdown-item' href='../admin/index.php'>Panel Admin</a>
                            </li>";
                    }
                ?>
                
                <li>
                <a class="dropdown-item" onclick="deconnexion('../')" href="">Déconnexion</a>
                </li>
            </ul>
            </div>
        </div>
        <!-- Right elements -->
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->

    <div class="container-users-page">
        <?php
        $email = $_GET['email'];

        $sql = "SELECT id, firstname, username, email, lastname, img, idroles, bio, poste, `last-conn`, fonctions, numero FROM users WHERE email = ?";
        $result = $mysqli->prepare($sql);
        $result->bind_param("s", $email);
        $result->execute();
        $result->bind_result($id, $firstname, $username, $email, $lastname, $img, $idroles, $bio, $poste, $lastconn, $fonctions, $numero);
        if ($result->fetch()) {
            echo "
            <div class='container-left-users-page'>
                <div class='profile-pic-users-page'>
                    <img src='../$img' class='' alt=''>
                </div>
                <div class='box-users-page'>
                    <h2>$firstname $lastname</h2>
                    <p id='bio-users-page'>> $bio</p>
                </div>
            </div>
            <div class='container-right-users-page'>
                <div class='box-users-page'>
                    <div class='title-users-page'>
                        <p>Fiche de contact</p>
                    </div>
                    <div class='chest-users-page'>
                        <span><b>Numéro</b>: $numero</span>
                        <span><b>E-mail</b>: $email</span>
                        <span><b>Nom d'utilisateur</b>: $username</span>
                        <span><b>Rôles</b>: $poste</span>
                        <span><b>Fonction</b>: $fonctions</span>
                        <span><b>Dernière connexion</b>: $lastconn</span>";

                        if ($_SESSION['idroles'] == '3') {
                            $_SESSION['idFiche'] = $id;
                            $_SESSION['nomUserFiche'] == $firstname;
                            echo "<div class='boxButton-users-page'><button id='openModalLink-users-page'>Modifier la fiche de contact</button></div>";
                        }

            echo "  </div>
                </div>
            </div>";

        } else {
            echo "Aucun utilisateur trouvé avec cette adresse e-mail.";
        }
    
        ?>
        
    </div>

    <div id="myModal-users-page" class="modal-users-page">
        <div class="modal-content-users-page" style="width: 400px; height: 350px">
            <span class="close-users-page">&times;</span>
            <div class="elementModal-users-page">
                <h3 id="modalTitle-users-page">Modification de la fiche de contact</h3>
                <form action="../php/admin/modifFicheContact.php" id="roleForm-users-page" method="POST">
                    <input type="text" name="fonctions" placeholder="Fonctions">
                    <input type="text" name="email" placeholder="E-mail">
                    <input type="text" name="numero" placeholder="Numéro">
                    <button type="submit">Valider</button>
                </form>
            </div>
            
        </div>
    </div>
    <script src="../js/deconnexion.js"></script>
    <script src="../js/updateNotifications.js"></script>
</body>
<script
  type="text/javascript"
  src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.1/mdb.umd.min.js"
></script>


</html>