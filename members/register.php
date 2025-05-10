<?php
include '../database/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hachage du mot de passe

    try {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        echo "Inscription réussie !, Vous pouvez vous connectez";
        // Redirection vers la page de connexion après une inscription réussie
        header("Location: login.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de l'inscription : " . $e->getMessage();
    }
}
?>

<form method="post" action="">
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" name="username" required>
    <br>
    <label for="email">Email :</label>
    <input type="email" name="email" required>
    <br>
    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required>
    <br>
    <input type="submit" value="S'inscrire">
</form>
