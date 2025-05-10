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

// Traitement du formulaire de téléchargement de vidéo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_video'])) {
    $video_title = $_POST['video_title'];
    $video_file = $_FILES['video_file'];
    $video_description = $_POST['video_description'];
    $video_theme = $_POST['video_theme'];

    // Vérifiez si le fichier est une vidéo
    $allowed_types = ['video/mp4', 'video/avi', 'video/mov', 'video/mkv'];
    if (in_array($video_file['type'], $allowed_types)) {
        $upload_dir = '../videos/'; // Dossier où les vidéos seront stockées
        $timestamp = time();
        $upload_file =  $upload_dir  . $timestamp . '_' . basename($video_file['name']);

        // Déplacez le fichier téléchargé
        if (move_uploaded_file($video_file['tmp_name'], $upload_file)) {
            // Enregistrez les informations de la vidéo dans la base de données
            $insert_stmt = $conn->prepare("INSERT INTO videos (user_id, theme, title, description, url) VALUES (:user_id, :theme, :title, :description, :url)");
            $insert_stmt->bindParam(':user_id', $user_id);
            $insert_stmt->bindParam(':theme', $video_theme);
            $insert_stmt->bindParam(':title', $video_title);
            $insert_stmt->bindParam(':description', $video_description);
            $insert_stmt->bindParam(':url', $upload_file);
            $insert_stmt->execute();
            echo "Vidéo téléchargée avec succès !";
        } else {
            echo "Erreur lors du téléchargement de la vidéo.";
        }
    } else {
        echo "Type de fichier non autorisé. Veuillez télécharger une vidéo.";
    }
}

// Récupérer la liste des vidéos de l'utilisateur
$videos_stmt = $conn->prepare("SELECT * FROM videos WHERE user_id = :user_id");
$videos_stmt->bindParam(':user_id', $user_id);
$videos_stmt->execute();
$videos = $videos_stmt->fetchAll(PDO::FETCH_ASSOC);
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
        .upload-section {
            background-color: #e9ecef; /* Couleur de fond légèrement plus foncée */
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .video-section {
            background-color: #f1f3f5; /* Couleur de fond encore plus foncée */
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
            <h1 class="text-center">Bienvenue, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        </header>

        <section class="info-section mb-4">
            <h1>Vos informations</h1>
            <p>Nom d'utilisateur : <?php echo htmlspecialchars($user['username']); ?></p>
            <p>Rôle : <?php echo htmlspecialchars($user['role']); ?></p>
            <p>Date de création : <?php echo htmlspecialchars($user['created_at']); ?></p>
        </section>

        <section class="upload-section mb-4">
            <h2>Télécharger une vidéo</h2>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="video_theme" class="form-label">Thème de la vidéo :</label>
                    <select name="video_theme" id="video_theme" class="form-select" required>
                        <option value="html">HTML</option>
                        <option value="css">CSS</option>
                        <option value="js">JAVASCRIPT</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="video_title" class="form-label">Titre de la vidéo :</label>
                    <input type="text" name="video_title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="video_description" class="form-label">Description de la vidéo :</label>
                    <input type="text" name="video_description" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="video_file" class="form-label">Choisir un fichier vidéo :</label>
                    <input type="file" name="video_file" class="form-control" accept="video/*" required>
                </div>
                <button type="submit" name="upload_video" class="btn btn-primary">Télécharger</button>
            </form>
        </section>

        <section class="video-section">
            <h2>Vos vidéos</h2>
            <div class="row">
                <?php if (count($videos) > 0): ?>
                    <?php foreach ($videos as $video): ?>
                        <div class="col-md-4 video-item">
                            <div class="list-group-item">
                                <strong><?php echo htmlspecialchars($video['title']); ?></strong>
                                <br>
                                <video width="320" height="240" controls>
                                    <source src="<?php echo htmlspecialchars($video['url']); ?>" type="video/mp4">
                                    Votre navigateur ne supporte pas la lecture de vidéos.
                                </video>
                                <br>
                                <a href="delete_video.php?id=<?php echo $video['video_id']; ?>" class="btn btn-danger mt-2" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette vidéo ?');">Supprimer</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="list-group-item">Aucune vidéo téléchargée.</div>
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

