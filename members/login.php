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

<form method="post" action="">
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" name="username" required>
    <br>
    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required>
    <br>
    <input type="submit" value="Se connecter">
</form>

<p>Pas encore inscrit ? <a href="register.php">S'inscrire ici</a></p>
