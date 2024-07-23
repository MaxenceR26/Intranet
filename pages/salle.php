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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="page" content="Salles">
    <link rel="stylesheet" href="../CSS/styles.css">
    <script src="https://kit.fontawesome.com/11b0336c50.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.1/mdb.min.css" rel="stylesheet"/>
    <title>Calendrier</title>
</head>
<body class="body-page-calendar">
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
        <!-- Right elements -->
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->
    <div class="main">
        <div class="box-locations-page" style="color:red;">
            <a href="rendezvous.php" id='rendezvous-page-calendar'><i class="fa-solid fa-calendar-days"></i></a>
            <a href="voitures.php" id='voiture-page-calendar'><i class="fa-solid fa-car"></i></a>
            <a href="salle.php"><i class="fa-solid fa-house"></i></a>
        </div>
        <div id="calendar-page-calendar"></div>
        <div id="eventModal-page-calendar" class="modal-page-calendar">
            <div class="modal-content-page-calendar">
                <span class="close-page-calendar">&times;</span>
                <h2>Détails</h2>
                <div class="eventDetails-page-calendar">
                    <label>Type d'évènement</label>
                    <input type="text" disabled id="eventType-page-calendar" placeholder="">
                    <label>Nom de l'évenement</label>
                    <input type="text" disabled id="eventDetails-page-calendar" placeholder="">
                    <label>From</label>
                    <input type="text" disabled id="eventFrom-page-calendar" placeholder="">
                    <label>To</label>
                    <input type="text" disabled id="eventTo-page-calendar" placeholder="">
                    <label>Qui</label>
                    <input type="text" disabled id="eventWho-page-calendar" placeholder="">
                </div>
            </div>
        </div>
        <div id="newEvent-page-calendar">
            <button id='eventButton-page-calendar'>Ajouter un évenement</button>
            <div id="new-page-calendar">
                <label for="name-page-calendar">Nom & Prénom</label>
                <input type="text" id="name-page-calendar" placeholder="Ex: Jean Dupont">
                <label for="title-page-calendar">Titre de l'évenement</label>
                <input type="text" id="title-page-calendar" placeholder="Ex: Réunion administrative">
                <label for="description-page-calendar">Description</label>
                <textarea type="text" id="desc-page-calendar" placeholder="Ex: Rendez vous dans la salle .. pour la réunion....."></textarea>
                <label for="from-page-calendar">Le</label>
                <input type="text" id="from-page-calendar">
                <label for="to-page-calendar">L'heures</label>
                <input type="text" id="hours-page-calendar" placeholder="Ex: 17:00">
                <input type='submit' id='submitButton-page-calendar'></input>
            
                <div class="return-page-calendar">
                    <button id='backButton-page-calendar'>Retour</button>
                </div>
            
            </div>
        </div>
    </div>
    <script>
        const button = document.getElementById('eventButton-page-calendar');
        const newEvent = document.getElementById('new-page-calendar');
        const eventModal = document.getElementById('calendar-page-calendar');
        const returnButton = document.getElementById('backButton-page-calendar');
        const submitButton = document.getElementById('submitButton-page-calendar');
        
        button.addEventListener('click', function() {
            button.style.display = 'none';
            eventModal.style.display = 'none';
            newEvent.style.display = 'flex';
            document.getElementById('from-page-calendar').value = window.dateDay;
        });

        returnButton.addEventListener('click', function() {
            returnButton.style.display = 'none';
            eventModal.style.display = 'block';
            button.style.display = 'flex';
            newEvent.style.display = 'none';
        });

        submitButton.addEventListener('click', function() {
            window.name = document.getElementById('name-page-calendar').value;
            window.title = document.getElementById('title-page-calendar').value;
            window.desc = document.getElementById('desc-page-calendar').value;
            window.dateDay = document.getElementById('from-page-calendar').value;
            window.hours = document.getElementById('hours-page-calendar').value;
            newEvents();
        });
        
        function newEvents() {
            axios.post('http://localhost:5000/createevent/', {
                event_title: window.title,
                event_date: window.dateDay,
                descriptions: window.desc,
                hours: window.hours,
                type: currentScript
            }, {
                headers: {
                    'accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                window.location.reload();
                button.style.display = 'none';
            })
            .catch(error => {
                console.error('Une erreur est survenue pendant l\'execution de la requête', error);
            });
        }
    </script>
    
    <script src="../js/calendar.js"></script>
    <script src="../js/deconnexion.js"></script>
    <script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.1/mdb.umd.min.js"
    ></script>
    <script src="../js/updateNotifications.js"></script>
</body>
</html>
