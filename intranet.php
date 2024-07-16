<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
require_once 'php/Sample/actualites/getactualites.php';
require 'php/Sample/core/connection.php';

if (!isset($_SESSION['state'])) {
    header('Location: index.html');
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
    <link rel="stylesheet" href="CSS/styles.css">
    <script src="https://kit.fontawesome.com/11b0336c50.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.1/mdb.min.css" rel="stylesheet"/>
    <title>Intranet Osartis</title>
    <link rel="icon" href="images/1719307876_logo.ico"/>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary">
        <div class="container-fluid">
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
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <a class="navbar-brand mt-2 mt-lg-0" href="#" style="user-select: none; pointer-events: none;">
            <img
                src="images/logo.jpg"
                height="35"
                alt="MDB Logo"
                loading="lazy"
                
            />
            </a>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <div class="nav-item">
                    <a class="nav-link" href="">Osartis Intranet</a>
                </div>
            
            </ul>
        </div>
        <div class="d-flex align-items-center">
            <div class="box-search-intranet-page">
                <form action="php/Sample/search.php" method="GET">
                    <input type="search" name="q" placeholder="Rechercher..." required>
                    <button type="submit"><i class='fa-solid fa-magnifying-glass'></i></button>
                </form>
            </div>
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
                <a href="#" class="d-flex justify-content-center" onclick="allIsRead('')">Tous lu</a>
            <?php else: ?>
                <li><a class="dropdown-item" href="#">Vous n'avez pas de notifications</a></li>
            <?php endif; ?>
            </ul>
            </div>
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
                    src="<?php echo $_SESSION['image'];?>"
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
                <a class="dropdown-item" href="pages/parametre.php">Mes paramètres</a>
                </li>
                <li><a class="dropdown-item" href="pages/locations.php">Locations</a></li>
                <li><a class="dropdown-item" href="pages/myTickets.php">Mes Tickets</a></li>
                <li><a class="dropdown-item" href="pages/link.php">Mes Liens</a></li>
                <li><a class="dropdown-item" href="pages/newTickets.php">Nouveau ticket</a></li>
                <?php
                    if ($_SESSION['roles'] && ($_SESSION['supprimer'] == 1)) {
                        echo "<li>
                                <a class='dropdown-item' href='admin/index.php'>Panel Admin</a>
                            </li>";
                    }
                ?>
                
                <li>
                <a class="dropdown-item" onclick="deconnexion('')" href="">Déconnexion</a>
                </li>
            </ul>
            </div>
        </div>
        </div>
    </nav>
    <?php
        if ($_SESSION['update-state'] == 0) {
            echo '<div class="update-state-intranet-page" style="background-color: red;">
            <span>Une erreur est survenue pendant la mise à jour !</span>
        </div>';
        $_SESSION['update-state'] = 100;
        } else if ($_SESSION['update-state'] == 1) {
            echo '<div class="update-state-intranet-page" style="background-color: green;">
            <span>Votre profil à bien était mise à jour !</span>
        </div>';
        $_SESSION['update-state'] = 100;
        }
    ?>
    
    <?php

    if (!isset($_SESSION['titre']) || !isset($_SESSION['head']) || !isset($_SESSION['body'])) {
        echo "
        
        <div class='container-actu-intranet-page'>
            <div class='box-intranet-page'>
                <p>Actualités du jour</p>
                <p>Aucune actualité</p>
                <p><span id='text-intranet-page'>Malheureusement,</span>
                il n'y a pas d'actualité ce jour.
            </div>
        </div>
        
        ";
    } else {
        $titre = $_SESSION['titre'];
        $head = $_SESSION['head'];
        $body = $_SESSION['body'];
        $sql = "SELECT id, titre, head, body, texts, dates, image FROM actualites ORDER BY id DESC LIMIT 1, 2";
        $result = $mysqli->query($sql);

        echo "
        
        <div class='container-actu-intranet-page'>
            <div class='box-intranet-page'>
                <div id='slider'>";
                echo"
                    <div class='slides active'>
                        <div class='contentContainer-intranet-page'>
                            <p>Actualités du jour</p>
                            <p>$titre</p>
                            <p><span id='text'>$head</span>
                            $body
                            <a href='pages/actualites/actualites.php'>voir plus</a></p>
                        </div>
                    </div>";                 
                
                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            echo"
                            <div class='slides'>
                                <div class='contentContainer-intranet-page'>
                                    <p>Anciennes actualités</p>
                                    <p>".$row['titre']."</p>
                                    <p><span id='text'>".$row['head']."</span>
                                    ".$row['body']."
                                    <a href='pages/actualites/detail_actu.php?id=". $row['id'] ."'>voir plus</a></p>
                                </div>
                            </div>
                            ";                  
                        
                        }
                    }
                echo "</div>
                </div>            
            </div>";
        
                
    }    
    ?>
    <div class="box-intranet-page">
        <div class="network-intranet-page">
            <div class="container-search-intranet-page">
            <h1>Accès rapide</h1>
            <div class="boxContainer-intranet-page">
            <?php
                if (isset($_SESSION['email'])) {
                    $email = $_SESSION['email'];
                    $sql = "SELECT title, `url` FROM link WHERE email = ?";
                    $stmt = $mysqli->prepare($sql);
                    
                    if ($stmt) {
                        $stmt->bind_param("s", $email);
                        $stmt->execute();
                        $stmt->bind_result($title, $url);
                        echo '<div class="box-link-intranet-page">';
                        while ($stmt->fetch()) {
                            $titre = htmlspecialchars($title);
                            echo '<a href='.$url.' target="_BLANK">' . $titre . '</a>';
                        }
                        echo '</div>';
                        $stmt->close();
                    } else {
                        echo "Erreur de préparation de la requête.";
                    }
                } else {
                    header("Location: login.php");
                    exit();
                }
                
                ?>

            </div>
                
            </div>
        </div>
        </div>
    </div>


    <div class="galerie-intranet-page">
        <h1>Galerie photo</h1>
        <div class="boxGalerie-intranet-page">
            <?php
                $sql = "SELECT bio, link FROM galerie";
                $result = $mysqli->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='image-container-intranet-page'>
                                <img src='{$row['link']}' alt='Image Description' loading='lazy'>
                                ";
                                if ($_SESSION['idroles'] == '2' or $_SESSION['idroles'] == 3) {
                                    echo "<div class='iconModify-intranet-page'>
                                            <button class='deleteImage' data-index='{$row['link']}'><i class='fa-solid fa-trash'></i></button>
                                        </div>";
                                }                            
                                echo "
                                <div class='overlay-intranet-page'>
                                    <div class='text-intranet-page'>{$row['bio']}</div>
                                </div>
                            </div>";
                    }
                } else {}
                $mysqli->close();
                ?>
                <input type='file' name='file' id='file' class="file-intranet-page">
                <button id="fileButton-intranet-page">+</button>
        </div>
    </div>

    <div id='popup-intranet-page' class='popup-intranet-page'>
        <div class='popup-content-intranet-page'>
            <span class='close-intranet-page'>&times;</span>
            <span class="mb-2">Ajouter un text pour la photo :</span>
            <textarea id='editPhrase-intranet-page' rows='4' cols='50'></textarea>
            <span class="mb-2 mt-2">Ajouter le lien de l'image (URL) :</span>
            <input type='text' id='editImage-intranet-page' />
            <button id='saveChanges-intranet-page' class="mt-5">Ajouter</button>
        </div>
    </div>
    
    <script src="js/deconnexion.js"></script>
    <script src="js/nav.js"></script>
    <script src="js/slider.js"></script>
    <script src="js/galerie.js"></script>
    <script src="js/updateNotifications.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.1/mdb.umd.min.js"></script>
    
</body>
</html>