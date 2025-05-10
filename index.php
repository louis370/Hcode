<?php
session_start();
include('database/conn.php'); // Assurez-vous que le chemin est correct

// Récupérer la liste des vidéos de l'utilisateur
$videos_stmt = $conn->prepare("SELECT * FROM videos AS v INNER JOIN users AS u ON u.user_id = v.user_id GROUP BY v.video_id HAVING v.theme = 'js'");
$videos_stmt->execute();
$videos_js = $videos_stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des vidéos de l'utilisateur
$videos_stmt = $conn->prepare("SELECT * FROM videos AS v INNER JOIN users AS u ON u.user_id = v.user_id GROUP BY v.video_id HAVING v.theme = 'css'");
$videos_stmt->execute();
$videos_css = $videos_stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des vidéos de l'utilisateur
$videos_stmt = $conn->prepare("SELECT * FROM videos AS v INNER JOIN users AS u ON u.user_id = v.user_id GROUP BY v.video_id HAVING v.theme = 'html'");
$videos_stmt->execute();
$videos_html = $videos_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'components/header.php'; ?>
    <section class="home">
        <div class="home-1">
            <img src="image/home-bg.jpg" alt="">
        </div>
        <div class="baniere">
            <h1>Apprendre à coder <br> gratuitement.</h1>
            <h1>Via des tutoriels vidéos.</h1>
            <p>Notre but est de mettre à la disposition de chacun <br>des supports de qualité pour les aider dans leur apprentissage du code</p>
            <div class="computer">
                <img src="image/01.png" alt="">
            </div>
            <div class="but">
                <a href="index.php#footer" class="btn">savoir plus</a>
            </div>
        </div>
    </section>
    <section class="feature" id="fict">
        <h1 class="feature-1">Caracteristique</h1>
        <div class="box-0">

            <div class="box-1">
                <img src="image/icon-1.png" alt="">
                <h3>HTML5</h3>
                <p>Langage de balisage pour structurer et présenter le contenu web, avec de nouvelles fonctionnalités multimédia, graphiques et sémantiques.</p>
                <a href="html.php" class="btn1">pus</a>
            </div>

            <div class="box-1">
                <img src="image/icon-2.png" alt="">
                <h3>CSS</h3>
                <p>Langage de style qui définit la présentation et la mise en page des éléments HTML pour créer des designs web attrayants et responsifs.</p>
                <a href="css.php" class="btn1">pus</a>
            </div>
    
            <div class="box-1">
                <img src="image/icon-3.png" alt="">
                <h3>JavaScript</h3>
                <p>Langage de programmation permettant d'ajouter de l'interactivité, de manipuler le contenu dynamiquement et de créer des applications web complexes.</p>
                <a href="js.php" class="btn1">pus</a>
            </div>
    
        </div>
    
    </section>
    <section class="cours" id="cours">
        <h1 class="ban">nos cours</h1>
        <div class="filtre">
            
            <div class="box-container">

                <div class="box">
                    <img src="image/lg3.jpg" alt="">
                    <div class="info">
                       <li><button type="button" value="html5" class="btn2">HTML5</button> </li>
                    </div>
                </div>
        
                <div class="box">
                    <img src="image/lg2.jpg" alt="">
                    <div class="info">
                        <li><button type="button" value="css3" class="btn2">CSS3</button></li>
                    </div>
                </div>
        
                <div class="box">
                    <img src="image/lg1.jpg" alt="">
                    <div class="info">
                        <li><button type="button" value="js" class="btn2">JavaScript</button></li>
                    </div>
                </div>
                
            </div>
            
        </div>

    </section>
    <div >
        <h1 class="tuy">
            cliquer pour commencer
        </h1>
    </div>
   <div class="col">
    <section id="html5" class="clear">
        <?php if (count($videos_html) > 0): ?>
            <?php $counter = 0;?>
            <h1 class="h">HTML5</h1>
            <p class="intro">Voici une série de tutoriels vidéo pour vous accompagner dans l'apprentissage du langage de balisage de pages web HTML5 de A à Z.</p>
            <div class="all">
            <div class="vidhtml">
            <?php foreach ($videos_html as $video): ?>
                <?php if($counter >= 3) break;?>
                    <div class="flot">
                    <video controls>
                        <source src="<?php echo str_replace("../", "", htmlspecialchars($video['url'])); ?>" type="video/mp4">
                        Désolé, votre navigateur ne prend pas en charge la lecture de vidéos HTML5.
                    </video>
                    <h3> Titre : <?php echo htmlspecialchars($video['title']); ?></h3>
                    <p> Description : <?php echo htmlspecialchars($video['description']); ?></p>
                    <p> Auteur : <?php echo htmlspecialchars($video['username']); ?></p>
                    </div>
            <?php $counter++; ?>
            <?php endforeach; ?>
            </div>
            </div>
        <?php else: ?>
            <li>Aucune vidéo téléchargée.</li>
        <?php endif; ?>
        <div>
            <a href="html.php"> <input type="button" value="voir plus" class="jkl"></a>
         </div>
    </section>
   </div>
   <div class="col">
    <section id="css3" class="clear">
        <?php if (count($videos_css) > 0): ?>
            <?php $counter = 0;?>
            <h1 class="h">CSS3</h1>
            <p class="intro">Apprendre le css3 de simples selecteurs aux medias queries tout au long de cette série de tutoriels vidéo</p>
            <div class="all">
            <div class="vidhtml">
            <?php foreach ($videos_css as $video): ?>
                <?php if($counter >= 3) break;?>
                    <div class="flot">
                    <video controls>
                        <source src="<?php echo str_replace("../", "", htmlspecialchars($video['url'])); ?>" type="video/mp4">
                        Désolé, votre navigateur ne prend pas en charge la lecture de vidéos HTML5.
                    </video>
                    <h3> Titre : <?php echo htmlspecialchars($video['title']); ?></h3>
                    <p> Description : <?php echo htmlspecialchars($video['description']); ?></p>
                    <p> Auteur : <?php echo htmlspecialchars($video['username']); ?></p>
                    </div>
                <?php $counter++; ?>
            <?php endforeach; ?>
            </div>
            </div>
        <?php else: ?>
            <li>Aucune vidéo téléchargée.</li>
        <?php endif; ?>
       <div>
        <a href="css.php"><input type="button" value="voir plus" class="jkl"></a>
       </div>
    </section>
    
   </div>
    <div class="col">
        <section id="js" class="clear">
        <?php if (count($videos_js) > 0):?>
            <?php $counter = 0;?>
            <h1 class="h">JavaScript</h1>
            <p class="intro">ES6 pour toutes les personnes qui voudraient ajouter un peu de dynamisme à leur page web, le DOM et la gestion des évènements y sont abordées.</p>
            <div class="all">
            <div class="vidhtml">
            <?php foreach ($videos_js as $video): ?>
            <?php if($counter >= 3) break;?>
                    <div class="flot">
                    <video controls>
                        <source src="<?php echo str_replace("../", "", htmlspecialchars($video['url'])); ?>" type="video/mp4">
                        Désolé, votre navigateur ne prend pas en charge la lecture de vidéos HTML5.
                    </video>
                    <h3> Titre : <?php echo htmlspecialchars($video['title']); ?></h3>
                    <p> Description : <?php echo htmlspecialchars($video['description']); ?></p>
                    <p> Auteur : <?php echo htmlspecialchars($video['username']); ?></p>
                    </div>
            <?php $counter++; ?>
            <?php endforeach; ?>
            </div>
            </div>
        <?php else: ?>
            <li>Aucune vidéo téléchargée.</li>
        <?php endif; ?>
           <div>
                <a href="js.php"><input type="button" value="voir plus" class="jkl"></a>
           </div>
        </section>
    </div>
    <section>
        <h1 class="tuy1" id="cont">contact</h1>
        <form action="contact.php" method="post">
            <div>
                <div class="inputBox">
                    <label>name</label>
                    <input type="text" required class="intu" id="name" name="name">
                </div>
    
                <div class="inputBox1">
                    <label>email</label>
                    <input type="email" required class="intu" id="email" name="email">
                </div>
    
                <div class="inputBox1">
                    <label>Message</label>
                    <input type="text" required class="intu" id="message" name="message">
                </div>
    
            </div>
            <div class="sec">
                <img src="image/port-img1.jpg" alt="">
                <input type="submit" class="btn2-1" value="send message">
            </div>
        </form>
    </section>
    <?php include 'components/footer.php'; ?>

    <script>
        let previous = null;

        document.querySelectorAll("li button").forEach(button => {
            button.addEventListener('click', () => {
                if(previous){
                    previous.classList.add('clear');
                }
                const display = document.getElementById(button.value);
                display.classList.remove('clear')

                previous = display;
            })
        })
    </script>
    <script src="contact.js"></script>
</body>
</html>