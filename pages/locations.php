<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier</title>
    <link rel="stylesheet" href="../CSS/calendar.css">
</head>
<body>
    <div id="calendar"></div>
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Détails</h2>
            <div>
                <label>Type d'évènement</label>
                <input type="text"  id="eventDetails" placeholder="">
            </div>
        </div>
    </div>
    <script src="../js/calendar.js"></script>
</body>
</html>
