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
                                            <th>Rendement par heure en %</th>
                                            <th>Rendement par heure par piéces</th>
                                            <th>Heure</th>
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
                                            <th>Performance par heure</th>
                                            <th>Heure</th>
                                            <th>Date</th>
                                        </tr>
                                    </tfoot> -->
                                    <tbody>
                                        <?php
                                        require_once './php/config.php';
                                        if (isset($_GET["matricule"])) {
                                            $matricule = $_GET["matricule"];
                                            $sql = "SELECT
                                            MIN(`prod__operator_perf_hr`.`prod_line`) AS `prod_line`,
                                            MIN(`prod__operator_perf_hr`.`operator`) AS `operator`,
                                            MIN(`init__employee`.`first_name`) AS `first_name`,
                                            MIN(`init__employee`.`last_name`) AS `last_name`,
                                            MIN(`init__employee`.`card_rfid`) AS `card_rfid`,
                                            MIN(`init__employee`.`qualification`) AS `qualification`,
                                            MIN(`prod__operator_perf_hr`.`performance`) AS `performance`,
                                            MIN(`prod__operator_perf_hr`.`tot_qty`) AS `tot_qty`,
                                            MIN(`prod__operator_perf_hr`.`cur_date`) AS `cur_date`,
                                            MIN(`prod__operator_perf_hr`.`cur_time`) AS `cur_time`
                                        FROM
                                            `prod__operator_perf_hr`
                                        INNER JOIN `init__employee` ON `prod__operator_perf_hr`.`operator` = `init__employee`.`matricule` WHERE
                                     `prod__operator_perf_hr`.`operator`=$matricule
                                     GROUP BY `prod__operator_perf_hr`.`operator`, `prod__operator_perf_hr`.`cur_date`, `prod__operator_perf_hr`.`cur_time`";
                                            $presence = mysqli_query($con, $sql);
                                            $pres = [];
                                            while ($item1 = $presence->fetch_assoc()) {
                                                $pres[] = $item1;
                                            }
                                            for ($i = 0; $i < count($pres); $i++) { ?>
                                                <tr>
                                                    <td>
                                                        <?php echo ($pres[$i]['operator']); ?>
                                                    </td>
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
                                                        <?php echo ($pres[$i]['performance']); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($pres[$i]['tot_qty']); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($pres[$i]['cur_time']); ?>
                                                    </td>
                                                    <td>
                                                <?php echo ($pres[$i]['cur_date']);
                                            }
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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
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