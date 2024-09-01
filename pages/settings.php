<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
require '../php/Sample/core/permission.php';

if (!isset($_SESSION['state'])) {
    session_destroy();
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
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet Osartis | Paramètre</title>
    <link rel="icon" href="../images/1719307876_logo.ico"/>
    <link rel="stylesheet" href="../CSS/styles.css">
    <script src="https://kit.fontawesome.com/11b0336c50.js" crossorigin="anonymous"></script>
    <!-- Font Awesome -->
    <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    rel="stylesheet"
    />
    <!-- Google Fonts -->
    <link
    href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
    rel="stylesheet"
    />
    <!-- MDB -->
    <link
    href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.1/mdb.min.css"
    rel="stylesheet"
    />
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
            <a class="navbar-brand mt-2 mt-lg-0" href="#"  style="user-select: none; pointer-events: none;">
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
                <a class="dropdown-item" href="settings.php">Mes paramètres</a>
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
    
    <form action="../php/Sample/param/upload.php" method="post" enctype="multipart/form-data">
        <div class="container-param-page">
            <div class="left-param-page">
                <div class="box-param-page">
                    <label for="name">Nom</label>
                    <input type="text" name=""class="name-param-page" disabled placeholder="<?php echo $_SESSION['lastname']?>">
                    <label for="firstname">Prénom</label>
                    <input type="text" name=""class="email-param-page" disabled placeholder="<?php echo $_SESSION['firstname']?>">
                    <label for="email">E-mail</label>
                    <input type="text" name=""class="username-param-page" disabled placeholder="<?php echo $_SESSION['email']?>">
                    <label for="username">Username</label>
                    <input type="text" name=""class="firstname-param-page" disabled placeholder="<?php echo $_SESSION['username']?>">
                    <label for="bio">Biographie</label>
                    <input type="text" name="bio" class="bio-param-page">
                </div>
            </div>
            <div class="right-param-page">
                <div class="profile-pic-param-page">
                    <label class="-label" for="file">
                        <span class="glyphicon glyphicon"></span>
                        <span>Modifier l'image</span>
                    </label>
                    <input id="file" type="file" name="profile_pic" onchange="loadFile(event)"/>
                    <img src='<?php echo '../'.$_SESSION['image'];?>' id="output-param-page" width="200" />
                </div>
            </div>
        </div>
        <div class="buttonSave-param-page">
            <input type="submit" class="button-param-page" value="Enregistrer les modifications">
        </div>
    </form>
    <script src="../js/deconnexion.js"></script>
    <script src="../js/nav.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.1/mdb.umd.min.js"></script>
    <script>
        var loadFile = function (event) {
            var image = document.getElementById("output-param-page");
            image.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>
</body>
</html>