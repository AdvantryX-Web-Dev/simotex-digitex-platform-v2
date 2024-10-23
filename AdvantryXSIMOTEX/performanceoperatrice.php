<?php
session_start();
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                            <h6 class="m-0 font-weight-bold text-primary">Performance Journalière:</h6>
                            <form action="process.php" method="post">
                                <div class="col-md-3 float-right">
                                    <button type="submit" name="submit1"
                                        class="btn btn-primary float-right">Telecharger</button>
                                </div>
                                <div class="col-md-3 float-right">
                                    <select name="prod_line" class="form-control"> Chaine de Production
                                        <option>Tous</option>
                                        <?php
                                        require_once './php/config.php';
                                        $result = $con->query("SELECT `prod_line` FROM `init__prod_line`");
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option>{$row['prod_line']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3 float-right">
                                    <input type="date" name="date" class="form-control float-right">
                                </div>

                            </form>
                            <!-- <div class="mb-0 mt-2 mr-2"><a href='edit.php?newop=<?php // echo ("i2_operator")  
                                                                                        ?>'><img src="./img/add-file.png" alt="icone" width="25mm" height="25mm"></a></div> -->
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Matricule</th>
                                            <th>Nom & Prénom</th>
                                            <th>Fonction</th>
                                            <th>ID Carte</th>
                                            <th>Chaine de production</th>
                                            <th>Temps de présence</th>
                                            <th>Hors Strandards (min)</th>
                                            <th>Performance</th>
                                            <th>Taux de présence</th>
                                            <th>Heure(s) suplémentaires</th>
                                            <th>Date</th>

                                        </tr>
                                    </thead>
                                    <!-- <tfoot>
                                        <tr>
                                            <th>Matricule</th>
                                            <th>Nom & Prénom</th>
                                            <th>Fonction</th>
                                            <th>ID Carte</th>
                                            <th>Chaine de production</th>
                                            <th>Temps de présence</th>
                                            <th>Temps perdu</th>
                                            <th>Performance</th>
                                            <th>Date</th>
                                        </tr>
                                    </tfoot> -->
                                    <tbody>
                                        <?php
                                        require_once './php/config.php';
                                        $sql = "SELECT
                                        `prod__operator_perf`.`prod_line`,
                                        `prod__operator_perf`.`operator`,
                                        `prod__operator_perf`.`presence`,
                                        `init__employee`.`first_name`,
                                        `init__employee`.`last_name`,
                                        `init__employee`.`card_rfid`,
                                        `init__employee`.`qualification`,
                                        `prod__operator_perf`.`performance`,
                                        `prod__operator_perf`.`prod_time`,
                                        
                                        COALESCE(`total_downtime`.`downtime`, 0) AS `downtime`,
                                        COALESCE(`prod__overtime`.`overtime`, 0) AS `overtime`,
                                        `prod__operator_perf`.`cur_date`
                                    FROM
                                        `prod__operator_perf`
                                    INNER JOIN `init__employee` ON `prod__operator_perf`.`operator` = `init__employee`.`matricule`
                                    LEFT JOIN `prod__overtime` ON `prod__overtime`.`operator` = `init__employee`.`matricule` AND `prod__operator_perf`.`cur_date` = `prod__overtime`.`cur_date`
                                    LEFT JOIN (
                                        SELECT
                                            `aleas__req_interv`.`operator` AS `operator`,
                                            SUM(
                                                TIMESTAMPDIFF(
                                                    MINUTE,
                                                    `aleas__mon_interv`.`created_at`,
                                                    `aleas__end_mon_interv`.`created_at`
                                                )
                                            ) AS `downtime`,
                                            DATE(`aleas__end_mon_interv`.`created_at`) AS `date_aleas`
                                        FROM
                                            `aleas__req_interv`
                                        LEFT JOIN `aleas__end_mon_interv` ON `aleas__end_mon_interv`.`req_interv_id` = `aleas__req_interv`.`id`
                                        LEFT JOIN `aleas__mon_interv` ON `aleas__mon_interv`.`req_interv_id` = `aleas__req_interv`.`id`
                                        GROUP BY
                                            `aleas__req_interv`.`operator`, DATE(`aleas__end_mon_interv`.`created_at`)
                                    ) AS `total_downtime` ON `prod__operator_perf`.`operator` = `total_downtime`.`operator` AND `prod__operator_perf`.`cur_date` = `total_downtime`.`date_aleas`  
ORDER BY `prod__operator_perf`.`cur_date` DESC;";
                                        $presence = mysqli_query($con, $sql);
                                        $pres = [];
                                        while ($item1 = $presence->fetch_assoc()) {
                                            $pres[] = $item1;
                                        }
                                        for ($i = 0; $i < count($pres); $i++) { ?>
                                            <tr>
                                                <td><a
                                                        href="perfparheure.php?matricule=<?php echo ($pres[$i]['operator']); ?>">
                                                        <?php echo ($pres[$i]['operator']); ?>
                                                    </a></td>
                                                <td>
                                                    <?php echo ($pres[$i]['first_name'] . ' ' . $pres[$i]['last_name']); ?>
                                                </td>
                                                <td>
                                                    <?php echo ($pres[$i]['qualification']); ?>
                                                </td>
                                                <td>
                                                    <?php echo ($pres[$i]['card_rfid']); ?>
                                                </td>
                                                <td>
                                                    <?php echo ($pres[$i]['prod_line']); ?>
                                                </td>
                                                <td>
                                                    <?php echo ($pres[$i]['presence']); ?>
                                                </td>
                                                <td>
                                                    <?php echo ($pres[$i]['downtime']); ?>
                                                </td>
                                                <td>
                                                    <?php echo ($pres[$i]['performance']); ?>
                                                </td>
                                                <td>
                                                    <?php if ($pres[$i]['presence'] >= 525) {
                                                        echo 1;
                                                    } else {
                                                        echo (round(($pres[$i]['presence'] / 540), 2));
                                                    }; ?>
                                                </td>
                                                <td>
                                                    <?php echo ($pres[$i]['overtime']); ?>
                                                </td>
                                                <td>
                                                <?php echo ($pres[$i]['cur_date']);
                                            } ?>
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
                        <span> Copyright &copy; Advantry X 2024</span>
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
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>