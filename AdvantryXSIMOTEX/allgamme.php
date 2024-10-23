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

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mt-4 text-gray-800">Méthode </h1>
                    <p class="mb-4"></p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="mb-0 font-weight-bold text-primary">Gamme-Modèle:</h6>
                            <!-- <div class="mb-0 mt-2 mr-2"><a href='edit.php?TABLE=<?php //echo ("p3_gamme") 
                                                                                        ?>'><img src="./img/add-file.png" alt="icone" width="25mm" height="25mm"></a></div> -->
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <!-- <th>Modifier</th> -->
                                            <th>Réf du modéle</th>
                                            <th>Réf de l'OF</th>
                                            <th>Code de l'opération</th>
                                            <th>Designation</th>
                                            <th>Temps unitaire</th>
                                            <th>Quantité par heure</th>
                                            <th>Machine</th>
                                            <th>Digitex</th>
                                            <th>Date d'entée systéme DigiTex </th>
                                        </tr>
                                    </thead>
                                    <!-- <tfoot>
                                        <tr>
                                            <th>Edit</th>
                                            <th>Réf du modéle</th>
                                            <th>Réf de l'OF</th>
                                            <th>Numéro de Paquet</th>
                                            <th>Code de l'opération</th>
                                            <th>Designation</th>
                                            <th>Temps unitaire</th>
                                            <th>Quantité par heure</th>
                                            <th>Machine</th>
                                            <th>Digitex</th>
                                            <th>Date d'importation </th>

                                        </tr>
                                    </tfoot> -->
                                    <tbody>

                                        <?php
                                        require_once './php/config.php';
                                        $sql = "SELECT prod__gamme.`model_id`, init__model.model, prod__of.of_num, `operation_num`, `designation`, `unit_time`, `qte_h`, `machine_id`, `smartbox`, 
                                prod__gamme.`id`,
                                prod__gamme.`import_dt` 
                                FROM `prod__gamme` 
                                INNER JOIN prod__of ON prod__of.model_id= prod__gamme.model_id 
                                INNER JOIN init__model ON init__model.id= prod__gamme.model_id";
                                        $rsl = $con->query($sql);
                                        $gamme_all = [];

                                        while ($item = $rsl->fetch_assoc()) {
                                            $gamme_all[] = $item;
                                        }

                                        for ($l = 0; $l < count($gamme_all); $l++) {
                                        ?>

                                            <tr>
                                                <td>
                                                    <?php echo ($gamme_all[$l]['model']); ?>
                                                </td>
                                                <td>
                                                    <?php echo ($gamme_all[$l]['of_num']); ?>
                                                </td>
                                                <!-- <td><?php // echo ($gamme_all[$l]['pack_num']);
                                                            ?></td> -->
                                                <td>
                                                    <?php echo ($gamme_all[$l]['operation_num']); ?>
                                                </td>
                                                <td>
                                                    <?php echo ($gamme_all[$l]['designation']); ?>
                                                </td>
                                                <td>
                                                    <?php echo ($gamme_all[$l]['unit_time']); ?>
                                                </td>
                                                <td>
                                                    <?php echo ($gamme_all[$l]['qte_h']); ?>
                                                </td>
                                                <td>
                                                    <?php echo ($gamme_all[$l]['machine_id']); ?>
                                                </td>
                                                <td>
                                                    <?php echo ($gamme_all[$l]['smartbox']);
                                                    ?>
                                                </td>
                                                <td>
                                                <?php echo ($gamme_all[$l]['import_dt']);
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