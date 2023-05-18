<?php

    session_start();
    require "../connection.php";
    require "./navbar.php";

    $id_formateur = isset($_SESSION['id_formateur']) ? $_SESSION['id_formateur'] : "";

  
    $sql = "SELECT * FROM formateurs WHERE id_formateur = $id_formateur";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC); 
    
    $query = "SELECT DISTINCT YEAR(date_debut) AS session_year FROM sessions ORDER BY session_year DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $years = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
                                    

?>

<body>

    <div class="hero">
        
        
        
        <div class="container ll">
            <div class="row justify-content-center "> 
                <div class="col-lg-12 text-center searching">
                    <h1 class="grtitre">Bienvenue  <?php echo $row['prenom_formateur'];echo $row['nom_formateur'] ;?> </h1>
                    
                    <form action="" method="post" >
                        
                        
                        <div class="col-md-12 flt">
                            
                            <?php
                                echo "<select name='date' class='form-control form-select'>";
                                echo "<option value=''>Année</option>";
                                foreach ($years as $year) {
                                    echo "<option value='" . $year['session_year'] . "'>" . $year['session_year'] . "</option>";
                                }
                                echo "</select>";
                            ?> 
                            <select name="categorie" class="form-control form-select ">
                                <option value="" <?php if (!isset($_POST['categorie']) || empty($_POST['categorie'])) echo 'selected'; ?>>Tout</option>
                                <?php

                                    $sql = "SELECT DISTINCT categorie FROM formations";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute();
                                    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
                                    // Loop through categories to create dropdown options
                                    foreach ($categories as $category) {
                                        $selected = ($_POST['categorie'] ?? '') === $category ? 'selected' : '';
                                        echo "<option value=\"$category\" $selected>$category</option>";
                                    }
                                    ?>
                                    
                                </select>                               
                                    <input type="submit" name="btn" value="filtrer"/>
                                <!-- <button class="btn btn--radius btn--green" name="btn" type="submit">Filtrer</button> -->
                            </div>
                            
                        </form> 
                    </div>
                </div>
                
            </div>
            
        </div>
        <?php
            if(empty($_POST['date']) && empty($_POST['categorie'])){
                $query =  "SELECT f.*, s.id_session, s.date_debut, s.date_fin, s.etat, s.places,
                    fo.id_formateur, fo.nom_formateur, fo.prenom_formateur
                    FROM formations f
                    INNER JOIN sessions s ON f.id_formation = s.id_formation
                    INNER JOIN formateurs fo ON s.id_formateur = fo.id_formateur
                    WHERE fo.id_formateur = :id_formateur
                ";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':id_formateur', $id_formateur);
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);    
                
           

                echo "<div class='container-xxl py-5 mt-5'>";
                
                echo "<div class='container'>";
                echo "<div class='tab-content'>";
                echo "<div id='tab-1' class='tab-pane d-flex fade show p-0 active'>";
                echo "<div class='row g-4'>";
        
                foreach ($rows as $row) {
                    $card =  "<div class='card'>
                    <div class='bg-image hover-overlay ripple' data-mdb-ripple-color='light'>
                        <img src='../apprenant/images/".$row['image']."' class='img-fluid' width = '262px'/>
                        
                        </div>
                        <form method='post' action=''>
                            <div class='card-body'>
                                <div class='d-flex justify-content-between'>
                                    <h4 class='card-title'>".$row['titre']."</h4>
                                    <span class='categorie'>".$row['categorie']."</span>
                                </div>
                                <div class='d-flex justify-content-between'>
                                   <p class='card-description'>".$row['description']."</p>
                                </div>
                                <div class='d-flex'>
                                    <span class='date'>de ".$row['date_debut']." à ".$row['date_fin']."  </span>
                                </div>
                                <div class='d-flex justify-content-between'>
                                    <span class='prof'>Prof : ".$row['prenom_formateur']." ".$row['nom_formateur']." </span>
                                    <span class='prof'>Etat :  ".$row['etat']." </span>
                                    <button><a class='text-decoration-none lnnk' href='details.php?id_session=" . $row['id_session'] . "'>Voir plus</a></button>

                                </div>
                            </div>
                        </form>
                    </div>";
                            echo $card;
                            
                }
                echo "</div></div></div></div></div>";
                       
            
            }

            if($_SERVER['REQUEST_METHOD'] === 'POST') { 
                $categorie = isset($_POST['categorie']) ? $_POST['categorie'] : '';
                $date = isset($_POST['date']) ? $_POST['date'] : '';
                $query = "SELECT f.*, s.id_session, s.date_debut, s.date_fin, s.etat, s.places,
                fo.id_formateur, fo.nom_formateur, fo.prenom_formateur
                FROM formations f
                INNER JOIN sessions s ON f.id_formation = s.id_formation
                INNER JOIN formateurs fo ON s.id_formateur = fo.id_formateur";

                $params = array();

                if (!empty($date) || !empty($categorie)) {
                    $query .= " WHERE fo.id_formateur = :id_formateur";
                    $params[':id_formateur'] =  $id_formateur;
                }

                if (!empty($date)) {
                    $query .= " AND YEAR(date_debut) = :date";
                    $params[':date'] =  $date;
                }

                if (!empty($categorie)) {
                    $query .= " AND categorie = :categorie";
                    $params[':categorie'] =  $categorie;
                }

                $stmt = $pdo->prepare($query);

                foreach ($params as $key => $value) {
                    $stmt->bindValue($key, $value);
                }

                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($rows)) {
                    echo "<div class='container-xxl py-5 mt-5'>";
                    echo "<div class='container'>";
                    echo "<div class='tab-content'>";
                    echo "<div id='tab-1' class='tab-pane d-flex fade show p-0 active'>";
                    echo "<div class='row g-4'>";
              
                    foreach ($rows as $row) {
                          $card =  "<div class='card'>
                          <div class='bg-image hover-overlay ripple' data-mdb-ripple-color='light'>
                              <img src='../apprenant/images/".$row['image']."' class='img-fluid' width = '262px'/>
                              
                              </div>
                              <form method='post' action=''>
                                  <div class='card-body'>
                                      <div class='d-flex justify-content-between'>
                                          <h4 class='card-title'>".$row['titre']."</h4>
                                          <span class='categorie'>".$row['categorie']."</span>
                                      </div>
                                      <div class='d-flex justify-content-between'>
                                         <p class='card-description'>".$row['description']."</p>
                                      </div>
                                      <div class='d-flex'>
                                          <span class='date'>de ".$row['date_debut']." à ".$row['date_fin']."  </span>
                                      </div>
                                      <div class='d-flex justify-content-between'>
                                          <span class='prof'>Prof : ".$row['prenom_formateur']." ".$row['nom_formateur']." </span>
                                          <span class='prof'>Etat :  ".$row['etat']." </span>
                                          <button><a class='text-decoration-none lnnk' href='details.php?id_session=" . $row['id_session'] . "'>Voir plus</a></button>
                                      </div>
                                  </div>
                              </form>
                          </div>";
                                  echo $card;
                                  
                    }
                  echo "</div></div></div></div></div>";
                               
                }else {
                    echo  "<div class='alert alert-danger' role='alert'>Aucune donnée disponible.</div>";
                }

            }

         

        
        ?>

        
    </body>
    <style>
 
    body {
        background-color: #F6F4E8;
    }

    .container.ll {
        max-width: 1519px;
    }

    .col-lg-12.text-center.searching {
    margin-top: 81px;
    height: 412px;
    background-image: url('https://images.unsplash.com/photo-1590402494682-cd3fb53b1f70?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80photo-1590402491619-cd3fb53b1f70?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80photo-1590402494674-cd3fb53b1f70?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80');
    background-size: 100%;
    
    background-position-y: -257px;
    background-repeat: no-repeat;
    background-attachment: fixed;
}

    h1.grtitre {
        color: white;
        position: relative;
        font-weight: bold;
        font-size: 45px;
        top: 102px;
    }
    .col-md-12 {
    margin: 119px;
    flex: 0 0 auto;
    display: flex;
    background-color: #f6f4e8;
    flex-direction: row;
    width: 581px;
    position: relative;
    height: 49px;
    bottom: -93px;
    left: 22%;
}

    input[type="text"] {
        height: 38px;
        align-self: center;
        background-color: #f6f4e8;
        border: none;
        width: 220px;
        padding: 0px 8px 0px 14px;
        color: #3c654a;
        border-right: solid #3c654a;
    }
    input[type="submit"] {
    background-color: #3c654a;
    color: white;
    border: none;
    width: 116px;
}

    select.form-control.form-select {
        background-color: #f6f4e8;
        height: 38px;
        align-self: center;
        border: none;
        padding: 0px 0px 0px 42px;
        color: #3c654a;
        width: 271px;
        border-radius: 0px;
    }

    input[type="date"] {
        background-color: #f6f4e8;
        padding: 0px 36px 0px 33px;
        height: 38px;
        color: #3c654a;
        align-self: center;
        border: none;
        border-right: solid #3c654a;
    }

    button.btn.btn--radius.btn--green {
        background-color: #3c654a;
        color: white;
        width: 131px;
        height: 38px;
        border-radius: 4px;
        margin: 17px;
        align-self: center;
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
        height: 262px;
    }

    .card-body {
        width: 970px;
        height: 218px;
        margin: 9px 27px 18px 23px;
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
    margin-bottom: 23px;
}

span.categorie {
    font-weight: bold;
    font-size: 19px;
}

p.card-description {
    margin-bottom: 59px;
    max-width: 75%;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

span.prof,
span.date {
    font-weight: bold;
    font-size: 15px;
}

button {
    color: #3c654a;
    background-color: #3c654a;
    border: none;
    width: 104px;
    border-radius: 5px;
    height: 39px;
}

a.text-decoration-none.lnnk {
    color: white;
}

</style>
