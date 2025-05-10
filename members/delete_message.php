<?php
session_start();
include('../database/conn.php'); // Assurez-vous que le chemin est correct

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les informations de l'utilisateur
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}


// Vérifiez si l'ID du message est passé en paramètre
if (isset($_GET['id'])) {
    $message_id = $_GET['id'];
    // Supprimer l'enregistrement de la base de données
    $delete_stmt = $conn->prepare("DELETE FROM messages WHERE message_id = :message_id");
    $delete_stmt->bindParam(':message_id', $message_id);
    $delete_stmt->execute();
    if  ($stmt->rowCount() === 0){
    echo "Message supprimé avec succès !";
    } else {
        echo "Message introuvable.";
    }
} else {
    echo "Aucun ID du message spécifié.";
}

// Rediriger vers le tableau de bord après la suppression
header("Location: admin_dashboard.php");
exit();
?>