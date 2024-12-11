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
// Initialiser les variables de session si elles ne sont pas définies
if (!isset($_SESSION['resultsG'])) {
    $_SESSION['resultsG'] = [];
}
// Traitement du formulaire lorsque soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialisation des variables pour les valeurs par défaut ou les valeurs du formulaire

    $models = $_POST['model'] ?? [];

    $results = [];

    $sql = "SELECT `prod__gamme`.`operation_num`, `prod__gamme`.`designation`, `prod__gamme`.`unit_time`, `prod__gamme`.`qte_h`, `prod__gamme`.`machine_id`,
                                `prod__gamme`.`smartbox`, `prod__gamme`.`main_sb`, `prod__gamme`.`model_id`, `prod__gamme`.`id`,
                                `init__model`.`model` 
                                FROM `prod__gamme` 
                                 INNER JOIN `init__model` ON `prod__gamme`.`model_id`= `init__model`.`id`
                                   ";

    $whereClause = "";


    if (!empty($models)) {
        $modelList = implode("','", $models);
        $whereClause .= " AND `init__model`.`id` IN ('$modelList')";
    }

    if (!empty($whereClause)) {
        $sql .= " WHERE 1=1 $whereClause";
    }


    $req = $con->query($sql);

    if ($req && $req->num_rows > 0) {
        // Récupérer les résultats dans un tableau associatif
        while ($row = $req->fetch_assoc()) {
            $results[] = $row;
        }
    }
    // Stocker les résultats dans la session
    $_SESSION['resultsG'] = $results;
    $_SESSION['modelsG'] = $models;

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
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

    <title>SIMOTEX DigiTex By Advantry X</title>
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
        <?php include("sideBare.php") ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mt-4 text-gray-800">Méthode </h1>
                    <p class="mb-4"></p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">

                            <h6 class="m-0 font-weight-bold text-primary">Affectation Opération-Box : </h6>
                            <form class="d-flex flex-wrap" id="filterForm" method="POST">
                                <div class="col-md-4">
                                    <label for="operation" class="form-label d-block">Modèles</label>
                                    <select id="model" name="model[]" class="form-select form-control" multiple>
                                        <?php
                                        $sql3 = "SELECT * FROM `init__model` ORDER by import_dt DESC ";
                                        $result3 = mysqli_query($con, $sql3);
                                        while ($row3 = mysqli_fetch_assoc($result3)) { ?>
                                            <option value='<?php echo $row3['id'] ?>'
                                                <?php echo in_array($row3['id'],  $_SESSION['modelsG'] ?? []) ? 'selected' : ''; ?>>
                                                <?php echo $row3['model']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-primary" id="submitButton">Valider</button>
                                </div>

                            </form>



                        </div>
                        <div class="card-body">
                            <?php if (!empty($_SESSION['resultsG'])) { ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <!-- <th>Modifier</th> -->
                                                <th>Code opération</th>
                                                <th>Désignation</th>
                                                <th>Temps unitaire</th>
                                                <th>Quantité par heure</th>
                                                <!-- <th>Machine</th> -->
                                                <th>Digitex</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php


                                            foreach ($_SESSION['resultsG'] as $p3_gamme) {
                                            ?>
                                                <tr>

                                                    <td>
                                                        <?php echo $p3_gamme['operation_num']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $p3_gamme['designation']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $p3_gamme['unit_time']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $p3_gamme['qte_h']; ?>
                                                        <!-- <td> -->
                                                        <?php //echo $p3_gamme['machine_id']; 
                                                        ?>
                                                    <td>
                                                    <?php echo $p3_gamme['smartbox'];
                                                }
                                                    ?>
                                                    </td>
                                                </tr>

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
                        <span> Copyright &copy; Advantry X <?php echo date("Y"); ?></span>
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
                    search: "Rechercher :",
                    lengthMenu: "Afficher _MENU_ éléments par page",
                    info: "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                    infoEmpty: "Aucun élément à afficher",
                    infoFiltered: "(filtré de _MAX_ éléments au total)",
                    zeroRecords: "Aucun enregistrement correspondant trouvé",
                    paginate: {
                        first: "Premier",
                        previous: "Précédent",
                        next: "Suivant",
                        last: "Dernier"
                    }
                }
            });

            $('#model').select2({
                placeholder: '--Sélectionner un modèle--',
                language: "fr"
            });

        });
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("filterForm").addEventListener("submit", function(event) {
                var ofs = document.getElementById("of").value.trim();

                var models = document.getElementById("model").value.trim();


                // Vérifie si au moins un champ est rempli
                if (!models && !ofs) {
                    event.preventDefault(); // Empêche la soumission du formulaire
                    alert("Veuillez remplir au moins un champ pour filtrer les résultats.");
                }
            });
        });
    </script>


</body>

</html>