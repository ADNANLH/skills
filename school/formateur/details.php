<?php
session_start();
// $id_session = isset($_GET['id_session']) ? $_GET['id_session'] : "";

$id_session =  $_GET['id_session'];
// echo $id_session ;

require "../connection.php";




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
            WHERE s.id_session = :id_session";
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
                <img src='../apprenant/images/<?php echo $row['image']; ?> ' class='img-fluid' />
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
                <div class='d-flex justify-content-around align-items-end'>
                   
                    <?php 
                         if($row['etat']=="cloturée"){
                            // echo "<button type='button' name='validation' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#exampleModal-" . $row['id_session'] . "'>Validation</button>";
                            echo "<button  class='btn btn-primary'><a class='text-decoration-none lnnk' href='valid.php?id_session=" . $row['id_session'] . "'>Validation</a></button>";
                            
                        } 
                        
                        ?> 
                        <button type='button' name='list' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#examplelist-<?php echo $row['id_session']; ?>'>Liste des apprenants</button>
                </div>
                
            </div>
        </div>
    </form>
</div>


        </div>
</body>
    <?php
        $query = "SELECT a.*, s.id_session, s.date_debut, s.date_fin, s.etat, i.*
        FROM inscriptions i
        INNER JOIN sessions s ON i.id_session = s.id_session
        INNER JOIN apprenants a ON i.id_appr = a.id_appr
        WHERE s.id_session = :id_session";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_session', $id_session);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $validate = '';

if (count($rows) > 0) {
  $list = "<div class='container'>
              <div class='modal fade' id='examplelist-{$id_session}' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                  <div class='modal-dialog'>
                      <div class='modal-content'>
                          <div class='modal-header'>
                              <h1 class='modal-title fs-5' id='exampleModalLabel'>List des apprenants</h1>
                              <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                          </div>
                          <div class='modal-body'>
                              <table class='table table-striped'>
                                  <thead>
                                      <tr>
                                          <th>Id</th>
                                          <th>Nom</th>
                                          <th>Prénom</th>
                                          <th>Date-debut</th>
                                          <th>Date_fin</th>
                                          <th>Etat</th>
                                      </tr>
                                  </thead>
                                  <tbody>";
  foreach ($rows as $row) {
      if ($row['validate'] === 1) {
          $validate = 'Validée';
      } elseif ($row['validate'] === 0) {
          $validate = 'Non valider';
      } elseif ($row['validate'] === NULL) {
          $validate = 'Pas en cours';
      }
      $list .= "<tr>
                      <td>{$row['id_appr']}</td>
                      <td>{$row['nom_appr']}</td>
                      <td>{$row['prenom_appr']}</td>
                      <td>{$row['date_debut']}</td>
                      <td>{$row['date_fin']}</td>
                      <td>{$validate}</td>
                  </tr>";
  }

  $list .= "</tbody>
              </table>
          </div>
          <div class='modal-footer'>
              <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
          </div>
      </div>
  </div>
</div>
</div>";

  echo $list;
} else {
    
  echo "<div class='container'>
          <div class='alert alert-danger' role='alert'>
            Aucune donnée disponible pour la session spécifiée.
          </div>
      </div>";
}

    ?>


</html>
<style>
    body {
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

    h3.mntitre {
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

    [type=button]:not(:disabled),
    [type=reset]:not(:disabled),
    [type=submit]:not(:disabled),
    button:not(:disabled) {
        cursor: pointer;
        width: 99px;
    }
    button.btn.btn-primary {
        background-color: #3f664c;
        width: auto;
        margin-top: 14px;
        border: none;
    }

    .img-fluid {
    max-width: 82%;
    height: auto;
    margin-left: 18px;
    border-radius: 6px 6px 6px 6px;
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
    
    /* Added styles for modals */
    .modal-title {
        font-size: 1.5rem;
        font-weight: bold;
    }
    
    .modal-body table {
        width: 100%;
    }
    .modal-content {
      
        width: 119%;
       
    }
    
    .modal-body th,
    .modal-body td {
        padding: 8px;
    }
    
    a.text-decoration-none.lnnk {
    color: white;
}
    
    .modal-footer .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
</style>
