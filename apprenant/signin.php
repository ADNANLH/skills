<?php
    require '../connection.php';
    session_start();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">

    <title>Au Register Forms by Colorlib</title>
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>


    <link href="style.css" rel="stylesheet" media="all">
    <meta name="robots" content="noindex, follow">
    <!-- <style>
        /* Add custom styles here */
        body {
        background-color: #f1f1f1;
        font-family: Arial, sans-serif;
        font-size: 16px;
        }
        .navbar {
        background-color: #333;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 60px;
        padding: 0 20px;
        }
        .logo img {
        height: 50px;
        }
        .menue a {
        color: #fff;
        margin-left: 20px;
        text-decoration: none;
        font-size: 18px;
        }
        .menue a:hover {
        color: #ff0;
        }
        h1 {
        text-align: center;
        margin-top: 40px;
        }
        form {
        max-width: 500px;
        margin: 0 auto;
        background-color: #fff;
        padding: 30px;
        box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
        border-radius: 5px;
        }
        h2 {
        font-size: 28px;
        margin-bottom: 30px;
        }
        .inputs {
        margin-bottom: 20px;
        }
        label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        }
        input[type="email"],
        input[type="password"] {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 16px;
        margin-top: 5px;
        }
        .btn {
        display: block;
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: none;
        color: #fff;
        font-size: 18px;
        font-weight: bold;
        margin-top: 20px;
        }
        .btn-green {
        background-color: #4caf50;
        }
        .btn-green:hover {
        background-color: #3e8e41;
        }
        .error {
        color: #d50000;
        margin-bottom: 10px;
        }
        .have-one {
        text-align: center;
        margin-top: 20px;
        font-size: 18px;
        }
        .have-one a {
        color: #4caf50;
        text-decoration: none;
        font-weight: bold;
        }
        .have-one a:hover {
        color: #3e8e41;
        }
  </style> -->
</head>
<header>
    <div class="navbar">

        <div class="logo">
            <img src="./images/logo.png" alt="logo" class="imgLogo" width="269px">
        </div>

        <div class="menue">
            <a href="#">Accueil</a>
            <a href="#">Session</a>
            <a href="#">Crée un compte</a>
        </div>
    </div>

</header>

<body>
    <h1>Connexion</h1>
    <form action="" method="post">
        <h2>Se connecter</h2>
        <div class="inputs">

            <label for="">Email</label>
            <input type="email" name="email" placeholder="Exemple@gmail.com">

        </div>
            <div class="inputs">

                <label for="">Mot de passe</label>
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
                $password = md5($_POST['password']);
                // $password = $_POST['password'];
                

                //check if the input in email format
                if (empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['error'] = "<div  class='error alert alert-danger' role='alert'>*Your Email is required, try again.</div>";
                } else if (empty($password)) {
                    $_SESSION['error'] = "<div class='error alert alert-danger' role='alert'>*Password is required.</div>";
                } else {
                    $stmt = $pdo->prepare('SELECT * FROM `apprenants` WHERE email_appr=:email AND password_appr=:password');
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $password);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($result) === 1) {
                        $row = $result[0];
                        if ($row['email_appr'] === $email && $row['password_appr'] === $password) {

                            $_SESSION['id_appr'] = $row['id_appr'];
                            header("Location: index.php");
                        } else {
                            $_SESSION['error'] = "<div class='error alert alert-danger' role='alert'>*the Email or the Password incorrect, try again.</div>";
                        }
                    } else {
                        $_SESSION['error'] = "<div class='error alert alert-danger' role='alert'>*the Email and the Password incorrect, try again.</div>";
                    }
                }
            }




            ?>

    </form>
    <div class="have_one">
        <span class="have">Vous n'avez pas de compte? <a href="./SignUp.php" class="have">Crée un compte</a></span>
    </div>
</body>