<?php
    $host = 'localhost';
    $dbname = 'school';
    $username = 'root';
    $password = '';

   
    
    $dsn = "mysql:host=$host;dbname=$dbname";
    $pdo = new PDO($dsn, $username, $password);

    function check_db_connection($host, $dbname, $username, $password) {
        try {
            $dsn = "mysql:host=$host;dbname=$dbname";
            $pdo = new PDO($dsn, $username, $password);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    //     if (check_db_connection($host, $dbname, $username, $password)) {
    //         echo "Connection successful";
    //     } else {
    //         echo "Connection failed";
    //     }


 ?>
