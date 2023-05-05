<?php
    require('../connection.php');
    $id_inscription = $_GET['id_inscription'];
    echo $id_inscription;
    // require('./navbar.php');
    if(isset($id_inscription)){
        $sql="DELETE FROM inscriptions WHERE id_inscription = :id_inscription";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_inscription', $id_inscription);
        $stmt->execute();
        header("location: inscriptions.php");
    }


?>
