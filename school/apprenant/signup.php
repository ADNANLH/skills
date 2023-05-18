<?php
    require '../connection.php';
    session_start();
?>
<!DOCTYPE html>
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
        button.btbC {
            background-color: #f6f4e8;
            border: 2px solid #3c654a;
            width: 150px;
            height: 37px;
            border-radius: 7px;
        }


    </style>
</head>

<header>
    

<nav class="navbar navbar-expand-lg navbar-scroll fixed-top shadow-0">
  <div class="container">
    <a href="index.php" class="navbar-brand" ><img class="logo" src="./images/logo.png" width="70px"></a>

    
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <a class='text-decoration-none link' href="index.php">Accueil</a>
        </li>
        <li class="nav-item">
            <a class='text-decoration-none link' href="inscriptions.php">Mes inscriptions</a>
        </li>
        <li class="nav-item">
            <button class="btbC"><a class='text-decoration-none link' href="signin.php">Connecter</a></button>
        </li>
       

        
      </ul>
    </div>
  </div>
</nav>


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
            <div class="have_one">
                <span class="have">Avez-vous un compte ? <a href="./Signin.php" class="have text-decoration-none link">Connexion</a> </span>
            </div>
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = md5($_POST['password']) ?? '';


        $error = '';

        if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
            $_SESSION['error'] = "<div class='error alert alert-danger' role='alert'>Veuillez remplir tous les champs.</div>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "<div class='error alert alert-danger' role='alert'>Format email invalide</div>";
        } else {
            $select = "SELECT * FROM apprenants WHERE email_appr = :email";
            $stmt = $pdo->prepare($select);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $_SESSION['error'] = "<div class='error alert alert-danger' role='alert'>Cet Email existe déja</div>";
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
                
                // Check if the insertion was successful
                if (!$result) {
                    $_SESSION['error'] = "<div class='error alert alert-danger' role='alert'>Erreur lors de l'inscription. Veuillez réessayer plus tard.</div>";
                } else {
                    header("Location: signin.php");
                    exit();
                }


            }
        }
    }

    ?>

</body>

</html>
<style>
    body {
    background-color: #F6F4E8;
}

form {
    display: flex;
    flex-direction: column;
    background-color: #eae7d6;
    border: none;
    box-shadow: 4px 4px 11px #cac9c0;
    color: #3c654a;
    height: auto;
    max-width: 600px; /* Adjust as needed */
    margin: auto; /* Center horizontally */
    margin-top: 90px;
    padding: 20px;
}

h1 {
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
    margin-bottom: 20px;
    background-color: #3c654a;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 16px;
}


</style>