<?php
session_start();
include('database/conn.php'); // Assurez-vous que le chemin est correct

// Récupérer la liste des vidéos de l'utilisateur
$videos_stmt = $conn->prepare("SELECT * FROM videos AS v INNER JOIN users AS u ON u.user_id = v.user_id GROUP BY v.video_id HAVING v.theme = 'js'");
$videos_stmt->execute();
$videos = $videos_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JavaScript</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'components/header.php'; ?>
    <div class="ap">
        <h1>JavaScript</h1>
    </div>
    <div class="col">
        <section id="html5">
        <?php if (count($videos) > 0): ?>
            <h1 class="h"></h1>
            <p class="intro">ES6 pour toutes les personnes qui voudraient ajouter un peu de dynamisme à leur page web, le DOM et la gestion des évènements y sont abordées.</p>
            <div class="vidhtml"></div>
        <?php foreach ($videos as $video): ?>
                <div class="flot">
                    <video controls>
                        <source src="<?php echo str_replace("../", "", htmlspecialchars($video['url'])); ?>" type="video/mp4">
                        Désolé, votre navigateur ne prend pas en charge la lecture de vidéos HTML5.
                    </video>
                    <h3> Titre : <?php echo htmlspecialchars($video['title']); ?></h3>
                    <p> Description : <?php echo htmlspecialchars($video['description']); ?></p>
                    <p> Auteur : <?php echo htmlspecialchars($video['username']); ?></p>
                </div>
            <?php endforeach; ?>
            </div>
        <?php else: ?>
            <li>Aucune vidéo téléchargée.</li>
        <?php endif; ?>
        </section>
       </div>
       <?php include 'components/footer.php'; ?>
</body>
</html>