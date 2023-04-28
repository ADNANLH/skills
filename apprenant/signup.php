<?php
require '../connection.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">

    <title>Skills</title>
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">

    <link href="style.css" rel="stylesheet" media="all">
    <meta name="robots" content="noindex, follow">
</head>
<header>
    <div class="navbar">

        <div class="logo">
            <img src="./images/logo.png" alt="logo" class="imgLogo" width="269px">
        </div>

        <div class="menue">
            <a href="#">Accueil</a>
            <a href="#">Session</a>
            <a href="#">Connexion</a>
        </div>
    </div>

</header>

<body>
    <div class="card-body">
        <h1 class="title">Crée un compte</h1>
        <form method="POST">
            <h2>Crée votre compte</h2>
            <div class="inputs">

                <label for="">Nom</label>
                <input type="text" name="nom" placeholder="Votre Nom">

            </div>
            <div class="inputs">

                <label for="">Prénom</label>
                <input type="text" name="prenom" placeholder="Votre prénom">

            </div>
            <div class="inputs">

                <label for="">Email</label>
                <input type="email" name="email" placeholder="Votre Email">

            </div>

            <div class="inputs">

                <label for="">Mot de passe</label>
                <input type="password" name="password" placeholder="Votre Mot de passe">

            </div>





            <?php
            if (!empty($_SESSION['error'])) {
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            }
            ?>



            <div class="p-t-30">
                <button class="btn btn--radius btn--green" type="submit">Inscrire</button>
            </div>
        </form>
        <div class="have_one">
            <span class="have">Avez-vous un compte ? <a href="./Signin.php" class="have">Connexion</a> </span>
        </div>
    </div>

    <?php:
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom  = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $password = md5($_POST['password']);


        $error = '';

        if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
            $_SESSION['error'] = "<div class='error'> Entrer tout les inputes.</div>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "<div class='error'>Format email invalide</div>";
        } else {
            $select = "SELECT * FROM apprenants WHERE email_appr = :email";
            $stmt = $pdo->prepare($select);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $_SESSION['error'] = "<div class='error'>Cette Email déja existe</div>";
            } else {
                // Insert data into the database
                $insert = "INSERT INTO apprenants (nom_appr, prenom_appr, email_appr, password_appr ) 
            VALUES (:nom, :prenom, :email, :password )";
                $stmt = $pdo->prepare($insert);
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':prenom', $prenom);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);
                $stmt->execute();

                header("location: signin.php");

                // Check if the insertion was successful

            }
        }
    }

    ?>

</body>

</html>