<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier</title>
    <link rel="stylesheet" href="../CSS/calendar.css">
</head>
<body>
    <div class="box-locations-page" style="color:red;">
        <a href="#"><i class="fa-solid fa-calendar-days"></i></a>
        <a href="#"><i class="fa-solid fa-car"></i></a>
        <a href="#"><i class="fa-solid fa-house"></i></a>
    </div>
    <div id="calendar"></div>
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Détails</h2>
            <div class="eventDetails">
                <label>Type d'évènement</label>
                <input type="text" disabled id="eventType" placeholder="">
                <label>Nom de l'évenement</label>
                <input type="text" disabled id="eventDetails" placeholder="">
                <label>From</label>
                <input type="text" disabled id="eventDetails" placeholder="">
                <label>To</label>
                <input type="text" disabled id="eventDetails" placeholder="">
                <label>Qui</label>
                <input type="text" disabled id="eventDetails" placeholder="">
            </div>
        </div>
    </div>
    <script src="../js/calendar.js"></script>
</body>
</html>
