<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
require_once '../core/connection.php';
session_start();

if (!isset($_SESSION['state'])) {
    header('Location: index.html');
} else {

    $bio = $_POST['bio'];

    if (isset($bio) || (isset($_FILES['profile_pic']))) {

        if (isset($bio) && !empty($bio)) {
            $_SESSION['bio'] = $bio;
        
            $sql = "UPDATE users SET bio = ? WHERE id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $bio, $_SESSION['iduser']);
        
            if ($stmt->execute()) {
                echo "La biographie a été mise à jour avec succès.";
                $_SESSION['update-state'] =  1;
            } else {
                echo "Erreur lors de la mise à jour de la biographie : " . $mysqli->error;
                $_SESSION['update-state'] = 0;
            }
        
            $stmt->close();
        
        }
        
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../../../images/';
            $tmp_name = $_FILES['profile_pic']['tmp_name'];
            $new_name = basename($_FILES['profile_pic']['name']);
            $new_path = $upload_dir . $new_name;
        
            if (file_exists($new_path)) {
                // Mise à jour du chemin de l'image dans la session
                $_SESSION['image'] = 'images/' . $new_name;
                // Mise à jour du chemin de l'image dans la base de données


                $sql = "UPDATE users SET img = ? WHERE id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("si", $_SESSION['image'], $_SESSION['iduser']);
        
                if ($stmt->execute()) {
                    echo "L'image de profil a été mise à jour avec succès.";
                    $_SESSION['update-state'] =  1;
                } else {
                    echo "Erreur lors de la mise à jour de l'image de profil : " . $mysqli->error;
                    $_SESSION['update-state'] = 0;
                }
        
                $stmt->close();
            } else {
                if (move_uploaded_file($tmp_name, $new_path)) {
                    // Mise à jour du chemin de l'image dans la session
                    $_SESSION['image'] = 'images/' . $new_name;
            
                    // Mise à jour du chemin de l'image dans la base de données
                    $sql = "UPDATE users SET img = ? WHERE id = ?";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("si", $_SESSION['image'], $_SESSION['iduser']);
            
                    if ($stmt->execute()) {
                        echo "L'image de profil a été mise à jour avec succès.";
                        $_SESSION['update-state'] =  1;
                    } else {
                        echo "Erreur lors de la mise à jour de l'image de profil : " . $mysqli->error;
                        $_SESSION['update-state'] = 0;
                    }
            
                    $stmt->close();
                } else {
                    echo "Erreur lors de l'upload de l'image de profil.";
                    $_SESSION['update-state'] = 0;
                }
            }
            
        }
        
        header('Location: ../../../intranet.php');
    } else {
        header('Location: ../../../intranet.php');
    }

}


?>