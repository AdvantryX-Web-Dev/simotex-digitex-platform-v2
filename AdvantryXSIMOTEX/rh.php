<?php
session_start();
require_once './php/config.php';

// Fonction pour vérifier si 5 minutes se sont écoulées
function session_expired()
{
    if (!isset($_SESSION['last_activity'])) {
        $_SESSION['last_activity'] = time();
        return false;
    }

    $inactive_duration = 1000; // 1 minutes en secondes
    $session_age = time() - $_SESSION['last_activity'];

    if ($session_age > $inactive_duration) {
        return true;
    }

    $_SESSION['last_activity'] = time();
    return false;
}
//vider session
if (session_expired()) {
    session_unset(); // Utiliser session_unset() au lieu de $_SESSION = [];
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $operators = $_POST['operator'] ?? [];

    $results = [];

    $sql = "SELECT * FROM `init__employee`";

    $whereClause = "";

    if (!empty($operators)) {
        $operatorList = implode("','", $operators);
        $whereClause .= " AND `matricule` IN ('$operatorList')";
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
    $_SESSION['resultsOPE'] = $results;
    $_SESSION['operatorsOPE'] = $operators;

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
        <?php include("sideBare.php") ?>
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
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Opératrices:</h6>
                            <form class="d-flex flex-wrap" id="filterForm" method="POST">
                                <div class="col-md-4">
                                    <div class="mb-0 mt-2 mr-2">
                                        <a href='edit.php?newop=<?php echo ("init__employee") ?>'>
                                            <img src="./img/add-file.png" alt="icone" width="25mm" height="25mm">
                                        </a>
                                    </div>
                                    <!-- <label for="operator" class="form-label d-block">Opératrices</label> -->
                                    <select id="operator" name="operator[]" class="form-select form-control" multiple>
                                        <?php
                                        require_once './php/config.php';
                                        $sql1 = "SELECT * FROM `init__employee`";
                                        $result1 = mysqli_query($con, $sql1);
                                        while ($row1 = mysqli_fetch_assoc($result1)) { ?>
                                            <option value='<?php echo $row1['matricule'] ?>'
                                                <?php echo in_array($row1['matricule'], $_SESSION['operatorsOPE'] ?? []) ? 'selected' : ''; ?>>
                                                <?php echo $row1['matricule'] . '|' . $row1['first_name'] . ' ' . $row1['last_name']; ?>
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
                            <?php if (!empty($_SESSION['resultsOPE'])) { ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Modifier </th>
                                                <th>Matricule</th>
                                                <th>Nom & Prénom</th>
                                                <!-- <th>Fonction</th> -->
                                                <th>ID Carte</th>

                                                <th>Nombre des operations aujourd'hui</th>
                                                <th>Minute Hrs</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            foreach ($_SESSION['resultsOPE'] as $row) {  ?>
                                                <tr>
                                                    <td> <a href='edit.php?operatrice=<?php echo ($row['matricule']) ?>'><img
                                                                src="./img/edit.png" alt="icone" width="17mm" height="17mm"></a>
                                                        <!-- &emsp; <a
                                                        href='deleteconf.php?operatrice=<?php echo ($row['matricule']) ?>'><img
                                                            src="./img/delete.png" alt="icone" width="17mm"
                                                            height="17mm"></a> -->
                                                    </td>
                                                    <td>
                                                        <?php echo ($row['matricule']); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($row['last_name'] . ' ' . $row['first_name']); ?>
                                                    </td>

                                                    <td>
                                                        <?php echo ($row['card_rfid']); ?>
                                                    </td>
                                                    <!-- <td>
                                                    <?php /*$operator = $row['matricule'];
                                                    $sql = "SELECT *
                                                            FROM `prod__pack_operation`
                                                            WHERE `operator`='$operator' 
                                                            ";
                                                    $rsl4 = $con->query($sql);
                                                    $packet = [];
                                                    while ($item4 = $rsl4->fetch_assoc()) {
                                                        $packet[] = $item4;
                                                    }*/ ?>
                                                    <a href='pack.php?matricule=<?php //echo $operator 
                                                                                ?>'>
                                                        <?php //echo count($packet); 
                                                        ?>
                                                    </a>
                                                </td> -->

                                                    <td>
                                                        <?php $operator = $row['matricule'];
                                                        $sql = "SELECT *
                                                            FROM `prod__pack_operation`
                                                            WHERE `operator`='$operator' AND `cur_date`= CURRENT_DATE
                                                            ";
                                                        $rsl4 = $con->query($sql);
                                                        $packet = [];
                                                        while ($item4 = $rsl4->fetch_assoc()) {
                                                            $packet[] = $item4;
                                                        } ?>
                                                        <a href='pack.php?matriculej=<?php echo $operator ?>'>
                                                            <?php echo count($packet);
                                                            ?>
                                                        </a>
                                                    </td>
                                                    <td>

                                                    <?php $operator = $row['matricule'];
                                                    $sql = "SELECT 
                                                    MAX(`aleas__req_interv`.`operator`) AS `operator`,
                                                    SUM(
                                                        TIMESTAMPDIFF(
                                                            MINUTE,
                                                            `aleas__mon_interv`.`created_at`,
                                                            `aleas__end_mon_interv`.`created_at`
                                                        )
                                                    ) AS `downtime`,
                                                    MAX(`aleas__end_mon_interv`.`created_at`) AS date_aleas
                                                FROM
                                                    `aleas__req_interv`
                                                LEFT JOIN `aleas__end_mon_interv` ON `aleas__end_mon_interv`.`req_interv_id` = `aleas__req_interv`.`id`
                                                LEFT JOIN `aleas__mon_interv` ON `aleas__mon_interv`.`req_interv_id` = `aleas__req_interv`.`id`
                                                WHERE DATE(`aleas__req_interv`.`created_at`)= CURRENT_DATE AND `operator`='$operator'
                                                GROUP BY
                                                    `aleas__req_interv`.`operator`,
                                                    DATE(`aleas__end_mon_interv`.`created_at`)
                                                            ";
                                                    $rsl4 = $con->query($sql);
                                                    $aleas = [];
                                                    while ($item4 = $rsl4->fetch_assoc()) {
                                                        $aleas[] = $item4;
                                                    }

                                                    if (count($aleas) > 0) {
                                                        echo '<a href="aleas.php?matricule=' . $operator . '">' . $aleas[0]['downtime'] . '<br> <small> Date & Heure reprise prod:' . $aleas[0]['date_aleas'] . '</small></a>';
                                                    } else {
                                                        echo '<a href="aleas.php?matricule=' . $operator . '">Pas de temps Hors Standards </a>';
                                                    }
                                                } ?>
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

            $('#operator').select2({
                placeholder: '--Sélectionner une opératrice--',
                language: "fr"
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("filterForm").addEventListener("submit", function(event) {
                var operator = document.getElementById("operator").value.trim();
                if (!operator) {
                    event.preventDefault();
                    alert("Veuillez remplir le champ pour filtrer les résultats.");
                }
            });
        });
    </script>



</body>

</html>