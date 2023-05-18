<html lang="en">
<?php
    session_start();
    require "../connection.php";
    $id_appr = isset($_SESSION['id_appr']) ? $_SESSION['id_appr'] : "";
    require "./navbar.php";

?>









<body>
    <div class="container ll">
        <h1 class="grtitre">Mes inscriptions</h1>
        <div class="row gl">
            <div class="col-md-2">
                <form action="" method="POST" class="sidebar">
                    <input type="submit" value="historique" name="historique" class="btn btn-primary btn-block mb-3">
                    <input type="submit" value="en cours" name="cours" class="btn btn-primary btn-block mb-3">
                </form>
            </div>
            <div class="col-md-10">
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

                        
                            
                        echo   "<h3 class='card-title'>L'historique des formation</h3>";
                        echo " <div class='container-xxl pb-5 mt-5'>";
                        echo   "<div class='container'>";
                        echo   "<div class='tab-content'>";
                        echo   "<div id='tab-1' class='tab-pane fade show p-0 active'>";
                        echo   "<div class='row  g-4'>";
                        foreach ($results as $row) {
                            $card = "<div class='card'>
                                        <div class='bg-image hover-overlay ripple' data-mdb-ripple-color='light'>
                                            <img src='images/" . $row['image'] . "' class='img-fluid' width='262px'/>
                                        </div>
                                        <form method='post' action=''>
                                            <div class='card-body'>
                                                <div class='d-flex justify-content-between'>
                                                    <h4 class='card-title'>" . $row['titre'] . "</h4>
                                                    <span class='categorie'>" . $row['categorie'] . "</span>
                                                </div>
                                                <div class='d-flex justify-content-between'>
                                                    <p class='card-description'>" . $row['description'] . "</p>
                                                </div>
                                                <div class='d-flex'>
                                                    <span class='date'>de " . $row['date_debut'] . " à " . $row['date_fin'] . " </span>
                                                </div>
                                                <div class='d-flex justify-content-between'>
                                                    <span class='prof'>prof : " . $row['prenom_formateur'] . " " . $row['nom_formateur'] . " </span>
                                                </div>
                                                <div class='d-flex justify-content-between'>
                                                    <span class='prof'>etat : " . $row['etat'] . " </span>
                                                </div>    
                                                <div class='d-flex '>
                                                    <span class='prof'>Validation : </span>
                                                    <div class='validate-status'>";
                        
                                                        if ($row['validate'] === 0) {
                                                            $card .= "<div class='remove'><img src='./images/remove.png' class='icon'></div>";
                                                           
                                                        } elseif ($row['validate'] === 1) {
                                                            $card .= "<div class='valide'><img src='./images/accept.png' class='icon'></div>";
                                                            
                                                        } elseif ($row['validate'] === NULL) {
                                                            $card .= "<span class='status prof'>Pas en cours</span>";
                                                        }
                        
                            $card .= "</div></div>
                                    </div>
                                </form>
                            </div>";
                            echo $card;
                        }
                        
                                    
                                    
                                    
                                
                                    
                        
                        echo "</div></div></div></div></div>";
                    }


                if(isset($_POST['cours'])){
                        
                        $sql = "SELECT s.date_debut, s.date_fin, f.description, s.etat, f.titre, f.categorie, f.image, fo.prenom_formateur, fo.nom_formateur, i.validate, i.id_inscription
                            FROM inscriptions i
                            INNER JOIN sessions s ON i.id_session = s.id_session
                            INNER JOIN formations f ON s.id_formation = f.id_formation
                            INNER JOIN formateurs fo ON s.id_formateur = fo.id_formateur
                            WHERE i.id_appr = :id_appr
                            AND (s.etat = 'en cours' OR s.etat = 'en cours dinscription')

                        ";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id_appr', $id_appr);
                        $stmt->execute();
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                
                
                        
                        echo   "<h3 class='card-title'>Les formation En cours</h3>";
                        echo " <div class='container-xxl pb-5 mt-5'>";
                        echo   "<div class='container'>";
                        echo   "<div class='tab-content'>";
                        echo   "<div id='tab-1' class='tab-pane fade show p-0 active'>";
                        echo   "<div class='row  g-4'>";
                        foreach ($results as $row){           
                            $card = "<div class='card'>
                            <div class='bg-image hover-overlay ripple' data-mdb-ripple-color='light'>
                                <img src='images/".$row['image']."' class='img-fluid' width = '262px'/>
                                
                                </div>
                                <form method='post' action='delete_inscription.php'>
                                    <div class='card-body'>
                                        <div class='d-flex '>
                                            <h4 class='card-title'>".$row['titre']."</h4>
                                        </div>
                                        <div class='d-flex justify-content-between'>
                                           <p class='card-description'>".$row['description']."</p>
                                        </div>
                                        <div class='d-flex'>
                                            <span class='date'>de ".$row['date_debut']." à ".$row['date_fin']."  </span>
                                        </div>
                                        <div class='d-flex justify-content-between'>
                                            <span class='prof'>prof : ".$row['prenom_formateur']." ".$row['nom_formateur']." </span>
                                        </div>
                                        <div class='d-flex justify-content-between'>
                                        <span class='prof'>etat : ".$row['etat']." </span>";
                                        if($row['etat'] != 'en cours') {
                                            $card .= "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#exampleModal-" . $row['id_inscription'] . "'>Supprimer</button>";
                                            // $card .= " <button type='submit' class='btn btn-danger' name='delete' value='" . $row['id_inscription'] . "'>Supprimer</button>";
                                            // $card .= "<button type='button' class='btn btn-danger' data-toggle='modal' data-target='#confirm-delete-".$row['id_inscription']."'>Supprimer</button>";
                                                        
                                        }
                                        $card .= "  </div></div></form></div>";
                        echo $card; 
                    
                    
                        echo "<div class='modal fade' id='exampleModal-" . $row['id_inscription'] . "' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
        <div class='modal-header'>
            <h1 class='modal-title fs-5' id='exampleModalLabel'>Modal title</h1>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body'>
            Etes-vous sûr que vous voulez supprimer cette inscription sur cette fotmation        
        </div>
        <div class='modal-footer'>
            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
            <button type='button' class='btn btn-primary'><a class='text-decoration-none lnnk' href='delete_inscription.php?id_inscription=" . $row['id_inscription'] . "'>Supprimer</a></button>
        </div>
        </div>
    </div>
</div>";

                   
                    }
                    echo "</div></div></div></div></div>";
                    
                }

                
                ?>
           
        </div>
    </div>
    
    
    
</body>
<style>
      body{
        background-color: #F6F4E8;
    }
    
    .container.ll {
    max-width: 1149px;
    margin-top: 120px;
}
   
    
h1.grtitre {
    color: #3C654A; 
    font-weight: bold;
    font-size: 45px;
    margin-bottom: 54px; 
}
h3.card-title {
    font-weight: bold;
    font-size: 17px;
    margin-bottom: 3px;
    color: #51705c;
    margin: 25px;
}
.row.gl {
    display: flex;
    background-color: #eae7d6;
    box-shadow: 4px 4px 11px #cac9c0;
}
.col-md-2 {
    flex: 0 0 auto;
    width: 16.66666667%;
    /* border-right: 0.8px solid #3C654A; */
    box-shadow: 5px 0px 7px #cccccc;
}

.sidebar {
    display: flex;
    margin-top: 55px;
    flex-direction: column;
    align-items: flex-start;
}
 
button.btn.btn-primary,[type=submit]:not(:disabled) {
    height: 37px;
    background-color: #51705c;
    align-self: center;
    cursor: pointer;
    border-radius: 4px;
    width: 136px;
    border: none;

}
h2.titri {
        margin-left: 57px;
        padding: 32px 44px;
        font-weight: bold;
        color: #3c654a;
    }
    .card {
    display: flex;
    flex-direction: row;
    background-color: #eae7d6;
    border: none;
    box-shadow: 4px 4px 11px #cac9c0;
    color: #3c654a;
    height: 168px;
}
.card-body {
    width: 450px;
    height: auto;
    margin: 6px 2px 15px 4px;
}
    .bg-image.hover-overlay.ripple {
        align-self: center;
    }
    .img-fluid {
        max-width: 100%;
        height: auto;
        margin: 0px 16px 0px 16px;
        width: 211px;
    }
    h4.card-title {
    font-weight: bold;
    font-size: 17px;
    margin-bottom: 3px;
}
span.categorie {
    font-weight: bold;
    font-size: 13px;
}
    p.card-description {
    margin-bottom: 6px;
    max-width: 17%;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 12px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}
span.prof, span.date {
    font-weight: bold;
    font-size: 10px;
    align-self: center;
}
    
    a.text-decoration-none.lnnk {
        color: white;
    }
    img.icon {
    width: 26px;
    margin-left: 44px;
}
</style>