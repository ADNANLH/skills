<?php
session_start();

$id_session =  $_GET['id_session'];

require "../connection.php";
require "./navbar.php";


$query = "SELECT a.*, s.id_session, s.date_debut, s.date_fin, s.etat, i.*
FROM inscriptions i
INNER JOIN sessions s ON i.id_session = s.id_session
INNER JOIN apprenants a ON i.id_appr = a.id_appr
WHERE s.id_session = :id_session";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_session', $id_session);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


$table= "<div class='container'>
    
            
            <div class='modal-body'>
                <table class='table table-striped'>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Date-debut</th>
                            <th>Date_fin</th>
                            <th>Validation</th>
                        </tr>
                    </thead>
                    <tbody>";
                    foreach ($rows as $row) {
                        if ($row['validate'] === 1) {
                            $validate = 'Validée';
                        } elseif ($row['validate'] === 0) {
                            $validate = 'Non valider';
                        } elseif ($row['validate'] === NULL) {
                            $validate = 'Pas en cours';
                        }
                        $table .= "<tr>
                                        <td>{$row['id_appr']}</td>
                                        <td>{$row['nom_appr']}</td>
                                        <td>{$row['prenom_appr']}</td>
                                        <td>{$row['date_debut']}</td>
                                        <td>{$row['date_fin']}</td>
                                        <td>";
                        if ($row['validate'] === null) {
                            $table .= "<form method='POST' action=''>
                                            <input type='hidden' name='id_inscription' value='{$row['id_inscription']}' />
                                            <input type='submit' class='btn' name='valider-{$row['id_inscription']}' value='Valider' />
                                            <input type='submit' class='btn nv' name='Nvalider-{$row['id_inscription']}' value='Non Valider' />
                                        </form>";
                        } else {
                            $table .= $validate;
                        }
                        $table .= "</td>
                                    </tr>";
                    }
                    
                    $table .= "</tbody>
                                </table>
                                </div>
                                
                               
                            </div>
                        </div>";
                                                            
                    echo $table;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        foreach ($rows as $row) {
            $id_inscription = $row['id_inscription'];                        
            if (isset($_POST['valider-' . $id_inscription])) {
                // echo $id_inscription;
                $sql = "UPDATE inscriptions SET validate = 1 WHERE id_inscription = :id_inscription";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id_inscription', $id_inscription);
                $stmt->execute();
            }  elseif (isset($_POST['Nvalider-' . $id_inscription])) {
                // echo $id_inscription;
                $sql = "UPDATE inscriptions SET validate = 0 WHERE id_inscription = :id_inscription";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id_inscription', $id_inscription);
                $stmt->execute();
            }                           
        }
    }





?>
<style>
        body {
            background-color: #F6F4E8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead tr th {
    background-color: #3c654a;
    color: #3c654a;
    font-weight: bold;
    padding: 10px;
}
.modal-body {
    margin-top: 137px;
    /* padding: 14px; */
}

        table tbody tr td {
            padding: 10px;
        }

        table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .table>:not(caption)>*>* {
    padding: .5rem .5rem;
    background-color: var(--bs-table-bg);
    border-bottom-width: var(--bs-border-width);
    box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
    text-align: center;
}
input.btn.nv {
    background-color: red;
}

.btn {
    color: #ffffff;
    background-color: #3c654a;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    /* font-weight: bold; */
}

        .btn:hover {
            background-color: #3c654a;
            color: white;
        }
    </style>
