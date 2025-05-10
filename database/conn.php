<?php
$host = 'localhost';
$username = 'root';
$password = '12345';
$database = 'hcode';

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM users  WHERE role = :role");
    $stmt->execute(['role' => 'admin']);

    if   ($stmt->rowCount() === 0){
        $username = "admin";
        $password = '12345';
        $email = "admin@email.com";
        $password = password_hash($password, PASSWORD_DEFAULT);
        $role = "admin";
        
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
    }
} catch (PDOException $e) {
    die("Échec de la connexion : " . $e->getMessage());
}
?>
