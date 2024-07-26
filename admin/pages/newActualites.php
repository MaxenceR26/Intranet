<?php session_start()?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="http://code.jquery.com/jquery.js" type="text/javascript"></script>
    <link rel="stylesheet" href="../../CSS/panel-nav.css">
    <link rel="stylesheet" href="../../CSS/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <title>Admin Panel</title>
    <link rel="icon" href="../../images/1719307876_logo.ico"/>
</head>
<style>
    .upload-container {
        border: 2px dashed #ccc;
        border-radius: 10px;
        width: 300px;
        height: 200px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        transition: border-color 0.3s;
        margin: 15px;
        position: relative;
    }

    .upload-container:hover {
        border-color: #888;
    }

    .upload-label {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
        text-align: center;
        color: #aaa;
        text-decoration: none;
        cursor: pointer;
    }

    .upload-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .plus-sign {
        font-size: 48px;
        line-height: 1;
    }

    .upload-text {
        margin-top: 10px;
        font-size: 16px;
        line-height: 1;
    }

    .upload-input {
        display: none;
    }

    .upload-preview {
        max-width: 100%;
        max-height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
        display: none;
    }
</style>
<body>
    <nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fas fa-bars"></i>
        </label>
        <label class="logo">Panel Admin</label>
        <ul>
        <?php
            if ($_SESSION["idroles"] == "3") {
                echo "<li><a href='../index.php'>Gestion utilisateurs</a></li>";
            }
        ?>
            <li><a class='active' href="newActualites.php">Ajouter une actualité</a></li>
            <li><a href="communes.php">Annuaire mairies</a></li>
            <li><a href="services.php">Annuaire services</a></li>
            <?php
            if ($_SESSION["idroles"] == "3") {
                echo "<li><a href='tickets.php'>Tickets</a></li>
                <li><a href='archive.php'>Tickets Archivés</a></li>";
            }
            ?>
            <li><a href="../../intranet.php">Intranet</a></li>
        </ul>
    </nav>
    <form id="formulaire" method="POST" enctype="multipart/form-data">
        <div class="box">
            <label for="title">Titre</label>
            <input type="text" name="title" placeholder="Titre de l'article">
            <label for="title">En-tête</label>
            <input type="text" name="head" placeholder="En-tête (ex: Chers collègues,)">
            <label for="title">Body</label>
            <input type="text" name="body" placeholder="Body (Les 4-5-6 première lignes)">
            <label for="title">Article complet</label>
            <textarea name="text" id="text" placeholder="Article complet (Sans le titre, sans l'entête ! Remettre le body !)"></textarea>
            <div class="upload-container">
                <label for="file-upload" class="upload-label">
                    <div class="upload-content" id="upload-content">
                        <div class="plus-sign">+</div>
                        <div class="upload-text">AJOUTER UNE IMAGE</div>
                    </div>
                    <div style="display:flex; justify-content:center; align-items:center;"><img id="upload-preview"></div>
                </label>
                <input id="file-upload" type="file" name="image" class="upload-input" onchange="previewImage(event)">
                
            </div>
            <input type="submit" value="Publier l'actualité">
        </div>
    </form>

    <script>
        $(function() {
            $("#formulaire").submit(function(event) {
                event.preventDefault();
                
                var formData = new FormData(this);
                
                $.ajax({
                    type: 'POST',
                    url: '../../php/Sample/actualites/addactualites.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        window.location.replace('/intranet/intranet.php');
                    },
                    error: function(xhr, status, error) {
                        console.log('Erreur AJAX : ' + error);
                    }
                });
            });
        });

        function previewImage(event) {
            var preview = document.getElementById('upload-preview');
            var upload_content = document.getElementById('upload-content');
            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.style.display = 'flex';
            preview.style.width = '50%';
            preview.style.height = 'auto';
            upload_content.style.display = 'none';

        }
    </script>
</body>
</html>
