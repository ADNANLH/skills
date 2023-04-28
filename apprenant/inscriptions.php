<?php
session_start();
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
    <div class="container">
        <h1>Mes inscriptions</h1>
        <div class="row">
            <form action="" method="POST">
                <input type="submit" value="go" name="historique">

                <button type="button" class="btn btn-primary" name="historique"><img src="./images/history.png">Historique</button>
                <button type="button" class="btn btn-primary" name="cours"><img src="./images/history-book.png">En cours</button>
            </form>
        </div>
        <div class="row">
            <?php            
            if(isset($_POST['historique'])){

                $sql = "SELECT s.date_debut, s.date_fin, f.description, s.etat, f.titre, f.categorie, f.image, fo.prenom_formateur, fo.nom_formateur, i.validate
                FROM sessions s
                INNER JOIN formations f ON s.id_formation = f.id_formation
                INNER JOIN formateurs fo ON s.id_formateur = fo.id_formateur
                INNER JOIN inscriptions i ON s.id_session = i.id_session
                WHERE i.id_appr = :id_appr
                AND s.etat = 'cloturée'
                
                
                    ";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id_appr', $id_appr);
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                
                    
                }
                echo " <div class='container-xxl py-5 mt-5'>";
                echo   "<div class='container'>";
                echo   "<div class='tab-content'>";
                echo   "<div id='tab-1' class='tab-pane fade show p-0 active'>";
                echo   "<div class='row  g-4'>";
                foreach ($results as $row){
                    
                    $card =  "<div class='card'>
                    <div class='bg-image hover-overlay ripple' data-mdb-ripple-color='light'>
                    <img src='images/".$row['image']."' class='img-fluid' width = '262px'/>
                    
                    </div>
                    <form method='post' action=''>
                    <div class='card-body'>
                    <div class='d-flex justify-content-between'>
                    <h4 class='card-title'>".$row['titre']."</h4>
                    </div>
                    <div class='d-flex justify-content-between'>
                    <h5 class='card-title'>".$row['description']."</h5>
                    </div>
                    <div class='d-flex justify-content-between'>
                    <span>".$row['categorie']."</span>
                    <span>".$row['prenom_formateur']." ".$row['nom_formateur']." </span>
                    <span>de ".$row['date_debut']." à ".$row['date_fin']."</span>
                            </div>
                            </div>
                            </form>
                            </div>";
                            echo $card;
                            
                            
                            echo "</div>";
                            if($row['validate'] === 0){
                                echo "<div class='remove '><img src='./images/remove.png'></div>";
                                echo "non valide";
                            }else{
                                echo "<div class='valide '><img src='./images/accept.png'></div>";
                                echo "valide";
                            
                        }
                        echo "</div></div></div></div></div>";
                        
                    }


            ?>
        </div>
    </div>


</body>