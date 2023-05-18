<?php

session_start();
require "../connection.php";
$id_formateur = isset($_SESSION['id_formateur']) ? $_SESSION['id_formateur'] : "";


?>

<html lang="en">


<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="./images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <title>skills</title>
    <style>
        nav.navbar.navbar-expand-lg {
            background: #F6F4E8;
            height : 80px;
            box-shadow: 3px 3px 21px #8a8888;
        }
        img.logo {
            width: 143px;
        }
        a.link {
            color: #3C654A;
        }
        button.btn.btn-dark.ms-3 {
            background-color: #F6F4E8;
            border-color: #3C654A;
        }
        .navbar-expand-lg .navbar-nav {
            flex-direction: row;
            align-items: center;
        }
        li.nav-item {
            padding: 24px;
        }
        


    </style>
</head>

<header>
    

<nav class="navbar navbar-expand-lg navbar-scroll fixed-top shadow-0">
  <div class="container">
    <a href="index.php" class="navbar-brand" ><img class="logo" src="../apprenant/images/logo.png" width="70px"></a>

    
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <a class='text-decoration-none link' href="index.php">Accueil</a>
        </li>
      
        <li class="nav-item">
            <a class='text-decoration-none link' href="logout.php">Déconnecter</a>
        </li>
       

        
      </ul>
    </div>
  </div>
</nav>


</header>


<body>
    <?php

        $sql = "SELECT * FROM formateurs WHERE id_formateur= :id_formateur";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_formateur', $id_formateur);
        $stmt->execute();

        // Fetch the user's information from the result set
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            // Handle any errors
            echo "Error fetching user information: " . $stmt->errorInfo();
        }

    ?>

<div class="card">
    <div class="card-body">
        <h1 class="grtitre">Mon profile</h1>
        <form action="" method="post">
            <div class="inputs">
                <label for="">Nom :</label>
                <input type="text" name="nom" placeholder="<?php echo  $row['nom_formateur']  ?>" >
            </div>
            <div class="inputs">
                <label for="">Prénom :</label>
                <input type="text" name="prenom" placeholder="<?php echo $row['prenom_formateur']; ?>" >
            </div>
            <div class="inputs">
                <label for="">Email :</label>
                <input type="email" name="email" placeholder="<?php echo  $row['email_formateur']  ?>" >
            </div>
            <div class="inputs">
                <label for="">Mot de passe:</label>
                <input type="password" name="password" placeholder="*********">
            </div>
            <div class="inputs">
                <label for="">Nouveau Mot de passe:</label>
                <input type="password" name="new" placeholder="*********">
            </div>
            <div class="inputs">
                <label for="">Confirmer Mot de passe :</label>
                <input type="password" name="confirmer" placeholder="*********">
            </div>
            <?php
                if (!empty($_SESSION['error'])) {
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                }
            ?>
            <button class="btn btn--radius btn--green" type="submit" name="submit">Modifier</button>
        </form>
    </div>
</div>



    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom  = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $password = md5($_POST['password']);
            $newpass = md5($_POST['new']);
            $confirmpass = md5($_POST['confirmer']);

            if($password != $row['password_formateur'] || $password == ''){
                $_SESSION['error'] = "<div class='error alert alert-danger' role='alert'> Le mot de passe incorect.</div>";

            }elseif($newpass != $confirmpass || empty($newpass) || empty($confirmpass)) {
                $_SESSION['error'] = "<div class='error alert alert-danger' role='alert'> Confirmer le nouveau mot de passe.</div>";

            }else{
                $query = "UPDATE formateurs SET nom_formateur = '" . ($nom != '' ? $nom : $row['nom_formateur']) . "', prenom_formateur = '" . ($prenom != '' ? $prenom : $row['prenom_formateur']) . "', email_formateur = '" . ($email != '' ? $email : $row['email_formateur']) . "', password_formateur = '" . ($newpass != '' ? $newpass : $row['password_formateur']) . "' WHERE id_formateur = " . $row['id_formateur'];
                $stmt = $pdo->prepare($query);
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    // Update successful, display alert message
                    $_SESSION['error'] = "<div class='error alert alert-success' role='alert'>Succeed de modification.</div>";

                } else {
                    // Update failed, display error message
                    $_SESSION['error'] = "<div class='error alert alert-danger' role='alert'>La modification a échoué.</div>";

                }

            }


        }
    
    
    ?>






</body>
<style>


.site-menu.active a {
  background-color: #155724;
}

body {
    background-color: #F6F4E8;
}

.card {
    display: flex;
    flex-direction: column;
    background-color: #eae7d6;
    border: none;
    box-shadow: 4px 4px 11px #cac9c0;
    color: #3c654a;
    height: auto;
    max-width: 600px; /* Adjust as needed */
    margin: auto; /* Center horizontally */
    margin-top: 119px;
    padding: 20px;
}

.grtitre {
    margin-left: 4px;
    padding: 22px 0px;
    font-weight: bold;
    color: #3c654a;
}

.inputs {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
}

label {
    font-weight: bold;
    margin-bottom: 5px;
}

input[type="text"],
input[type="email"],
input[type="password"] {
    padding: 10px;
    border: none;
    border-radius: 5px;
    background-color: #F6F4E8;
    color: #3c654a;
    font-size: 16px;
    margin-top: 5px;
}

button.btn {
    margin-top: 20px;
    background-color: #3c654a;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 16px;
}



</style>
