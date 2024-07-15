<?php
session_start();
require '../Sample/core/connection.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $message = $_POST['message'];
    $read = 0;
    $stmt = $mysqli->prepare("INSERT INTO notifications (user_id, `message`, is_read, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $email, $message, $read);        

    if ($stmt->execute()) {
        echo "Ticket fait";
    } else {
        echo "Erreur la notifications n'a pas était crée.";
    }
}

?>