<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier Interactif</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="calendar">
            <?php include 'calendar.php'; ?>
        </div>
        <div class="event-form">
            <form action="add_event.php" method="post">
                <h3>Ajouter un événement</h3>
                <label for="event-date">Date:</label>
                <input type="date" id="event-date" name="event_date" required>
                <label for="event-title">Titre:</label>
                <input type="text" id="event-title" name="event_title" required>
                <button type="submit">Ajouter</button>
            </form>
        </div>
        <div class="event-details" id="event-details" style="display:none;">
            <h3>Détails de l'événement</h3>
            <p id="event-info"></p>
            <button onclick="document.getElementById('event-details').style.display='none';">Fermer</button>
        </div>
    </div>
    <script>
        function showEventDetails(eventTitle) {
            document.getElementById('event-info').innerText = eventTitle;
            document.getElementById('event-details').style.display = 'block';
        }
    </script>
</body>
</html>
