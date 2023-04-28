<?php

session_start();
require "../connection.php";
$id_appr = $_SESSION['id_appr'];


?>
<!DOCTYPE html>
<html lang="en">




<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="./images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="./style.css">
    <title>skills</title>
</head>

<header>

    <nav class="site-nav">
        <div class="site-navigation d-flex justify-content-between fixed-top py-3 px-5 align-items-center" style="background-color:#198754;">
            <div class="site-navigation  d-flex justify-content-end fixed-top py-3 px-5 align-items-center navi">
                <div class=" d-flex  fixed-top  py-3 px-5 align-items-center ">

                    <a href="index.php"><img class="logo" src="./images/logo.png" width="70px"></a>

                    <li class="js-clone-nav me-4 align-items-center d-flex text-center site-menu list-unstyled"><a class='text-decoration-none' href="index.php">Accueil</a></li>
                    <li class="js-clone-nav me-4 align-items-center d-flex text-center site-menu list-unstyled"><a class='text-decoration-none' href="reservation.php">Sessions</a></li>
                    <a href='profile.php'><img src='./images/user.png' alt='profile'></a>
                    <li class="js-clone-nav me-4 align-items-center d-flex text-center site-menu list-unstyled"><a class='text-decoration-none' href="logout.php">log out</a></li>

                </div>

                </li>
                </ul>
            </div>
    </nav>
</header>

<body>
    <?php

        $sql = "SELECT * FROM apprenants WHERE id_appr= :id_appr";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_appr', $id_appr);
        $stmt->execute();

        // Fetch the user's information from the result set
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            // Handle any errors
            echo "Error fetching user information: " . $stmt->errorInfo();
        }

    ?>

    <div class="card-body">
        <h1 class="title">Crée un compte</h1>
        <form action="" method="post">
            <div class="inputs">

            <label for="">Nom :</label>
            <input type="text" name="nom" placeholder="<?php echo  $row['nom_appr']  ?>" >


            </div>
            <div class="inputs">

                <label for="">Prénom :</label>
                <input type="text" name="prenom" placeholder="<?php echo $row['prenom_appr']; ?>" >


            </div>
            <div class="inputs">

                <label for="">Email :</label>
                <input type="email" name="email" placeholder="<?php echo  $row['email_appr']  ?>" >

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


    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom  = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $password = md5($_POST['password']);
            $newpass = md5($_POST['new']);
            $confirmpass = md5($_POST['confirmer']);

            if($password != $row['password_appr'] || $password == ''){
                $_SESSION['error'] = "<div class='error'> Le mot de passe incorect.</div>";

            }elseif($newpass != $confirmpass || empty($newpass) || empty($confirmpass)) {
                $_SESSION['error'] = "<div class='error'> Confirmer le nouveau mot de passe.</div>";

            }else{
                $query = "UPDATE apprenants SET nom_appr = '" . ($nom != '' ? $nom : $row['nom_appr']) . "', prenom_appr = '" . ($prenom != '' ? $prenom : $row['prenom_appr']) . "', email_appr = '" . ($email != '' ? $email : $row['email_appr']) . "', password_appr = '" . ($newpass != '' ? $newpass : $row['password_appr']) . "' WHERE id_appr = " . $row['id_appr'];
                $stmt = $pdo->prepare($query);
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    // Update successful, display alert message
                    echo "<script>alert('Update successful!');</script>";
                } else {
                    // Update failed, display error message
                    echo "<script>alert('Update failed!');</script>";
                }

            }


        }
    
    
    ?>






</body>
<style>
    /* Style for the navbar */
.site-nav {
  width: 100%;
  height: 60px;
  background-color: #198754;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 999;
}

.site-navigation {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
}

.logo {
  margin-right: 20px;
}

.site-menu {
  margin-right: 20px;
  font-size: 18px;
}

.site-menu a {
  color: #fff;
  text-decoration: none;
  padding: 10px;
}

.site-menu a:hover {
  color: #fff;
  text-decoration: none;
  background-color: #155724;
}

.site-menu.active a {
  background-color: #155724;
}

    /* Center the title */
    .title {
        text-align: center;
        margin-top: 50px;
        margin-bottom: 50px;
    }

    /* Style the input fields */
    .inputs {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 10px;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        border: none;
        border-bottom: 1px solid #ccc;
        font-size: 16px;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
        outline: none;
        border-color: #198754;
    }

    /* Style the submit button */
    .btn {
        display: block;
        width: 100%;
        padding: 10px;
        background-color: #198754;
        color: #fff;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    .btn:hover {
        background-color: #146b40;
    }
</style>
