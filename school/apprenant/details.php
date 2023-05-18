<?php
session_start();
$id_session = $_GET['id_session'];
require "../connection.php";
$id_appr = isset($_SESSION['id_appr']) ? $_SESSION['id_appr'] : "";



?>

<html lang="en">




<?php

    require "./navbar.php";

?>



<body>

    <?php
        $sql = "SELECT f.*, s.id_session, s.date_debut, s.date_fin, s.etat, s.places,
            fo.id_formateur, fo.nom_formateur, fo.prenom_formateur
            FROM formations f
            INNER JOIN sessions s ON f.id_formation = s.id_formation
            INNER JOIN formateurs fo ON s.id_formateur = fo.id_formateur
            WHERE s.id_session = :id_session
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_session', $id_session);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    ?>

    <div class="hero">



        <div class="container ll">
            <h1 class="grtitre"><?php echo $row['titre']; ?></h1>
            <h3 class="mntitre"><?php echo $row['categorie']; ?> : </h3>
            <div class="card">
    <form method="POST" action="">
        <div class='d-flex align-items-end'>
            <div class='bg-image hover-overlay ripple' data-mdb-ripple-color='light'>
                <img src='images/<?php echo $row['image']; ?> ' class='img-fluid' />
            </div>
            <div class='card-body'>
                <div class='d-flex justify-content-between'>
                    <h4 class='card-title'><?php echo $row['titre']; ?></h4>
                    <span class="categorie"><?php echo $row['categorie']; ?></span>
                </div>
                <p class='description'><?php echo $row['description']; ?></p>
                <div class='d-flex justify-content-around align-items-end'>
                    <span class="date"><?php echo $row['mass_horaire']; ?> Heures</span>
                    <span class="date"><?php echo "de " . $row['date_debut'] . " à " . $row['date_fin']; ?></span>
 
                </div>
                <div class='d-flex justify-content-around align-items-end'>
                    <span class="prof"><?php echo $row['prenom_formateur'] . ' ' . $row['nom_formateur']; ?></span>
                    <span class="etat"><?php echo $row['etat']; ?></span>
                </div>
                <input type='submit' name='submit' value='Inscrire'>
                     
            </div>
        </div>
    </form>
</div>


        </div>
</body>
    <?php
        //check if already inscrire in a session in the same formation

        $sql2 = "SELECT COUNT(*) AS count
            FROM inscriptions i
            INNER JOIN sessions s ON i.id_session = s.id_session
            WHERE i.id_appr = :id_appr
            AND s.id_formation = :id_formation
        ";

        $stmt = $pdo->prepare($sql2);
        $stmt->bindParam(':id_appr', $id_appr);
        $stmt->bindParam(':id_formation', $row['id_formation']);
        $stmt->execute();
        $row2 = $stmt->fetch(PDO::FETCH_ASSOC);


        //check if there is 2 session 
        $sql3 = "SELECT COUNT(*) AS count
                    FROM inscriptions i
                    INNER JOIN sessions s ON i.id_session = s.id_session
                    WHERE i.id_appr = :id_appr
                    AND (s.etat = 'en cours' OR s.etat = 'en cours dinscription')
                    AND YEAR(s.date_debut) = YEAR(CURRENT_DATE()) 
                ";
        $stmt = $pdo->prepare($sql3);
        $stmt->bindParam(':id_appr', $id_appr);
        $stmt->execute();
        $row3 = $stmt->fetch(PDO::FETCH_ASSOC);

        //check if 2 session in the same date
        $sql4 = "SELECT COUNT(*) AS count
                    FROM inscriptions i
                    INNER JOIN sessions s ON i.id_session = s.id_session
                    WHERE i.id_appr = :id_appr
                    AND s.date_debut < :date_fin
                    AND s.date_fin > :date_debut
                    AND (s.etat = 'en cours dinscription' OR s.etat = 'en cours' )
                    AND YEAR(s.date_debut) = YEAR(CURRENT_DATE()) 
                ";
        $stmt = $pdo->prepare($sql4);
        $stmt->bindParam(':id_appr', $id_appr);
        $stmt->bindParam(':date_debut', $row['date_debut']);
        $stmt->bindParam(':date_fin', $row['date_fin']);
        $stmt->execute();
        $row4 = $stmt->fetch(PDO::FETCH_ASSOC);

        //check the number of places
        $sqlSS = "SELECT COUNT(*) AS count FROM inscriptions WHERE id_session = :id_session";
        $stmt = $pdo->prepare($sqlSS);
        $stmt->bindParam(':id_session', $row['id_session']);
        $stmt->execute();
        $rowSS = $stmt->fetch(PDO::FETCH_ASSOC);

        $sqlSSS = "SELECT * FROM sessions WHERE id_session = :id_session";
        $stmt = $pdo->prepare($sqlSSS);
        $stmt->bindParam(':id_session', $row['id_session']);
        $stmt->execute();
        $rowSSS = $stmt->fetch(PDO::FETCH_ASSOC);



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
            elseif ($row4['count'] > 0) {
                echo "<div class='alert alert-danger' role='alert'>
                    vous êtes déjà inscrit à une session qui se chevauche avec cette session.
                </div>";
            }elseif($rowSS['count'] >= $rowSSS['places']){
                echo "<div class='alert alert-danger' role='alert'>
                Il n'y a plus de place disponible pour cette session.
              </div>";
        
            }elseif($row['etat'] != 'en cours dinscription'){
                echo "<div class='alert alert-danger' role='alert'>
                Désoler cette formation est ".$row['etat'].".
              </div>";
            }
            else {
                $sql5 = "INSERT INTO inscriptions (id_appr, id_session) VALUES (:id_appr, :id_session)";
                $stmt = $pdo->prepare($sql5);
                $stmt->bindParam(':id_appr', $id_appr);
                $stmt->bindParam(':id_session', $row['id_session']);
                $stmt->execute();
                echo "<div class='alert alert-success' role='alert'>Votre inscription a été complétée avec succès.</div>";
            }
        }

    ?>

</html>
<style>
    body{
        background-color: #F6F4E8;
    }
    .container.ll {
        max-width: 1261px;
        margin-top: 90px;
    
    }
    h1.grtitre {
        margin-left: 4px;
        padding: 22px 0px;
        font-weight: bold;
        color: #3c654a;
    }
    h3.mntitre{
        margin-left: 4px;
        padding: 22px 0px;
        font-weight: bold;
        color: #3c654a;
        
    }
    
    .card {
  display: flex;
  flex-direction: column;
  background-color: #eae7d6;
  border: none;
  box-shadow: 4px 4px 11px #cac9c0;
  color: #3c654a;
  height: auto;
  margin-bottom: 30px;
  border-radius: 10px;
  overflow: hidden;
}

.bg-image.hover-overlay.ripple {
  height: auto;
  width: 28%;
  align-self: center;
}

.img-fluid {
  max-width: 100%;
  height: auto;
  border-radius: 10px 10px 0 0;
}

.card-body {
    padding: 20px;
    width: 72%;
}

h4.card-title {
  font-weight: bold;
  margin-bottom: 10px;
}

span.categorie {
  font-weight: bold;
  font-size: 16px;
  margin-bottom: 10px;
}

p.description {
  margin-bottom: 20px;
  max-width: 100%;
}

.d-flex.justify-content-around.align-items-end {
  margin-top: auto;
}

span.prof,
span.date,
span.etat {
  font-weight: bold;
  font-size: 14px;
  margin-right: 10px;
}

input[type="submit"] {
    margin-top: 20px;
    color: white;
    background-color: #3c654a;
    border: none;
    width: 20%;
    border-radius: 5px;
    height: 40px;
}


</style>