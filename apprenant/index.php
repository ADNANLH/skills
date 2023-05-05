<?php

    session_start();
    require "../connection.php";
    $id_appr = isset($_SESSION['id_appr']) ? $_SESSION['id_appr'] : "";

?>

<html lang="en">

<?php

    require "./navbar.php";

?>



<body>

    <div class="hero">
        
       

        <div class="container ll">
            <div class="row justify-content-center "> 
                <div class="col-lg-12 text-center searching">
                    <h1 class="grtitre">Bienvenue à votre plateforme pour améliorer les skills</h1>
        
                    <form action="index.php" method="post" >
                        
                            
                        <div class="col-md-12 flt">
                            <input type="text" name="search" id="" placeholder="Formation...">
                            
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
                                <input type="date" name="date" id="" >
                                <button class="btn btn--radius btn--green" type="submit">Recherche</button>
                            </div>

                    </form>
                </div>
            </div>

        </div>
       
    </div>
    <h2 class="titri">Les formations :</h2>


  
<?php

if($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $search = isset($_POST['search']) ? $_POST['search'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $categorie = isset($_POST['categorie']) ? $_POST['categorie'] : '';
    
    
    // // Build the SQL query
    $sql = "SELECT f.*, s.id_session, s.date_debut, s.date_fin, s.etat, s.places,
    fo.id_formateur, fo.nom_formateur, fo.prenom_formateur
    FROM formations f
    INNER JOIN sessions s ON f.id_formation = s.id_formation
    INNER JOIN formateurs fo ON s.id_formateur = fo.id_formateur
    
    ";
    $params = array();

    if (!empty($search) || !empty($categorie) || !empty($date) ) {
        $sql .= " WHERE";
    }

    if (!empty($search)) {
        $sql .= " titre LIKE :search";
        $params[':search'] = '%' . $search . '%';
    }

    if (!empty($categorie)) {
        if (!empty($search)) {
            $sql .= " AND";
        }
        $sql .= " categorie = :categorie";
        $params[':categorie'] = $categorie;
    }
    if (!empty($date)) {
        if (!empty($search) || !empty($categorie)) {
            $sql .= " AND";
        }
        $sql .= " :date BETWEEN date_debut AND date_fin ";
        $params[':date'] = $date;
    }

    

    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);



            
    //         // Display the search results
    if (!empty($results)) {

        echo "<div class='container-xxl py-5 mt-5'>";
        
        echo "<div class='container'>";
        echo "<div class='tab-content'>";
        echo "<div id='tab-1' class='tab-pane d-flex fade show p-0 active'>";
        echo "<div class='row g-4'>";

        foreach ($results as $row) {
            $card =  "<div class='card'>
            <div class='bg-image hover-overlay ripple' data-mdb-ripple-color='light'>
                <img src='images/".$row['image']."' class='img-fluid' width = '262px'/>
                
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
                            <button><a class='text-decoration-none lnnk' href='details.php?id_formation=" . $row['id_formation'] . "'>Voir plus</a></button>
                        </div>
                    </div>
                </form>
            </div>";
                    echo $card;
                    
                }
                echo "</div></div></div></div></div>";
               
    }else {
                
        echo  "<p class='error'>Pas de formation.</p>";
    }

    
}
if(empty($_POST['search']) && empty($_POST['categorie'])){
       
            $query = "SELECT f.*, s.id_session, s.date_debut, s.date_fin, s.etat, s.places,
            fo.id_formateur, fo.nom_formateur, fo.prenom_formateur
            FROM formations f
            INNER JOIN sessions s ON f.id_formation = s.id_formation
            INNER JOIN formateurs fo ON s.id_formateur = fo.id_formateur;
            ";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
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
                                <button><a class='text-decoration-none lnnk' href='details.php?id_formation=" . $row['id_formation'] . "'>Voir plus</a></button>
                            </div>
                        </div>
                    </form>
                </div>";
                echo $card;
               
            }
            echo "</div></div></div></div></div>";
            
        }
    
    ?>

</body>
<style>
    body{
        background-color: #F6F4E8;
    }
    .container.ll {
        max-width: 1519px;
    }
    .col-lg-12.text-center.searching {
        margin-top: 81px;
        height: 412px;
        background-image: url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80 width');
        background-size: 100%;
        background-position-y: -257px;
        background-repeat: no-repeat;
    }
    h1.grtitre {
        color: white;
        position: relative;
        font-weight: bold;
        font-size: 45px;
        top: 102px;
    }
    .col-md-12 {
        margin: 118px;
        flex: 0 0 auto;
        display: flex;
        background-color: #f6f4e8;
        flex-direction: row;
        width: 874px;
        position: relative;
        height: 62px;
        bottom: -93px;
        left: 14%;
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
    select.form-control.form-select {
        background-color: #f6f4e8;
        height: 38px;
        align-self: center;
        border: none;
        border-right: solid #3c654a;
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
    span.prof, span.date {
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


</html>