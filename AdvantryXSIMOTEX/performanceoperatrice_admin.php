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

$startDate = $_POST['startDate'] ?? '';
$endDate = $_POST['endDate'] ?? '';

$results = [];

$sql = "SELECT
                                        `kpi__op_qd`.`id`,
                                        `kpi__op_qd`.`operator`,
                                        `kpi__op_qd`.`presence`,
                                      Concat(`init__employee`.`first_name`,'  ',`init__employee`.`last_name`) AS name,
                                        `init__employee`.`card_rfid`,
                                        `init__employee`.`qualification`,
                                        `kpi__op_qd`.`rendement`,
                                        `kpi__op_qd`.`prod_time`,
                                          `kpi__op_qd`.`downtime` AS `downtime`,
                                      /*  COALESCE(`prod__overtime`.`overtime`, 0) AS `overtime`,*/
                                        `kpi__op_qd`.`created_at`
                                    FROM
                                        `kpi__op_qd`
                                    INNER JOIN `init__employee` ON `kpi__op_qd`.`operator` = `init__employee`.`matricule`
   

                                   ";

$whereClause = "";

if (!empty($startDate) && empty($endDate)) {
    $whereClause .= " AND DATE(`kpi__op_qd`.`created_at`) = '$startDate'";
}
if (!empty($startDate) && !empty($endDate)) {
    $whereClause .= " AND DATE(`kpi__op_qd`.`created_at`) BETWEEN '$startDate' AND '$endDate' ";
}
if (empty($startDate) && !empty($endDate)) {
    $whereClause .= " AND DATE(`kpi__op_qd`.`created_at`) <= '$endDate'";
}
if (!empty($whereClause)) {
    $sql .= " WHERE 1=1 $whereClause AND YEAR(`kpi__op_qd`.`created_at`) = YEAR(CURDATE())";
}
$sql .= " ORDER BY `kpi__op_qd`.`created_at` DESC";
$req = $con->query($sql);

if ($req && $req->num_rows > 0) {
    // Récupérer les résultats dans un tableau associatif
    while ($row = $req->fetch_assoc()) {
        $results[] = $row;
    }
}

if (!isset($_SESSION['resultsOP']) || ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] == 'filter')) {
    $_SESSION['resultsOP'] = $results;
    $_SESSION['startDateOP'] = $startDate;
    $_SESSION['endDateOP'] = $endDate;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] === 'export') {
    $_SESSION['resultsOP'] = $results;
    $_SESSION['startDateOP'] = $startDate;
    $_SESSION['endDateOP'] = $endDate;
    header('Location: ./fichierExcel/performanceOP.php');
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
    <!-- Bootstrap core JavaScript-->
    <script src="js/jquery.min.js"></script>

    <link href="css/select2.min.css" rel="stylesheet" />
    <script src="js/select2.min.js"></script>
    <style>
        th,
        td {
            white-space: nowrap;
        }

        .highlight {
            background-color: red;
            color: white;
            /* Optionnel, pour le contraste */
        }
    </style>


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
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
                    <h1 class="h3 mt-4 text-gray-800">Ressources Humaine </h1>
                    <p class="mb-4"></p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <?php if (isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                                <?php echo $_SESSION['success_message']; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php unset($_SESSION['success_message']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                                <?php echo $_SESSION['error_message']; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php unset($_SESSION['error_message']); ?>
                        <?php endif; ?>

                        <script>
                            // Faire disparaître les alertes après 3 secondes
                            $(document).ready(function() {
                                setTimeout(function() {
                                    $("#success-alert, #error-alert").fadeOut("slow");
                                }, 3000);
                            });
                        </script>

                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Rendement Journalière:</h6>
                            <br>
                            <form class="d-flex flex-wrap" id="filterForm" method="POST" action=""
                                onsubmit="return validateDates();">
                                <div class="row mb-3">
                                   
                                    <!-- Nouvelle ligne pour les champs de date -->
                                    <div class="col-md-6">
                                        <label for="startDate" class="form-label d-block">Du</label>
                                        <input type="date" class="form-control" id="startDate" name="startDate"
                                            value="<?php echo htmlspecialchars($_SESSION['startDateOP'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="endDate" class="form-label d-block">Au</label>
                                        <input type="date" class="form-control" id="endDate" name="endDate"
                                            value="<?php echo htmlspecialchars($_SESSION['endDateOP'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <!-- Bouton Soumettre -->
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
                                </div>
                            </form>

                        </div>
                       
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Modifier</th>
                                            <th>Matricule</th>
                                            <th>Nom & Prénom</th>
                                            <th>Fonction</th>
                                            <th>ID Carte</th>
                                            <th>Temps de présence (min)</th>
                                            <th>Hors Standards (min)</th>
                                            <th>Rendement</th>
                                            <th>Minutes produites</th>

                                            <th>Date</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        foreach ($_SESSION['resultsOP'] as $perfOP) { ?>
                                            <?php $rowClass = (round($perfOP['prod_time']) > round($perfOP['presence']) || round($perfOP['rendement'], 2) < 30) ? 'class="highlight"' : ''; ?>
                                            <tr>
                                                <td>
                                                    <a href='performance__edit.php?operation=<?php echo ($perfOP['id']) ?>'>
                                                        <img src="./img/edit.png" alt="icone" width="17mm" height="17mm">
                                                    </a>

                                                </td>
                                                <td><a
                                                        href="perfparheure.php?matricule=<?php echo ($perfOP['operator']); ?>">
                                                        <?php echo ($perfOP['operator']); ?>
                                                    </a></td>
                                                <td>
                                                    <?php echo ($perfOP['name']); ?>
                                                </td>
                                                <td>
                                                    <?php echo ($perfOP['qualification']); ?>
                                                </td>
                                                <td>
                                                    <?php echo ($perfOP['card_rfid']); ?>
                                                </td>

                                                <td>
                                                    <?php echo (round(($perfOP['presence']))); ?>
                                                </td>
                                                <td>
                                                    <?php echo (round($perfOP['downtime'])); ?>
                                                </td>
                                                <td <?php echo $rowClass; ?>>
                                                    <?php echo (round($perfOP['rendement'], 2)) . '%'; ?>
                                                </td>

                                                <td>
                                                    <?php echo (round($perfOP['prod_time'])); ?>
                                                </td>

                                                <td>
                                                <?php echo ($perfOP['created_at']);
                                            }

                                                ?>

                                                </td>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
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
                    [6, 'desc']

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
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("filterForm").addEventListener("submit", function(event) {
                var startDate = document.getElementById("startDate").value.trim();
                var endDate = document.getElementById("endDate").value.trim();

                // Vérifie si au moins un champ est rempli
                if (!startDate && !endDate) {
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
            
            return true;
        }
    </script>

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

</body>

</html>