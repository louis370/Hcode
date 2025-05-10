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
</head>
<body>
    <h1>Bienvenue, <?php echo htmlspecialchars($user['username']); ?>!</h1>

    <!-- Formulaire pour modifier les informations de l'utilisateur -->
    <h2>Vos informations</h2>
    <h3>Nom d'utilisateur : <?php echo htmlspecialchars($user['username']); ?></h3>
    <h3> Rôle : <?php echo htmlspecialchars($user['role']); ?></h3>
    <h3> Date de création : <?php echo htmlspecialchars($user['created_at']); ?></h3>
    <h3> Mot de passe : <?php echo htmlspecialchars($user['password']); ?></h3>


    <!--Formulaire pour télécharger une vidéo -->
    <h2>Télécharger une vidéo</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <label for="video_theme">Theme de la vidéo :</label>
        <select name="video_theme" id="video_theme">
            <option value="html">HTML</option>
            <option value="css">CSS</option>
            <option value="js">JAVASCRIPT</option>
        </select>
        <label for="video_title">Titre de la vidéo :</label>
        <input type="text" name="video_title" required>
        <br>
        <label for="video_description">Description de la vidéo :</label>
        <input type="text" name="video_description" required>
        <br>
        <label for="video_file">Choisir un fichier vidéo :</label>
        <input type="file" name="video_file" accept="video/*" required>
        <br>
        <input type="submit" name="upload_video" value="Télécharger">
    </form>

    <!-- Liste des vidéos de l'utilisateur -->
    <h2>Vos vidéos</h2>
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