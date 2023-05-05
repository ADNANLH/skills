
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
    <a href="index.php" class="navbar-brand" ><img class="logo" src="./images/logo.png" width="70px"></a>

    
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <a class='text-decoration-none link' href="index.php">Accueil</a>
        </li>
        <li class="nav-item">
            <?php
                    if (!empty($id_appr)) {
                        echo "<a class='text-decoration-none link' href='inscriptions.php'>Mes inscriptions</a>";
                    } 
            ?>
        </li>
        <?php
                if (empty($id_appr)) {
                    echo "<li class='nav-item'><button type='button' class='btn btn-dark ms-3'><a class='text-decoration-none link' href='signin.php'>Connexion</a></button></li>";
                } else {
                    echo "<li class='nav-item'><a href = 'profile.php'><img src='./images/user.png' alt='profile' ></a></li>";
                }
        ?>

        
      </ul>
    </div>
  </div>
</nav>


</header>
