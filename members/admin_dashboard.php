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
    header("Location: login.php");
    exit();
}

// Récupérer la liste des vidéos des utilisateurs
$videos_stmt = $conn->prepare("SELECT * FROM videos AS v INNER JOIN users AS u ON u.user_id = v.user_id GROUP BY v.video_id");
$videos_stmt->execute();
$videos = $videos_stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des messages
$messages_stmt = $conn->prepare("SELECT * FROM messages");
$messages_stmt->execute();
$messages = $messages_stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des utilisateurs
$role = "user";
$users_stmt = $conn->prepare("SELECT * FROM users WHERE role = :role");
$users_stmt->bindParam(':role', $role);
$users_stmt->execute();
$users = $users_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
</head>
<body>
    <h1>Bienvenue, <?php echo htmlspecialchars($user['username']); ?>!</h1>

    <!-- Les informations de l'utilisateur -->
    <h2>Vos informations</h2>
    <h3>Nom d'utilisateur : <?php echo htmlspecialchars($user['username']); ?></h3>
    <h3> Rôle : <?php echo htmlspecialchars($user['role']); ?></h3>
    <h3> Date de création : <?php echo htmlspecialchars($user['created_at']); ?></h3>
    <h3> Mot de passe : <?php echo htmlspecialchars($user['password']); ?></h3>

    <!-- Liste des utilisateurs -->
    <h2>Les utilisateurs inscrits</h2>
    <ul>
        <?php if (count($users) > 0): ?>
            <?php foreach ($users as $user): ?>
                <li>
                    <h3>Username : <?php echo htmlspecialchars($user['username']); ?></h3>
                    <p>Email : <?php echo htmlspecialchars($user['email']); ?></p>
                    <p>Rôle : <?php echo htmlspecialchars($user['role']); ?></p>
                <br>
                <a href="delete_user.php?id=<?php echo $user['user_id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>Aucun utilisateur inscrit.</li>
        <?php endif; ?>
    </ul>

    <!-- Liste des messages -->
    <h2>Les messages des utilisateurs</h2>
    <ul>
        <?php if (count($messages) > 0): ?>
            <?php foreach ($messages as $message): ?>
                <li>
                    <h3>Nom : <?php echo htmlspecialchars($message['name']); ?></h3>
                    <p>Email : <?php echo htmlspecialchars($message['email']); ?></p>
                    <p>Message : <?php echo htmlspecialchars($message['message']); ?></p>
                <br>
                <a href="delete_user.php?id=<?php echo $user['user_id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>Aucun utilisateur inscrit.</li>
        <?php endif; ?>
    </ul>

    <!-- Liste des vidéos des utilisateurs -->
    <h2>Les vidéos disponibles</h2>
    <ul>
        <?php if (count($videos) > 0): ?>
            <?php foreach ($videos as $video): ?>
                <li>
                    <strong><?php echo htmlspecialchars($video['title']); ?></strong>
                    <br>
                    <video width="320" height="240" controls>
                        <source src="<?php echo htmlspecialchars($video['url']); ?>" type="video/mp4">
                        Votre navigateur ne supporte pas la lecture de vidéos.
                    </video>
                    <h3> Theme : <?php echo htmlspecialchars($video['theme']); ?></h3>
                    <p> Titre : <?php echo htmlspecialchars($video['title']); ?></p>
                    <p> Description : <?php echo htmlspecialchars($video['description']); ?></p>
                    <p> Auteur : <?php echo htmlspecialchars($video['username']); ?></p>
                <br>
                <a href="delete_video.php?id=<?php echo $video['video_id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette vidéo ?');">Supprimer</a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>Aucune vidéo téléchargée.</li>
        <?php endif; ?>
    </ul>

    <a href="logout.php">Se déconnecter</a> <!-- Lien pour se déconnecter -->
</body>
</html>