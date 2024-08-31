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
                <input class="input" name="phonenumber" type="tel" pattern="[0-9]{2}[0-9]{2}[0-9]{2}[0-9]{2}[0-9]{2}" required >
                <label for="users">Mot de passe</label>
                <input class="input" name="password" type="password" required>
                
                <div class="conButton">
                    <input type="submit" id="submit" value="Valider">
                </div>
                
            </form>
            <div id="error">
                <p>Un compte est déjà associé à ce nom d'utilisateur ou à cette email !</p>
            </div>
            <div id="errorSys">
                <p>Un problème est survenu, veuillez réessayer plus tard ou contacter un administrateur système.</p>
            </div>
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
                    console.log(response)
                    var data = JSON.parse(response);
                    console.log(data.code)
                    switch (data.code) {
                        case 0:
                            document.getElementById('error').style.display = 'flex';
                            break;
                        case 1:
                            window.location.replace('/intranet/index.php');
                            break;
                        case 405:
                            document.getElementById('error').style.display = 'none';
                            document.getElementById('errorSys').style.display = 'flex';
                            break;
                    }
                    
                },
                error: function(xhr, status, error) {
                    console.log('Erreur AJAX : ' + error);
                }
            });
        });
    });
</script>

</html>