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

// Vérifiez si l'ID de la vidéo est passé en paramètre
if (isset($_GET['id'])) {
    $video_id = $_GET['id'];

    // Récupérer le chemin du fichier vidéo à partir de la base de données
    if($user['role'] === 'user'){
        $stmt = $conn->prepare("SELECT url FROM videos WHERE video_id = :video_id AND user_id = :user_id");
        $stmt->bindParam(':video_id', $video_id);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $video = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    else{
        $stmt = $conn->prepare("SELECT url FROM videos WHERE video_id = :video_id");
        $stmt->bindParam(':video_id', $video_id);
        $stmt->execute();
        $video = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($video) {
        // Supprimer le fichier vidéo du système de fichiers
        if (file_exists($video['url'])) {
            unlink($video['url']); // Supprime le fichier
        }

        // Supprimer l'enregistrement de la base de données
        $delete_stmt = $conn->prepare("DELETE FROM videos WHERE video_id = :video_id");
        $delete_stmt->bindParam(':video_id', $video_id);
        $delete_stmt->execute();

        echo "Vidéo supprimée avec succès !";
    } else {
        echo "Vidéo non trouvée.";
    }
} else {
    echo "Aucun ID de vidéo spécifié.";
}

// Rediriger vers le tableau de bord après la suppression
if($user['role'] === 'user'){
    header("Location: dashboard.php");
    exit();
}
else{
    header("Location: admin_dashboard.php");
    exit();
}
?>
