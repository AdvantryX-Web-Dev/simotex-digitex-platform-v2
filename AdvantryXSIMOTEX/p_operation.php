<?php

session_start();

require_once './php/config.php';

function session_expired()
{
    if (!isset($_SESSION['last_activity'])) {
        $_SESSION['last_activity'] = time();
        return false;
    }

    $inactive_duration = 60; // 1 minute en secondes
    $session_age = time() - $_SESSION['last_activity'];

    if ($session_age > $inactive_duration) {
        return true;
    }

    $_SESSION['last_activity'] = time();
    return false;
}

// Vider session si expirée
if (session_expired()) {
    session_unset();
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// $operators = $_POST['operator'] ?? [];
// $digiTexes = $_POST['digiTex'] ?? [];
// $models = $_POST['model'] ?? [];
// $startDate = $_POST['startDate'] ?? '';
// $endDate = $_POST['endDate'] ?? '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $operators = $_POST['operator'] ?? [];
    $digiTexes = $_POST['digiTex'] ?? [];
    $models = $_POST['model'] ?? [];
    // $operation = $_POST['operation'] ?? '';
    $startDate = $_POST['startDate'] ?? '';
    $endDate = $_POST['endDate'] ?? '';
    $results = [];
    $sql = "SELECT op.*, CONCAT(e.first_name, ' ', e.last_name) AS nomOp  
        FROM `prod__pack_operation` op 
        JOIN init__employee e ON (op.operator=e.matricule)";

    $whereClause = "";

    if (!empty($operators)) {
        $operatorList = implode("','", $operators);
        $whereClause .= " AND op.operator IN ('$operatorList')";
    }
    if (!empty($digiTexes)) {
        $digiTexList = implode("','", $digiTexes);
        $whereClause .= " AND op.smartbox IN ('$digiTexList')";
    }
    if (!empty($models)) {
        $modelList = implode("','", $models);
        $whereClause .= " AND op.model IN ('$modelList')";
    }
    if (!empty($startDate) && empty($endDate)) {
        $whereClause .= " AND op.cur_date = '$startDate'";
    }
    if (!empty($startDate) && !empty($endDate)) {
        $whereClause .= " AND op.cur_date BETWEEN '$startDate' AND '$endDate' ";
    }
    if (empty($startDate) && !empty($endDate)) {
        $whereClause .= " AND op.cur_date <='$endDate'";
    }

    if (!empty($whereClause)) {
        // $sql .= " WHERE 1=1 $whereClause AND YEAR(op.cur_date)= YEAR(CURRENT_DATE)";
        $sql .= " WHERE 1=1 $whereClause";
    }

    $sql .= " ORDER BY op.cur_date DESC";

    $req = $con->query($sql);
    if ($req && $req->num_rows > 0) {
        // Récupérer les résultats dans un tableau
        while ($row = $req->fetch_assoc()) {
            $results[] = $row;
        }
    }
    if ($_POST['action'] === 'filter') {
        // Stocker les résultats dans la session
        $_SESSION['results'] = $results;
        $_SESSION['models'] = $models;
        $_SESSION['operators'] = $operators;
        $_SESSION['digiTexes'] = $digiTexes;
        // $_SESSION['operation'] = $operation;
        $_SESSION['startDate'] = $startDate;
        $_SESSION['endDate'] = $endDate;
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {

        $_SESSION['results'] = $results;
        $_SESSION['startDate'] = $startDate;
        $_SESSION['endDate'] = $endDate;
        header('Location: ./fichierExcel/p_operation.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SIMOTEX | DigiTex By Advantry X</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="img/favicon.ico" />

    <link href="css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Bootstrap core JavaScript-->
    <script src="js/jquery.min.js"></script>

    <link href="css/select2.min.css" rel="stylesheet" />
    <script src="js/select2.min.js"></script>
    <style>
        th,
        td {
            white-space: nowrap;
        }
    </style>
</head>

<div id="wrapper">

    <!-- Sidebar -->
    <?php include("sideBare.php"); ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <h1 class="h3 mt-4 text-gray-800"></h1>
                <p class="mb-4"></p>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Filtrer les résultats</h6>
                        <form class="d-flex flex-wrap" id="filterForm" method="POST" action="" onsubmit="return validateDates();">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="operator" class="form-label d-block">Opérateur</label>
                                    <select id="operator" name="operator[]" class="form-select form-control" multiple>
                                        <?php
                                        $sql1 = "SELECT * FROM `init__employee`";
                                        $result1 = mysqli_query($con, $sql1);
                                        while ($row1 = mysqli_fetch_assoc($result1)) { ?>
                                            <option value='<?php echo $row1['matricule'] ?>'
                                                <?php echo in_array($row1['matricule'], $_SESSION['operators'] ?? []) ? 'selected' : ''; ?>>
                                                <?php echo $row1['matricule'] . '|' . $row1['first_name'] . ' ' . $row1['last_name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="digiTex" class="form-label d-block">DigiTex</label>
                                    <select id="digiTex" name="digiTex[]" class="form-select form-control" multiple>
                                        <?php
                                        $sql2 = "SELECT * FROM `init__smartbox`";
                                        $result2 = mysqli_query($con, $sql2);
                                        while ($row2 = mysqli_fetch_assoc($result2)) { ?>
                                            <option value='<?php echo $row2['smartbox'] ?>'
                                                <?php echo in_array($row2['smartbox'], $_SESSION['digiTexes'] ?? []) ? 'selected' : ''; ?>>
                                                <?php echo $row2['smartbox']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="model" class="form-label d-block">Modèle</label>
                                    <select id="model" name="model[]" class="form-select form-control" multiple>
                                        <?php
                                        $sql3 = "SELECT * FROM `init__model`";
                                        $result3 = mysqli_query($con, $sql3);
                                        while ($row3 = mysqli_fetch_assoc($result3)) { ?>
                                            <option value='<?php echo $row3['model'] ?>'
                                                <?php echo in_array($row3['model'],  $_SESSION['models'] ?? []) ? 'selected' : ''; ?>>
                                                <?php echo $row3['model']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <br><br><br>
                                <!-- Nouvelle ligne pour les champs de date -->
                                <div class="col-md-6">
                                    <label for="startDate" class="form-label d-block">Date de début</label>
                                    <input type="date" class="form-control" id="startDate" name="startDate"
                                        value="<?php echo htmlspecialchars($_SESSION['startDate'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>

                                <div class="col-md-6">
                                    <label for="endDate" class="form-label d-block">Date de fin</label>
                                    <input type="date" class="form-control" id="endDate" name="endDate"
                                        value="<?php echo htmlspecialchars($_SESSION['endDate'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <br><br><br><br>
                                <!-- Bouton Soumettre -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" name="action" value="filter" class="btn btn-primary">Valider</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" name="action" value="export" class="btn btn-primary">Exporter</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($_SESSION['results'])) { ?>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Réf de l'OF</th>
                                            <th>Réf Modèle</th>
                                            <th>Chaine</th>
                                            <th>Numéro Paquet</th>
                                            <th>Quantité Paquet</th>
                                            <th>Numéro Opération</th>
                                            <th>Désignation d'opération</th>
                                            <th>Temps d'opération</th>
                                            <th>Opératrice</th>
                                            <th>Smartbox</th>
                                            <th>Date & Heure</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($_SESSION['results'] as $row) { ?>
                                            <tr>
                                                
                                                <td><?= $row['of_num'] ?></td>
                                                <td><?= $row['model'] ?></td>
                                                <td><?= $row['prod_line'] ?></td>
                                                <td><?= $row['pack_num'] ?></td>
                                                <td><?= $row['pack_qty'] ?></td>
                                                <td><?= $row['operation_num'] ?></td>
                                                <td><?= $row['designation'] ?></td>
                                                <td><?= $row['unit_time'] ?></td>
                                                <td><?= $row['operator'] . ' | ' . $row['nomOp'] ?></td>
                                                <td><?= $row['smartbox'] ?></td>
                                                <td><?= $row['cur_date'] . ' ' . $row['cur_time'] ?></td>
                                            </tr>
                                        <?php }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        <?php  } else if ($_SERVER["REQUEST_METHOD"] == "POST") { ?>
                            <div class="alert alert-warning" role="alert">
                                Aucun résultat trouvé.
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function() {
                    $('#dataTable').DataTable({


                        language: {
                            search: "Rechercher:",
                            // searchPlaceholder: "Saisissez votre recherche",
                            lengthMenu: "Afficher _MENU_ éléments par page",
                            info: "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                            infoEmpty: "Aucun élément à afficher",
                            infoFiltered: "(filtré de _MAX_ éléments au total)",
                            zeroRecords: "Aucun enregistrement correspondant trouvé",
                            paginate: {
                                first: "Premier",
                                previous: "Précédent",
                                next: "Suivant",
                                last: "Dernier",

                            }

                        },
                    });
                    $('#model').select2({
                        placeholder: '--Sélectionner un modèle--',
                        tags: false,
                        tokenSeparators: [',', ' '],
                        // maximumSelectionLength: 1,
                        language: "fr"
                    });

                    $('#operator').select2({
                        placeholder: '--Sélectionner une opératrice--',
                        tags: false,
                        tokenSeparators: [',', ' '],
                        // maximumSelectionLength: 1,
                        language: "fr"
                    });

                    $('#digiTex').select2({
                        placeholder: '--Sélectionner DigiTex--',
                        tags: false,
                        tokenSeparators: [',', ' '],
                        // maximumSelectionLength: 1,
                        language: "fr"
                    });



                });
                document.addEventListener("DOMContentLoaded", function() {
                    document.getElementById("filterForm").addEventListener("submit", function(event) {
                        var operator = document.getElementById("operator").value.trim();
                        var digiTex = document.getElementById("digiTex").value.trim();
                        var model = document.getElementById("model").value.trim();

                        var startDate = document.getElementById("startDate").value.trim();
                        var endDate = document.getElementById("endDate").value.trim();

                        // Vérifie si au moins un champ est rempli
                        if (!operator && !digiTex && !model && !startDate && !endDate) {
                            event.preventDefault(); // Empêche la soumission du formulaire
                            alert("Veuillez remplir au moins un champ pour filtrer les résultats.");
                        }
                    });
                });

                function validateDates() {
                    var startDate = document.getElementById("startDate").value;
                    var endDate = document.getElementById("endDate").value;

                    if (startDate && endDate && startDate > endDate) {
                        alert("La date de début ne peut pas être supérieure à la date de fin.");
                        return false;
                    }
                    // if (!startDate && endDate) {
                    //     alert("Il doit saisir la date début.");
                    //     return false;
                    // }
                    return true;
                }
            </script>
            <!-- Inclure jQuery et Bootstrap JS -->
        </div>
    </div>
</div>
</body>
<script src="js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="js/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="js/datatables-demo.js"></script>

</html>