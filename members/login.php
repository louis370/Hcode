<?php
include '../database/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            echo "Connexion réussie ! Bienvenue, " . htmlspecialchars($username) . "!";
            // Vous pouvez démarrer une session ici si nécessaire
            session_start();
            $_SESSION['user_id'] = $user['user_id']; // Stocker l'ID de l'utilisateur
            $_SESSION['username'] = $user['username']; // Stocker le nom d'utilisateur

            if($user['role'] === 'user'){
                header("Location: dashboard.php");
                exit(); // Assurez-vous d'appeler exit() après header()
            }
            else{
                header("Location: admin_dashboard.php");
                exit(); // Assurez-vous d'appeler exit() après header()
            }
        } else {
            echo "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la connexion : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <!-- Lien vers Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Connexion</h1>
        <form method="post" action="" class="mt-4">
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur :</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe :</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>

        <p class="mt-3 text-center">Pas encore inscrit ? <a href="register.php">S'inscrire ici</a></p>
    </div>

    <!-- Lien vers Bootstrap 5 JS (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
