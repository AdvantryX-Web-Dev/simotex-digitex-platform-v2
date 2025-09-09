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
                    <h1 class="h3 mt-4 text-gray-800">Méthode </h1>
                    <p class="mb-4"></p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Equilibrage:</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Réf Modèle</th>
                                            <th>Nombre des opérations</th>
                                            <th>Nombre des OF</th>
                                            <th>Nombre des paquets</th>
                                            <th>Nombre des paquets lancés</th>
                                            <th>Opération récente</th>
                                        </tr>
                                    </thead>
                                    <!-- <tfoot>
                                        <tr>
                                            <th>Réf Modèle</th>
                                            <th>Nombre des opérations</th>
                                            <th>Nombre des OF</th>
                                            <th>Nombre des paquets</th>
                                        </tr>
                                    </tfoot> -->
                                    <tbody>

                                        <?php
                                        require_once './php/config.php';
                                        $sql = "SELECT DISTINCT `model`, `id`, `import_dt` FROM `init__model`";
                                        $rslt = mysqli_query($con, $sql);

                                        if (mysqli_num_rows($rslt) > 0) {
                                            while ($row_gamme = mysqli_fetch_assoc($rslt)) {
                                                $gamme[] = $row_gamme;
                                            }
                                        }

                                        $i = 0;
                                        $model = "";
                                        while ($i < count($gamme)) {

                                            $model = $gamme[$i]['model'];
                                            $id = $gamme[$i]['id'];
                                            $query2 = "SELECT DISTINCT `of_num`, `model_id`, `client`, `quantity`, `asm_shop`, `of_state` FROM `prod__of` WHERE `model_id`='$id'";
                                            $rsl2 = $con->query($query2);
                                            $of = [];
                                            while ($item2 = $rsl2->fetch_assoc()) {
                                                $of[] = $item2;
                                            }

                                            $query3 = "SELECT DISTINCT `operation_num`, `designation`, `model_id`  FROM `prod__gamme` WHERE `model_id`='$id';";
                                            $rsl3 = $con->query($query3);
                                            $gamme1 = [];
                                            while ($item3 = $rsl3->fetch_assoc()) {
                                                $gamme1[] = $item3;
                                            }

                                            $query4 = "SELECT DISTINCT `prod__packet`.`pack_num`, `prod__packet`.`tag_rfid`, `prod__packet`.`of_num`, `prod__packet`.`quantity`,
                                    `prod__of`.`model_id`
                                    FROM `prod__packet` 
                                    INNER JOIN `prod__of` ON `prod__packet`.`of_num`= `prod__of`.`of_num`
                                    WHERE `prod__of`.`model_id` ='$id'";
                                            $rsl4 = $con->query($query4);
                                            $pack = [];
                                            while ($item4 = $rsl4->fetch_assoc()) {
                                                $pack[] = $item4;
                                            }
                                        ?>

                                            <tr>
                                                <td><a href='gammeequilibrage.php?model_id=<?php echo ($id); ?>'>
                                                        <?php echo ($model); ?>
                                                    </a></td>
                                                <td>
                                                    <?php echo (count($gamme1)); ?>
                                                </td>
                                                <td>
                                                    <?php echo (count($of)); ?>
                                                </td>
                                                <td>
                                                    <?php echo (count($pack)); ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $model = mysqli_real_escape_string($con, $model);
                                                    $query4 = "SELECT MIN(`pack_num`) AS `pack_num` FROM `prod__pack_operation` WHERE `model`='$model' Group BY `pack_num`";
                                                    $rsl4 = $con->query($query4);
                                                    $packet = [];
                                                    while ($item4 = $rsl4->fetch_assoc()) {
                                                        $packet[] = $item4;
                                                    }
                                                    echo count($packet); ?>
                                                </td>

                                                <td>
                                                <?php $query = "SELECT MAX(CAST(operation_num AS SIGNED)) AS op_num FROM prod__pack_operation WHERE model ='$model'";
                                                $rsl = $con->query($query);
                                                $pac = [];
                                                while ($item = $rsl->fetch_assoc()) {
                                                    $pac[] = $item;
                                                }
                                                echo $pac[0]['op_num'];
                                                $i++;
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