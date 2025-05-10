<?php
include('database/conn.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validation simple
    $errors = [];
    if (empty($name)) {
        $errors[] = "Le nom est requis.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Un email valide est requis.";
    }
    if (empty($message)) {
        $errors[] = "Le message est requis.";
    }

    // Si aucune erreur, traiter les données (par exemple, les enregistrer dans une base de données)
    if (empty($errors)) {
        try{
            // Préparer et lier
            $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (:name, :email, :message)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message', $message);
            // Exécuter la requête
            $stmt->execute();
            echo "Message envoyé avec succès.";
            header("Location: index.php");
        }catch (PDOException $e) {
            echo "Erreur lors de l'inscription : " . $e->getMessage();
            header("Location: index.php");
        }
    } else {
        // Afficher les erreurs
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
        header("Location: index.php");
    }
} else {
    echo "Méthode de requête non valide.";
    header("Location: index.php");
}
?>
