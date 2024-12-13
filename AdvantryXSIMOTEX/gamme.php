<?php session_start(); ?>
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
                    <h1 class="h3 mt-4 text-gray-800">Méthode </h1>
                    <p class="mb-4"></p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <?php
                            require_once './php/config.php';

                            if (isset($_GET["model_id"])) {
                                $model = $_GET["model_id"];
                                $sql = "SELECT * FROM init__model WHERE id=$model";
                                $rslt = $con->query($sql);
                                $gam = [];
                                while ($item3 = $rslt->fetch_assoc()) {
                                    $gam[] = $item3;
                                }
                                $mod = $gam[0]['model'];
                            ?>
                                <h6 class="m-0 font-weight-bold text-primary">Modèle-Gamme
                                    <?php echo ($mod); ?> :
                                </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Code opération</th>
                                            <th>Désignation</th>
                                            <th>Temps unitaire</th>
                                            <th>Quantité par heure</th>
                                            <th>Machine</th>
                                            <th>Digitex</th>
                                        </tr>
                                    </thead>
                                    <!-- <tfoot>
                                        <tr>
                                            <th>Code opération</th>
                                            <th>Désignation</th>
                                            <th>Temps unitaire</th>
                                            <th>Quantité par heure</th>
                                            <th>Machine</th>
                                            <th>Digitex</th>
                                        </tr>
                                    </tfoot> -->
                                    <tbody>

                                        <?php

                                        $query3 = "SELECT MAX(`prod__gamme`.`model_id`) as `model_id`, MAX(`init__model`.`model`) as `model`, MAX(`prod__gamme`.`operation_num`) as `operation_num`, MAX(`prod__gamme`.`designation`) AS `designation`, MAX(`prod__gamme`.`unit_time`) as `unit_time`, 
                                MAX(`prod__gamme`.`qte_h`) as `qte_h`, MAX(`prod__gamme`.`machine_id`) as `machine_id`, MAX(`prod__gamme`.`smartbox`) as `smartbox`, MAX(`prod__gamme`.`import_dt`) as `import_dt` 
                                FROM `init__model` 
                                INNER JOIN `prod__gamme` ON `init__model`.`id`= `prod__gamme`.`model_id` 
                                WHERE `init__model`.`id`=$model GROUP BY `operation_num`;";
                                        $rsl3 = $con->query($query3);
                                        $p3_gamme1 = [];
                                        while ($item3 = $rsl3->fetch_assoc()) {
                                            $p3_gamme1[] = $item3;
                                        }
                                        $i = 0;
                                        while ($i < count($p3_gamme1)) {
                                        ?>
                                            <tr>
                                                <td>
                                                    <?php echo $p3_gamme1[$i]['operation_num']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $p3_gamme1[$i]['designation']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $p3_gamme1[$i]['unit_time']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $p3_gamme1[$i]['qte_h']; ?>
                                                <td>
                                                    <?php echo $p3_gamme1[$i]['machine_id']; ?>
                                                <td>
                                            <?php echo $p3_gamme1[$i]['smartbox'];
                                            $i++;
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