<?php
session_start();
require_once './php/config.php';




function session_expired()
{
    if (!isset($_SESSION['last_activity'])) {
        $_SESSION['last_activity'] = time();
        return false;
    }

    $inactive_duration = 70; // 1 minute en secondes
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "SELECT DATE(`created_at`) AS date, `group` AS prod_line,SUM(`defective_pcs`) AS nbPieceDefct FROM `prod__eol_control` 
                                        ";

    $whereClause = "";


    $chainePro = $_POST['prod_line'] ?? [];


    $startDate = $_POST['startDate'] ?? '';
    $endDate = $_POST['endDate'] ?? '';
    $resultsCont = [];

    if (!empty($chainePro)) {
        $chaineList = implode("','", $chainePro);
        $whereClause .= " AND `group` IN ('$chaineList')";
    }
    if (!empty($startDate) && empty($endDate)) {
        $whereClause .= " AND DATE(created_at) = '$startDate'";
    }
    if (!empty($startDate) && !empty($endDate)) {
        $whereClause .= " AND DATE(created_at) BETWEEN '$startDate' AND '$endDate' ";
    }
    if (empty($startDate) && !empty($endDate)) {
        $whereClause .= " AND DATE(created_at) <= '$endDate'";
    }

    if (!empty($whereClause)) {
        // $sql .= " WHERE 1=1 $whereClause AND  YEAR(`created_at` ) =YEAR(CURRENT_DATE)";
        $sql .= " WHERE 1=1 $whereClause";
    }
    $sql .= "group by `group`,DATE(`created_at`)";


    $req = $con->query($sql);

    if ($req && $req->num_rows > 0) {
        // Récupérer les résultats dans un tableau
        while ($row = $req->fetch_assoc()) {
            $resultsCont[] = $row;
        }
    }
    if (!isset($_SESSION['resultsCont']) || $_POST['action'] == 'filter') {
        // Stocker les résultats dans la session
        $_SESSION['resultsCont'] = $resultsCont;
        $_SESSION['prod_line'] = $chainePro;

        $_SESSION['startDate'] = $startDate;
        $_SESSION['endDate'] = $endDate;

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    // Exporter les données en Excel
    if (!isset($_SESSION['resultsCont']) || $_POST['action'] === 'export') {
        $_SESSION['resultsCont'] = $resultsCont;
        $_SESSION['prod_line'] = $chainePro;
        $_SESSION['startDate'] = $startDate;
        $_SESSION['endDate'] = $endDate;
        header('Location: ./fichierExcel/controleProd.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SIMOTEX | DigiTex By Advantry X</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="img/favicon.ico" />

    <!-- Custom fonts for this template-->
    <link href="css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/select2.min.css" rel="stylesheet" />

    <style>
        th,
        td {
            white-space: nowrap;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
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
                    <h1 class="h3 mt-4 text-gray-800">Contrôle qualité </h1>
                    <p class="mb-4"></p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <?php require_once './php/config.php'; ?>
                            <h6 class="m-0 font-weight-bold text-primary">Retouches / Chaine:</h6>
                            <form action="" class="d-flex flex-wrap" id="filterForm" method="POST"
                                onsubmit="return validateDates();">
                                <!-- <div class="row"> -->
                                <div class="col-md-4">
                                    <label for="digiTex" class="form-label d-block">Chaine de production</label>
                                    <select id="prod_line" name="prod_line[]" class="form-select form-control" multiple>
                                        <?php
                                        $sql2 = "SELECT * FROM `init__prod_line`";
                                        $result2 = mysqli_query($con, $sql2);
                                        while ($row2 = mysqli_fetch_assoc($result2)) { ?>
                                            <option value='<?php echo $row2['prod_line'] ?>' <?php echo in_array($row2['prod_line'], $_SESSION['prod_line'] ?? []) ? 'selected' : ''; ?>>
                                                <?php echo $row2['prod_line']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <!-- </div> -->
                                <!-- <div class="row"> -->
                                <!-- Nouvelle ligne pour les champs de date -->
                                <div class="col-md-4">
                                    <label for="startDate" class="form-label d-block">Date de début</label>
                                    <input type="date" class="form-control" id="startDate" name="startDate"
                                        value="<?php echo htmlspecialchars($_SESSION['startDate'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>

                                <div class="col-md-4">
                                    <label for="endDate" class="form-label d-block">Date de fin</label>
                                    <input type="date" class="form-control" id="endDate" name="endDate"
                                        value="<?php echo htmlspecialchars($_SESSION['endDate'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <!-- </div> -->
                                <br><br><br><br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" name="action" value="filter"
                                            class="btn btn-primary">Valider</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" name="action" value="export"
                                            class="btn btn-primary">Exporter</button>
                                    </div>
                                </div>

                            </form>

                        </div>
                        <div class="card-body">
                            <?php if (!empty($_SESSION['resultsCont'])) { ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Nombre des pièces défaillantes</th>
                                                <th>Chaine de production</th>
                                                <!-- <th>Heure de controle</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php

                                            foreach ($_SESSION['resultsCont'] as $row) {
                                            ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $row['date']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['nbPieceDefct']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['prod_line']; ?>
                                                    </td>


                                                </tr>
                                            <?php
                                            }

                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            <?php } else if ($_SERVER["REQUEST_METHOD"] == "POST") { ?>
                                <div class="alert alert-warning" role="alert">
                                    Aucun résultat trouvé.
                                </div>
                            <?php } ?>
                        </div>

                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Advantry X <?php echo date("Y"); ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <!-- Bootstrap core JavaScript-->
    <script src="js/sb-admin-2.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>
    <script src="js/select2.min.js"></script>
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
                "order": [
                    [0, 'desc']

                ]
            });
            $('#prod_line').select2({
                placeholder: '--Sélectionner chaine de production--',
                tags: false,
                tokenSeparators: [',', ' '],
                // maximumSelectionLength: 1,
                language: "fr"
            });





        });

        function validateDates() {
            var startDate = document.getElementById("startDate").value;
            var endDate = document.getElementById("endDate").value;

            if (startDate && endDate && startDate > endDate) {
                alert("La date de début ne peut pas être supérieure à la date de fin.");
                return false;
            }

            return true;

        }
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("filterForm").addEventListener("submit", function(event) {
                var startDate = document.getElementById("startDate").value;
                var endDate = document.getElementById("endDate").value;
                var chaine = document.getElementById("prod_line").value.trim();

                // Vérifie si au moins un champ est rempli
                if (!startDate && !endDate && !chaine) {
                    event.preventDefault(); // Empêche la soumission du formulaire
                    alert("Veuillez remplir au moins un champ pour filtrer les résultats.");
                }
            });
        });
    </script>

</body>

</html>