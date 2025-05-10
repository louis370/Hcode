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
    <!-- Lien vers Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .info-section {
            background-color: #f8f9fa; /* Couleur de fond claire */
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .user-section {
            background-color: #e9ecef; /* Couleur de fond légèrement plus foncée */
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .message-section {
            background-color: #f1f3f5; /* Couleur de fond encore plus foncée */
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .video-section {
            background-color: #f8f9fa; /* Couleur de fond claire */
            padding: 20px;
            border-radius: 5px;
        }
        .video-item {
            margin-bottom: 20px; /* Espacement entre les vidéos */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <header>
            <h1>Bienvenue, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        </header>

        <section class="info-section">
            <h2>Vos informations</h2>
            <p>Nom d'utilisateur : <?php echo htmlspecialchars($user['username']); ?></p>
            <p>Rôle : <?php echo htmlspecialchars($user['role']); ?></p>
            <p>Date de création : <?php echo htmlspecialchars($user['created_at']); ?></p>
        </section>

        <section class="user-section">
            <h2>Les utilisateurs inscrits</h2>
            <ul>
                <?php if (count($users) > 0): ?>
                    <?php foreach ($users as $user): ?>
                        <li>
                            <p>Username : <?php echo htmlspecialchars($user['username']); ?></p>
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
        </section>

        <section class="message-section">
            <h2>Les messages des utilisateurs</h2>
            <div>
                <?php if (count($messages) > 0): ?>
                    <?php foreach ($messages as $message): ?>
                        <div>
                            <h3>Nom : <?php echo htmlspecialchars($message['name']); ?></h3>
                            <p>Email : <?php echo htmlspecialchars($message['email']); ?></p>
                            <p>Message : <?php echo htmlspecialchars($message['message']); ?></p>
                            <br>
                            <a href="delete_message.php?id=<?php echo $message['message_id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">Supprimer</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Aucun message.</li>
                <?php endif; ?>
            </div>
        </section>

        <section class="video-section">
            <h2>Les vidéos disponibles</h2>
            <div class="row">
                <?php if (count($videos) > 0): ?>
                    <?php foreach ($videos as $video): ?>
                        <div class="col-md-4 video-item">
                            <div class="card">
                            <div class="card-body">
                                    <strong><?php echo htmlspecialchars($video['title']); ?></strong>
                                    <br>
                                    <video width="320" height="240" controls>
                                        <source src="<?php echo htmlspecialchars($video['url']); ?>" type="video/mp4">
                                        Votre navigateur ne supporte pas la lecture de vidéos.
                                    </video>
                                    <h3>Thème : <?php echo htmlspecialchars($video['theme']); ?></h3>
                                    <p>Titre : <?php echo htmlspecialchars($video['title']); ?></p>
                                    <p>Description : <?php echo htmlspecialchars($video['description']); ?></p>
                                    <p>Auteur : <?php echo htmlspecialchars($video['username']); ?></p>
                                    <br>
                                    <a href="delete_video.php?id=<?php echo $video['video_id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette vidéo ?');" class="btn btn-danger">Supprimer</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <p>Aucune vidéo téléchargée.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <a href="logout.php" class="btn btn-secondary mt-4">Se déconnecter</a> <!-- Lien pour se déconnecter -->
    </div>

    <!-- Lien vers Bootstrap 5 JS (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
