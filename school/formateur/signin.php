<?php
    require '../connection.php';
    session_start();
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="../apprenant/images/logo.png" type="image/x-icon">
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
    <a href="index.php" class="navbar-brand" ><img class="logo" src="../apprenant/images/logo.png" width="70px"></a>

    
    
  </div>
</nav>


</header>


<body>
    <h1 class="">Connexion</h1>
    <form action="" method="post">
        <h2>Se connecter</h2>
        <div class="inputs">

            <label for="">Email: </label>
            <input type="email" name="email" placeholder="Exemple@gmail.com">

        </div>
            <div class="inputs">

                <label for="">Mot de passe :</label>
                <input type="password" name="password" placeholder="***********">
            </div>

            <?php
                if (!empty($_SESSION['error'])) {
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                }
            ?>

            <button class="btn btn--radius btn--green" type="submit" name="submit">Connexion</button>



            <?php


            if (isset($_POST['submit'])) {

                $email = $_POST['email'];
                // $password = md5($_POST['password']);
                $password = $_POST['password'];
                

                //check if the input in email format
                if (empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['error'] = "<div  class='error alert alert-danger' role='alert'>Your Email is required, try again.</div>";
                } else if (empty($password)) {
                    $_SESSION['error'] = "<div class='error alert alert-danger' role='alert'>Mot de passe inccorect.</div>";
                } else {
                    $stmt = $pdo->prepare('SELECT * FROM `formateurs` WHERE email_formateur=:email AND password_formateur=:password');
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $password);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($result) === 1) {
                        $row = $result[0];
                        if ($row['email_formateur'] === $email && $row['password_formateur'] === $password) {

                            $_SESSION['id_formateur'] = $row['id_formateur'];
                            header("Location: index.php");
                        } else {
                            $_SESSION['error'] = "<div class='error alert alert-danger' role='alert'>the Email or the Password incorrect, try again.</div>";
                        }
                    } else {
                        $_SESSION['error'] = "<div class='error alert alert-danger' role='alert'>the Email and the Password incorrect, try again.</div>";
                    }
                }
            }




            ?>

<div class="have_one">
    <span class="have">Vous n'avez pas de compte? <a href="./SignUp.php" class="have">Crée un compte</a></span>
</div>
    </form>
</body>
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