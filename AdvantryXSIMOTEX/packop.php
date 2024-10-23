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
        <?php include("sideBare.php") ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <!-- Page Heading -->
                        <h1 class="h3 mb-2 text-gray-800">Paquet</h1>
                    </div>
                </div>
            </div>
            <!-- Content Row -->
            <div class="row">
                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <?php
                            require_once './php/config.php';
                            if (isset($_GET["pack_num"])) {
                                $pack = $_GET["pack_num"]; ?>
                                <h6 class="m-0 font-weight-bold text-primary">Opération sur paquet numéro
                                    <?php echo ($pack); ?> :
                                </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Code Opération effectueé</th>
                                            <th>Designation</th>
                                            <th>Temps unitaire</th>
                                            <th>Quantité</th>
                                            <th>Chaine de production</th>
                                            <th>Opératrice</th>
                                            <th>DigiTex</th>
                                            <th>Date</th>
                                            <th>Heure</th>
                                        </tr>
                                    </thead>
                                    <!-- <tfoot>
                            <tr>
                                <th>Code Opération effectueé</th>
                                <th>Designation</th>
                                <th>Temps unitaire</th>
                                <th>Quantité</th>
                                <th>Chaine de production</th>
                                <th>Opératrice</th>
                                <th>DigiTex</th>
                                <th>Date</th>
                                <th>heure</th>
                            </tr>
                        </tfoot> -->
                                    <tbody>
                                        <?php
                                        $query1 = "SELECT * FROM `prod__pack_operation` INNER JOIN `init__employee` ON `prod__pack_operation`.`operator`= `init__employee`.`matricule` WHERE pack_num='$pack'";
                                        $rsl1 = $con->query($query1);
                                        $p4_pack_operation = [];
                                        while ($item1 = $rsl1->fetch_assoc()) {
                                            $p4_pack_operation[] = $item1;
                                        }

                                        for ($i = 0; $i < count($p4_pack_operation); $i++) { ?>
                                            <tr>
                                                <td>
                                                    <?php echo $p4_pack_operation[$i]['operation_num']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $p4_pack_operation[$i]['designation']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $p4_pack_operation[$i]['unit_time']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $p4_pack_operation[$i]['pack_qty']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $p4_pack_operation[$i]['prod_line']; ?>
                                                </td>
                                                <td> <a
                                                        href='rh.php?matricule=<?php echo $p4_pack_operation[$i]['operator']; ?>'>
                                                        <?php echo $p4_pack_operation[$i]['first_name'] . '  ' . $p4_pack_operation[$i]['last_name']; ?>
                                                    </a></td>
                                                <td>
                                                    <?php echo $p4_pack_operation[$i]['smartbox']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $p4_pack_operation[$i]['cur_date']; ?>
                                                </td>
                                                <td>
                                            <?php echo $p4_pack_operation[$i]['cur_time'];
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
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; DigiTex By Advanty X 2023 </span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
    </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>
</body>

</html>