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
                    <h1 class="h3 mt-4 text-gray-800">Contrôle qualité </h1>
                    <p class="mb-4"></p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <?php require_once './php/config.php'; ?>
                            <h6 class="m-0 font-weight-bold text-primary">Contrôle qualité sur chaine:</h6>
                            <!-- <div class="mb-0 mt-2 mr-2"><a href='edit.php?TAB=<?php //echo ("p2_paquet") 
                                                                                    ?>'><img src="./img/add-file.png" alt="icone" width="25mm" height="25mm"></a></div> -->

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Modéle</th>
                                            <th>Réf OF</th>
                                            <th>Réf Paquet</th>
                                            <th>Quantité par paquet</th>
                                            <th>Code opération</th>
                                            <th>Designation</th>
                                            <th>Quantité par prélevement</th>
                                            <th>Nombre des piéces défaillantes </th>
                                            <th>Opératrice</th>
                                            <th>Controlleuse</th>
                                            <th>Date</th>
                                            <th>Heure</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php

                                        $query3 = "SELECT
                                        `init__model`.`model`,
                                        `prod__of`.`model_id`,
                                        `prod__packet`.`of_num`,
                                        `prod__packet`.`pack_num`,
                                        `prod__packet`.`number`,
                                        `prod__on_chain_control`.`operation_num`,
                                        `prod__on_chain_control`.`designation`,
                                        CONCAT(t1.first_name, ' ', t1.last_name) AS controller,
                                        CONCAT(t2.first_name, ' ', t2.last_name) AS operator,
                                        `prod__on_chain_control`.`pack_qty`,
                                        `prod__on_chain_control`.`sample_qty`,
                                        `prod__on_chain_control`.`defective_pcs`,
                                        `prod__on_chain_control`.`cur_date`,
                                        `prod__on_chain_control`.`cur_time`
                                    FROM
                                        `prod__on_chain_control`
                                    INNER JOIN `init__employee` t1 ON
                                        `prod__on_chain_control`.`controller` = t1.`matricule`
                                    INNER JOIN `init__employee` t2 ON
                                        t2.`matricule` = `prod__on_chain_control`.`operator`
                                    INNER JOIN `prod__packet` ON `prod__packet`.`pack_num` = `prod__on_chain_control`.`pack_num`
                                    INNER JOIN `prod__of` ON `prod__of`.`of_num` = `prod__packet`.`of_num`
                                    INNER JOIN `init__model` ON `prod__of`.`model_id` = `init__model`.`id`;";

                                        $rsl3 = $con->query($query3);
                                        $pack = [];
                                        while ($item3 = $rsl3->fetch_assoc()) {
                                            $pack[] = $item3;
                                        }

                                        for ($i = 0; $i < count($pack); $i++) {
                                        ?>
                                            <tr>
                                                <td><a href='gamme.php?model_id=<?php echo $pack[$i]['model_id']; ?>'>
                                                        <?php echo $pack[$i]['model']; ?>
                                                    </a></td>
                                                <td><a href='pack.php?of_num=<?php echo $pack[$i]['of_num']; ?>'>
                                                        <?php echo $pack[$i]['of_num']; ?>
                                                    </a></td>
                                                <td><a href='packop.php?pack_num=<?php echo $pack[$i]['pack_num']; ?>'>
                                                        <?php echo $pack[$i]['number']; ?>
                                                    </a></td>
                                                <td>
                                                    <?php echo $pack[$i]['pack_qty']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $pack[$i]['operation_num']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $pack[$i]['designation']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $pack[$i]['sample_qty']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $pack[$i]['defective_pcs']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $pack[$i]['operator']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $pack[$i]['controller']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $pack[$i]['cur_date']; ?>
                                                </td>
                                                <td>
                                                <?php echo $pack[$i]['cur_time'];
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