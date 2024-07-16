<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
session_start();
require_once '../php/Sample/core/connection.php';

if (!isset($_SESSION['state'])) {
    header('Location: ../index.html');
    exit();
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/11b0336c50.js" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.1/mdb.min.css" rel="stylesheet"/>
    <title>Intranet Osartis | Tickets</title>
    <link rel="icon" href="../images/1719307876_logo.ico"/>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary">
        <div class="container-fluid">
            <button data-mdb-collapse-init class="navbar-toggler" type="button" data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <a class="navbar-brand mt-2 mt-lg-0" href="#">
                    <img src="../images/logo.jpg" height="35" alt="MDB Logo" loading="lazy"/>
                </a>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <div class="nav-item">
                        <a class="nav-link" href="">Osartis Intranet</a>
                    </div>
                </ul>
            </div>
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
                <div class="dropdown">
                    <a data-mdb-dropdown-init class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#" id="navbarDropdownMenuAvatar" role="button" aria-expanded="false">
                        <div class="profile-pic-navbar">
                            <img src="<?php echo '../'.$_SESSION['image'];?>" alt="Profile Picture" loading="lazy"/>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                    <li>
                        <a class="dropdown-item" href="parametre.php">Mes paramètres</a>
                        </li>
                        <li>
                        <a class="dropdown-item" href="myTickets.php">Mes Tickets</a>
                        <a class="dropdown-item" href="link.php">Mes Liens</a>
                        </li>
                        <li><a class="dropdown-item" href="newTickets.php">Nouveau ticket</a></li>
                        <?php
                            if ($_SESSION['roles'] && ($_SESSION['supprimer'] == 1)) {
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
        </div>
    </nav>

    <div class="container-newtickets-page box-newtickets-page">
        <div id="toast"><div id="img"><i class="fa-solid fa-paper-plane"></i></div><div id="desc">Votre ticket a bien été envoyé</div></div>
        <div class="content-newtickets-page">
            <h1 class="mb-3">Créer un ticket</h1>
            <input type="text" placeholder="Objet" id='objet-newtickets-page' required>
            <div class="d-flex mt-5 mb-5">
                <button class="me-3" title="Gravité 1 : Léger, pas urgent" onclick="setColor(this, 'yellow')">Gravité 1</button>
                <button class="me-3" title="Gravité 2 : Urgent" onclick="setColor(this, 'orange')">Gravité 2</button>
                <button class="me-3" title="Gravité 3 : Très urgent, nous interviendrons au plus vite" onclick="setColor(this, 'red')">Gravité 3</button>
            </div>
            <input type="text" placeholder="Nom et prénom" id="nomPrenom-newtickets-page" class="mb-5" required>
            <textarea class="mb-4" id="exp-newtickets-page"></textarea>
            <a href='#' id="send-newtickets-page" required style='padding:10px; color: gray; border: 1px solid gray; font-weight:600;'>Envoyer</a>
        </div>
    </div>
    <script src="../js/nav.js"></script>
    <script src="../js/deconnexion.js"></script>
    <script src="../js/tickets.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.1/mdb.umd.min.js"></script>
    <script src="../js/notifications.js"></script>
</body>
</html>
