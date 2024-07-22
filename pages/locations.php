<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <script src="https://kit.fontawesome.com/11b0336c50.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.1/mdb.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../CSS/calendar.css">
</head>
<body>
    <div class="main">
        <div class="box-locations-page" style="color:red;">
            <a href="#"><i class="fa-solid fa-calendar-days"></i></a>
            <a href="#"><i class="fa-solid fa-car"></i></a>
            <a href="#"><i class="fa-solid fa-house"></i></a>
        </div>
        <div class="rdv">
            <?php include 'rendezvous.php';?>
        </div>
    </div>
    <script src="../js/calendar.js"></script>
</body>
</html>
