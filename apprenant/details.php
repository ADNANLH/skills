<?php
session_start();
$id_formation = $_GET['id_formation'];
require "../connection.php";
$id_appr = isset($_SESSION['id_appr']) ? $_SESSION['id_appr'] : "";



?>
<!DOCTYPE html>
<html lang="en">




<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="./images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="./style.css">
    <title>skills</title>
</head>

<header>

    <nav class="site-nav">
        <div class="site-navigation d-flex justify-content-between fixed-top py-3 px-5 align-items-center" style="background-color:#198754;">
            <div class="site-navigation  d-flex justify-content-end fixed-top py-3 px-5 align-items-center navi">
                <div class=" d-flex  fixed-top  py-3 px-5 align-items-center ">

                    <a href="index.php"><img class="logo" src="./images/logo.png" width="70px"></a>

                    <li class="js-clone-nav me-4 align-items-center d-flex text-center site-menu list-unstyled"><a class='text-decoration-none' href="index.php">Accueil</a></li>
                    <li class="js-clone-nav me-4 align-items-center d-flex text-center site-menu list-unstyled"><a class='text-decoration-none' href="reservation.php">Sessions</a></li>

                    <?php
                    if (empty($id_appr)) {
                        echo "<li class='js-clone-nav me-4 align-items-center d-flex text-center site-menu list-unstyled'><a class='text-decoration-none' href='signin.php'>Connexion</a></li>";
                    } else {
                        echo "<a href = 'profile.php'><img src='./images/user.png' alt='profile' ></a>";
                    }
                    ?>
                </div>

                </li>
                </ul>
            </div>
    </nav>
</header>

<body>

    <?php
    $sql = "SELECT f.*, s.id_session, s.date_debut, s.date_fin, s.etat, s.places,
            fo.id_formateur, fo.nom_formateur, fo.prenom_formateur
            FROM formations f
            INNER JOIN sessions s ON f.id_formation = s.id_formation
            INNER JOIN formateurs fo ON s.id_formateur = fo.id_formateur
            WHERE f.id_formation = :id_formation
        ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_formation', $id_formation);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    ?>

    <div class="hero">



        <div class="container">
            <h1><?php echo $row['titre']; ?></h1>
            <h3><?php echo $row['categorie']; ?> : </h3>
            <div class="card">
                <form method="POST" action="">

                    <div class='bg-image hover-overlay ripple' data-mdb-ripple-color='light'>
                        <img src='images/<?php echo $row['categorie']; ?> ' class='img-fluid' width='262px' />

                    </div>

                    <div class='card-body'>
                        <div class='d-flex justify-content-between'>
                            <h4 class='card-title'><?php echo $row['titre']; ?> </h4>
                        </div>
                        <div class='d-flex justify-content-between'>
                            <h5 class='card-title'><?php echo $row['description']; ?> </h5>
                        </div>
                        <div class='d-flex justify-content-between'>
                            <span><?php echo $row['categorie']; ?> </span>
                            <span><?php echo $row['mass_horaire']; ?> Heures</span>
                            <span><?php echo $row['etat']; ?></span>
                            <span><?php echo $row['prenom_formateur'];
                                    echo $row['nom_formateur']; ?></span>
                            <span><?php echo "de " . $row['date_debut'] . " à " . $row['date_fin']; ?></span>
                        </div>
                    </div>
                    <input type="submit" name="submit" value="Inscrire">


            </div>
            </form>

        </div>
</body>
    <?php
        //check if already inscrire in a session in formation

        $sql2 = "SELECT COUNT(*) AS count
                    FROM inscriptions i
                    INNER JOIN sessions s ON i.id_session = s.id_session
                    WHERE i.id_appr = :id_appr
                    AND s.id_formation = :id_formation
                    AND (s.etat = 'en cours' OR s.etat = 'en cours dinscription')
                ";

        $stmt = $pdo->prepare($sql2);
        $stmt->bindParam(':id_appr', $id_appr);
        $stmt->bindParam(':id_formation', $row['id_formation']);
        $stmt->execute();
        $row2 = $stmt->fetch(PDO::FETCH_ASSOC);

        //check if there is 2 session in the same date
        $sql3 = "SELECT COUNT(*) AS count
                    FROM inscriptions i
                    INNER JOIN sessions s ON i.id_session = s.id_session
                    WHERE i.id_appr = :id_appr
                    AND (s.etat = 'en cours' OR s.etat = 'en cours dinscription')
                ";
        $stmt = $pdo->prepare($sql3);
        $stmt->bindParam(':id_appr', $id_appr);
        $stmt->execute();
        $row3 = $stmt->fetch(PDO::FETCH_ASSOC);

        //check if 2 session in the same date
        $sql5 = "SELECT COUNT(*) AS count
                    FROM inscriptions i
                    INNER JOIN sessions s ON i.id_session = s.id_session
                    WHERE i.id_appr = :id_appr
                    AND s.date_debut <= :date_fin
                    AND s.date_fin >= :date_debut
                    AND s.etat = 'en cours dinscription'
                ";
        $stmt = $pdo->prepare($sql5);
        $stmt->bindParam(':id_appr', $id_appr);
        $stmt->bindParam(':date_debut', $row['date_debut']);
        $stmt->bindParam(':date_fin', $row['date_fin']);
        $stmt->execute();
        $row5 = $stmt->fetch(PDO::FETCH_ASSOC);



        if (isset($_POST['submit'])) {
            if (empty($id_appr)) {
                echo "<div class='alert alert-danger' role='alert'>
                    Tu dois te connecter pour t'inscrire à une session.
                </div>";
            } elseif ($row2['count'] > 0) {
                echo "<div class='alert alert-danger' role='alert'>
                    vous avez deja inscrire sur cette session.
                </div>";
            } elseif ($row3['count'] >= 2) {
                echo "<div class='alert alert-danger' role='alert'>
                    vous avez deja inscrire sur 2 session au max.
                </div>";
            } 
            // elseif ($row5['count'] > 0) {
            //     echo "<div class='alert alert-danger' role='alert'>
            //         vous êtes déjà inscrit à une session qui se chevauche avec cette session.
            //     </div>";
            // } 
            else {
                $sql4 = "INSERT INTO inscriptions (id_appr, id_session) VALUES (:id_appr, :id_session)";
                $stmt = $pdo->prepare($sql4);
                $stmt->bindParam(':id_appr', $id_appr);
                $stmt->bindParam(':id_session', $row['id_session']);
                $stmt->execute();
                echo "<div class='alert alert-success' role='alert'>Votre inscription a été complétée avec succès.</div>";
            }
        }

    ?>

</html>