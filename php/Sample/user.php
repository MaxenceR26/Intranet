<?php

require_once 'core/connection.php';

session_start();

$_SESSION['state'] = false;
$date = date("d-m-Y H:i");

$username = $_GET["username"];
$password = $_GET["password"];

$sql = "SELECT id, firstname, username, email, lastname, img, idroles, bio, `password` FROM users WHERE username = '$username'";
$result = $mysqli->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['username'] == $username and password_verify($password, $row['password'])){
            $_SESSION['state'] = true;
            $_SESSION['firstname'] = $row['firstname'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['lastname'] = $row['lastname'];
            $_SESSION['image'] = $row['img'];
            $_SESSION['idroles'] = $row['idroles'];
            $_SESSION['bio'] = $row['bio'];
            $_SESSION['iduser'] = $row['id'];
            $_SESSION['update-state'] = 100;

            $sql = "UPDATE users SET `last-conn` = ? WHERE username = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ss", $date, $row['username']);
            $stmt->execute();
            $stmt->close();
        } else {
            session_destroy();
            header('Location: ../../index.php');
            exit();
        }
    } else {
        session_destroy();
        header('Location: ../../index.php');
        exit();
    }
    $result->free();
} else {
    echo "Erreur dans l'exécution de la requête : " . $mysqli->error;
}

