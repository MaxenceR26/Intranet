<?php
require_once '../../php/Sample/core/connection.php';
session_start();

if (!isset($_SESSION['state'])) {
    session_destroy();
    header('Location: ../../index.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/panel-nav.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/11b0336c50.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../CSS/notifications.css">
    <title>Admin Panel</title>
    <link rel="icon" href="../../images/1719307876_logo.ico"/>
</head>
<style>
    .validButton {
        background-color: #0075a6;
        width: 1px;
        color: #f9f9f9;
        cursor: pointer;
        &:hover {
            background-color: #0082ba;
            color: #f9f9f9;
            transition: 0.6s ease-in-out;
        }
    }

    #animated-table {
        width: 100%;
        border-collapse: collapse;
    }

    #animated-table th, #animated-table td {
        padding: 10px;
        text-align: left;
    }

    #animated-table tbody tr {
        border-top: 1px solid #ccc; /* Ligne de séparation */
    }

    #animated-table tbody tr:first-child {
        border-top: none; /* Pas de bordure sur la première ligne */
    }

    .box {
        padding: 20px;
        background-color: #fff;
    }
    #toast {
        visibility: hidden;
        position: fixed;
        bottom: 50px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #333;
        color: #fff;
        padding: 16px;
        border-radius: 2px;
        z-index: 1;
    }

    @keyframes fadein {
        from { opacity: 0; bottom: 0; }
        to { opacity: 1; bottom: 30px; }
    }
    @keyframes fadeout {
        from { opacity: 1; bottom: 30px; }
        to { opacity: 0; bottom: 0; }
    }
    #toast.show {
        visibility: visible;
        animation: fadein 0.5s, fadeout 0.5s 2.5s;
    }

    .delButton {
        background-color: #F24142;
        color: #f9f9f9;
        cursor: pointer;
    }

    .ticketButton {
        background-color: #4B971C;
        color: #f9f9f9;
        cursor: pointer;
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.4);
        padding-top: 60px;
    }

    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
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
        <li><a href="newActualites.php">Ajouter une actualité</a></li>
        <li><a href="communes.php">Annuaire mairies</a></li>
        <li><a href="services.php">Annuaire services</a></li>
        <?php
            if ($_SESSION["idroles"] == "3") {
                echo "<li><a class='active' href='tickets.php'>Tickets</a></li>
                <li><a href='archive.php'>Tickets Archivés</a></li>";
            }
        ?>
        <li><a href="../../intranet.php">Intranet</a></li>
    </ul>
</nav>


<div class="box">
    <table id="animated-table">
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Page</th>
                <th>Raison</th>
                <th>Date de création</th>
                <th>Identifiants</th>
                <th>Gravité</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
                $sql = "SELECT * FROM tickets";
                $result = $mysqli->query($sql);
                
                if ($result) {
                    if ($result->num_rows > 0) {
                        $found = false; // Initialisez le drapeau à false
                
                        while ($row = $result->fetch_assoc()) {
                            $raison = $row['raison'];
                            $pages = $row['pages'];
                            $utilisateurs = $row['utilisateurs'];
                            $identifiant = $row['foreignkey'];
                            $date_creation = $row['date_creation'];
                            $color = $row['color'];
                
                            if ($row['etat'] != '1') {
                                $found = true; // Un ticket avec un état différent de 1 a été trouvé
                
                                echo "<tr>
                                <td>$utilisateurs</td>
                                <td id='page'>$pages</td>
                                <td>$raison</td>
                                <td>$date_creation</td>
                                <td>$identifiant</td>
                                <td style='background-color: $color'></td>
                                <td class='validButton'>Valider</td>
                                <td class='delButton' style='width: 100px'>Supprimer</td>";
                
                                if ($pages == 'services' || $pages == 'communes') {
                                    echo "<td class='ticketButton' style='width: 230px'>Apperçu de la modification</td>
                                    </tr>";
                                } else {
                                    echo "</tr>";
                                }
                            }
                        }
                
                        // Après avoir parcouru tous les tickets, vérifiez le drapeau
                        if (!$found) {
                            echo "<tr><td colspan='8'>Aucun ticket trouvé.</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>Aucun ticket trouvé.</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Erreur dans l'exécution de la requête : " . $mysqli->error . "</td></tr>";
                }
                
                $mysqli->close();
                ?>                
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="myModal" class="modal">
    <div class="modal-content" style="width: 350px;">
        <span class="close">&times;</span>
        <div class="elementModal">
            <h2 id="modalTitle">Détails de la modification</h2><br><br>
            <div id="modificationDetails"></div>
        </div>
    </div>
</div>
<script src="../../js/notifications.js"></script>
<script>
    const pages = {
        'actualites': '../../php/Sample/actualites/removeActualites.php',
        'communes': '../../php/admin/modifAnnuaireCommune.php',
        'services': '../../php/admin/modifAnnuaireServices.php',
        '': ''
    }

    function showToast() {
        const toast = document.getElementById("toast");
        toast.classList.add("show");
        setTimeout(() => {
            toast.classList.remove("show");
        }, 3000); // Hide after 3 seconds
    }

   

    document.addEventListener("DOMContentLoaded", function () {    
        const validButton = document.querySelectorAll(".validButton");
        const delButton = document.querySelectorAll(".delButton");
        const ticketButton = document.querySelectorAll(".ticketButton");
        const modal = document.getElementById("myModal");
        const span = document.getElementsByClassName("close")[0];
        const modificationDetails = document.getElementById("modificationDetails");

        // Function to show the modal
        function showModal(details) {
            modificationDetails.innerHTML = details;
            modal.style.display = "block";
        }

        // Close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        ticketButton.forEach(ticket => {
            ticket.addEventListener("click", function(event) {
                event.stopPropagation();
                const row = this.parentElement;
                const identifiants = row.children[4].textContent;
                const page = row.children[1].textContent;

                
                // Make an AJAX request to fetch the modification details
                $.post('getModificationDetails.php', { page:page, identifiants: identifiants }, function(response) {
                    showModal(response);
                }).fail(function() {
                    console.log("Erreur lors de la récupération des détails de la modification.");
                });
            });
        });

        delButton.forEach(del => {
            del.addEventListener("click", function(event) {
                event.stopPropagation();
                const row = this.parentElement;
                const identifiants = row.children[4].textContent;
                var res = confirm("Êtes-vous sûr de vouloir supprimer?");
                
                if (res) {
                    $.post('delTickets.php', { identifiants: identifiants, current_page:'tickets' }, function(response) {
                        window.location.reload();
                        if (response.includes("Ticket supprimé avec succès")) {
                            row.remove();
                        } else {
                            alert("Erreur lors de la suppression du ticket: " + response);
                        }
                    })
                }
                
            })
        })

        validButton.forEach(valid => {
            valid.addEventListener("click", function(event) {
                event.stopPropagation();
                const row = this.parentElement;
                const page = row.children[1].textContent;
                const identifiants = row.children[4].textContent;
                const email = row.children[0].textContent;
                toast = document.querySelector(".toast");
                (closeIcon = document.querySelector(".close")),
                (progress = document.querySelector(".progress"));
                let timer1, timer2;
                if (page in pages) {
                    if (page != "actualites") {
                        $.post(pages[page], { page: page, identifiants: identifiants }, function(response) {
                            console.log('PHP execution result:', response);
                            window.location.reload();

                            if (response.includes("Mise à jour réussie.")) {
                                $.post('delTickets.php', { identifiants: identifiants, current_page:'tickets' }, function(response) {
                                    window.location.reload();
                                    if (response.includes("Ticket supprimé avec succès")) {
                                        row.remove();
                                        $.post('../../php/admin/addNotifications.php', { email: email, message:'Votre ticket à était validé !' }, function(response) {
                                            console.log(response)
                                        })
                                    } else {
                                        alert("Erreur lors de la suppression du ticket: " + response);
                                    }
                                })
                            } else {
                                alert("Erreur lors de la suppression du ticket: " + response);
                            }
                        });
                    } else {
                        $.post(pages[page], { page: page, identifiants: identifiants }, function(response) {
                            console.log('PHP execution result:', response);
                            window.location.reload();

                            if (response.includes("Ticket supprimé avec succès")) {
                                row.remove(); // Supprimer la ligne du tableau
                            } else {
                                alert("Erreur lors de la suppression du ticket: " + response);
                            }
                        });
                    }
                }
            });
        });
        const columns = Array.from({ length: 9 }, (_, i) =>
            document.querySelectorAll(`tbody td:nth-child(${i + 1})`)
        );

        columns.forEach((col, i) => {
            col.forEach((cell, j) => {
                setTimeout(() => {
                    cell.style.opacity = "1";
                    cell.style.transform = "translateY(0)";
                }, (i + j * 1.5) * 50);
            });
        });
    });
</script>
</body>
</html>
