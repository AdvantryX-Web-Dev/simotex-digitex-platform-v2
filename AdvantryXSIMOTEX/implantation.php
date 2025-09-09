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
                    <h1 class="h3 mt-4 text-gray-800">Maintenance </h1>
                    <p class="mb-4"></p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Position Box:</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Chaine de production</th>
                                            <!-- <th>Machine</th>
                                            <th>Designation machine</th> -->
                                            <th>DigiTex</th>
                                            <th>Poste</th>
                                            <th>Date & Heure</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        require_once './php/config.php';
                                        // require_once './php/config_maint.php';
                                        $sql = "SELECT
                                        `prod__implantation`.`prod_line`,
                                        `prod__implantation`.`machine_id`,
                                        `init__machine`.`designation`,
                                        `prod__implantation`.`smartbox`,
                                        `init__smartbox`.`position`,
                                        `prod__implantation`.`cur_date`,
                                        `prod__implantation`.`cur_time`
                                    FROM
                                        `prod__implantation`
                                    /*INNER JOIN `init__prod_line` ON `prod__implantation`.`prod_line_id` = `init__prod_line`.`id` */
                                    INNER JOIN `init__smartbox` ON `prod__implantation`.`smartbox` = `init__smartbox`.`smartbox`
                                    INNER JOIN `init__machine` ON `init__machine`.`machine_id` = `prod__implantation`.`machine_id`;";
                                        $presence = mysqli_query($con, $sql);
                                        $pres = [];
                                        while ($item1 = $presence->fetch_assoc()) {
                                            $pres[] = $item1;
                                        }
                                        for ($i = 0; $i < count($pres); $i++) { ?>
                                            <tr>
                                                <td>
                                                    <?php echo ($pres[$i]['prod_line']); ?>
                                                </td>
                                                <!-- <td>
                                                    <?php //echo ($pres[$i]['machine_id']); 
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php //echo ($pres[$i]['designation']); 
                                                    ?>
                                                </td> -->
                                                <td>
                                                    <?php echo ($pres[$i]['smartbox']); ?>
                                                </td>
                                                <td>
                                                    <?php echo ($pres[$i]['position']); ?>
                                                </td>
                                                <td>
                                                <?php echo 'D: ' . $pres[$i]['cur_date'] . ' H: ' . $pres[$i]['cur_time'];
                                            } ?>
                                                </td>
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