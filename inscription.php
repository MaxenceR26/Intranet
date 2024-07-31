<?php
session_start();
if ($_SESSION['state']) {
    header('Location: intranet.html');
} else {
    session_destroy();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/login.css">
    <script src="http://code.jquery.com/jquery.js" type="text/javascript"></script>
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="box">
            <img src="images/logo.jpg" width="256">
            <form id="formulaire" method="get">
                <label for="users">Nom d'utilisateur</label>
                <input class="input" name="username" type="text" required>
                <label for="users">Prénom</label>
                <input class="input" name="firstname" type="text" required>
                <label for="users">Nom</label>
                <input class="input" name="lastname" type="text" required>
                <label for="users">E-mail</label>
                <input class="input" name="email" type="email" required>
                <label for="users">Fonctions</label>
                <input class="input" name="fonctions" type="text" required>
                <label for="users">Numéro de téléphone</label>
                <input class="input" name="phonenumber" type="text" required>
                <label for="users">Mot de passe</label>
                <input class="input" name="password" type="password" required>
                
                <div class="conButton">
                    <input type="submit" id="submit" value="Valider">
                </div>
                
            </form>
        </div>
    </div>
</body>

<script>
    $(function() {
        $("#formulaire").submit(function(event) {
            event.preventDefault();
            
            var form = $(this);
            var formData = form.serialize();
            
            $.ajax({
                type: 'GET',
                url: 'php/Sample/inscription.php',
                data: formData,
                success: function(response) {
                    window.location.replace('/intranet/index.html');
                },
                error: function(xhr, status, error) {
                    console.log('Erreur AJAX : ' + error);
                }
            });
        });
    });
</script>

</html>