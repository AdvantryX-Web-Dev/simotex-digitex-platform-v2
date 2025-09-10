<?php
session_start();
require_once './php/config.php';


$operation_id = $_GET['operation'];

// Fetch current record data
$sql = "SELECT 
            prod__operator_perf.*, 
            Concat(init__employee.first_name, ' ', init__employee.last_name) AS name,
            init__employee.qualification,
            COALESCE(total_downtime.downtime, 0) AS downtime
        FROM prod__operator_perf 
        INNER JOIN init__employee ON prod__operator_perf.operator = init__employee.matricule
        LEFT JOIN (
            SELECT
                aleas__req_interv.operator AS operator,
                SUM(
                    TIMESTAMPDIFF(
                        MINUTE,
                        aleas__mon_interv.created_at,
                        aleas__end_mon_interv.created_at
                    )
                ) AS downtime,
                DATE(aleas__end_mon_interv.created_at) AS date_aleas
            FROM
                aleas__req_interv
            LEFT JOIN aleas__end_mon_interv ON aleas__end_mon_interv.req_interv_id = aleas__req_interv.id
            LEFT JOIN aleas__mon_interv ON aleas__mon_interv.req_interv_id = aleas__req_interv.id
            GROUP BY
                aleas__req_interv.operator, DATE(aleas__end_mon_interv.created_at)
        ) AS total_downtime ON prod__operator_perf.operator = total_downtime.operator 
           AND prod__operator_perf.cur_date = total_downtime.date_aleas
        WHERE prod__operator_perf.id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $operation_id);
$stmt->execute();
$result = $stmt->get_result();

$record = $result->fetch_assoc();

if (!$record) {
    $_SESSION['error_message'] = "Enregistrement non trouvé";
    header('Location: performanceoperatrice_admin.php');
    exit();
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $presence = floatval($_POST['presence']);
    $performance = floatval($_POST['performance']);
    
    // Validation
    // if ($presence <= 0) {
    //     $error_message = "Le temps de présence doit être supérieur à 0";
    // } elseif ($performance < 0 || $performance > 100) {
    //     $error_message = "La performance doit être entre 0 et 100%";
    // } else {
    //     // Calculate prod_time based on performance and presence
    //    // $prod_time = ($performance / 100) * $presence;
        
    //     // Update the record with all fields
    //     $update_sql = "UPDATE prod__operator_perf SET presence = ?, performance = ?WHERE id = ?";
    //     $update_stmt = $con->prepare($update_sql);
    //     $update_stmt->bind_param("ddi", $presence, $performance, $operation_id);
        
    //     if ($update_stmt->execute()) {
    //         $_SESSION['success_message'] = "Données de performance mises à jour avec succès";
    //         header('Location: performanceoperatrice_admin.php');
    //         exit();
    //     } else {
    //         $error_message = "Erreur lors de la mise à jour: " . $con->error;
    //     }
    // }
    // Validation
if ($presence <= 0) {
    $error_message = "Le temps de présence doit être supérieur à 0";
} elseif ($performance < 0 || $performance > 100) {
    $error_message = "La performance doit être comprise entre 0 et 100%";
} else {
    // Préparation de la requête UPDATE
    $update_sql = "UPDATE prod__operator_perf 
                   SET presence = ?, performance = ?
                   WHERE id = ?";
    $update_stmt = $con->prepare($update_sql);

    if ($update_stmt) {
        // "d" = double (pour decimal), "i" = integer
        $update_stmt->bind_param("ddi", $presence, $performance, $operation_id);

        if ($update_stmt->execute()) {
            $_SESSION['success_message'] = " Données de performance mises à jour avec succès";
            header("Location: performanceoperatrice_admin.php");
            exit();
        } else {
            $error_message = "❌ Erreur lors de la mise à jour : " . $update_stmt->error;
        }

        $update_stmt->close();
    } else {
        $error_message = "❌ Erreur de préparation de la requête : " . $con->error;
    }
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
    <!-- Bootstrap core JavaScript-->
    <script src="js/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Faire disparaître les alertes après 3 secondes
            setTimeout(function() {
                $(".alert-success, .alert-danger").fadeOut("slow");
            }, 3000);
            
            // Calculer automatiquement le temps produit lorsque les valeurs changent
            $("#presence, #performance").on("input", function() {
                calculateProdTime();
            });

            function calculateProdTime() {
                var presence = parseFloat($("#presence").val()) || 0;
                var performance = parseFloat($("#performance").val()) || 0;
                var prodTime = (performance / 100) * presence;
                console.log("Temps de présence: " + presence + ", Performance: " + performance + ", Minutes produites: " + prodTime);
            }
        });
    </script>
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
                    <h1 class="h3 mt-4 text-gray-800">Modification :
                    </h1>

                    <!-- Edit Form -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Opérateur: <?php echo htmlspecialchars($record['name'] ?? 'Non trouvé'); ?>
                                (<?php echo htmlspecialchars($record['operator'] ?? ''); ?>)
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php if (isset($error_message)): ?>
                                <div class="alert alert-danger" id="error-alert">
                                    <?php echo $error_message; ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (isset($_SESSION['success_message'])): ?>
                                <div class="alert alert-success" id="success-alert">
                                    <?php 
                                    echo $_SESSION['success_message']; 
                                    unset($_SESSION['success_message']);
                                    ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="">
                                <div class="form-group row">
                                    <label for="operator" class="col-sm-3 col-form-label">Matricule:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="operator" value="<?php echo htmlspecialchars($record['operator'] ?? ''); ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="presence" class="col-sm-3 col-form-label">Temps de présence (min):</label>
                                    <div class="col-sm-9">
                                        <input type="number" step="0.01" class="form-control" id="presence" name="presence" value="<?php echo htmlspecialchars($record['presence'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="downtime" class="col-sm-3 col-form-label">Hors Standards (min):</label>
                                    <div class="col-sm-9">
                                        <input type="number" step="0.01" class="form-control" id="downtime" value="<?php echo htmlspecialchars($record['downtime'] ?? ''); ?>" readonly>
                                        <small class="form-text text-muted">Ce champ est calculé automatiquement et ne peut pas être modifié.</small>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="performance" class="col-sm-3 col-form-label">Performance (%):</label>
                                    <div class="col-sm-9">
                                        <input type="number" step="0.01" class="form-control" id="performance" name="performance" value="<?php echo htmlspecialchars($record['performance'] ?? ''); ?>" required min="0" max="100">
                                    </div>
                                </div>

                             

                                <div class="form-group row">
                                    <div class="col-sm-9 offset-sm-3">
                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        <a href="performanceoperatrice_admin.php" class="btn btn-secondary">Retour</a>
                                    </div>
                                </div>
                            </form>
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

    <script src="js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="js/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>