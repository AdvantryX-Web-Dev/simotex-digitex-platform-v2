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
                            <?php require_once './php/config.php'; ?>
                            <h6 class="m-0 font-weight-bold text-primary">Paquets :</h6>
                            <!-- <div class="mb-0 mt-2 mr-2"><a href='edit.php?TAB=<?php //echo ("p2_paquet") 
                                                                                    ?>'><img src="./img/add-file.png" alt="icone" width="25mm" height="25mm"></a></div> -->

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <!-- <th>Modifier</th> -->
                                            <th>Réf Paquet</th>
                                            <th>Modèle</th>
                                            <th>Ordre de fabrication</th>
                                            <!-- <th>Tag ID</th> -->
                                            <th>Chaine de production</th>
                                            <th>Réf Couleur</th>
                                            <th>Taille</th>
                                            <th>Quantité</th>
                                            <th>Date</th>
                                            <!-- <th>Nombre des opérations effectuées</th> -->
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php

                                        $query3 = "SELECT
                                        pp.`pack_num`,
                                        pp.`id`,
                                        pp.`of_num`,
                                        pp.`number`,
                                        im.`model`,
                                        pp.`tag_rfid`,
                                        pp.`prod_line`,
                                        pp.`color`,
                                        pp.`quantity`,
                                        pp.`size`,
                                        po.lastop,
                                        po.`model_id`
                                    FROM
                                        `prod__packet` pp
                                    INNER JOIN `prod__of` po ON pp.`of_num` = po.`of_num`
                                    INNER JOIN `init__model` im ON po.`model_id` = im.`id`
                                    LEFT JOIN (
                                        SELECT 
                                            `pack_num`,
                                            CONCAT(
                                                'D:',
                                                `cur_date`,
                                                ' ',
                                                'H:',
                                                `cur_time`
                                            ) AS lastop
                                        FROM (
                                            SELECT 
                                                `pack_num`,
                                                `cur_date`,
                                                `cur_time`,
                                                ROW_NUMBER() OVER (PARTITION BY `pack_num` ORDER BY `cur_date` DESC, `cur_time` DESC) AS rn
                                            FROM `prod__pack_operation`
                                        ) t
                                        WHERE rn = 1
                                    ) po ON pp.`pack_num` = po.`pack_num`  
                                    ORDER BY `po`.`lastop`  DESC;
                                   /* WHERE
                                        `prod__packet`.`import_dt` BETWEEN DATE_SUB(CURRENT_DATE, INTERVAL 60 DAY) AND CURRENT_DATE;*/";

                                        $rsl3 = $con->query($query3);
                                        $pack = [];
                                        while ($item3 = $rsl3->fetch_assoc()) {
                                            $pack[] = $item3;
                                        }

                                        $i = 0;
                                        $g = 0;
                                        for ($i = 0; $i < count($pack); $i++) {
                                        ?>
                                            <tr>
                                                <!-- <td><a href='edit.php?pack_num=<?php echo ($pack[$i]['pack_num']) ?>'><img
                                                            src="./img/edit.png" alt="icone" width="15mm" height="15mm"></a>
                                                </td> -->
                                                <!-- &emsp;<a href='deleteconf.php?pack=<?php // echo ($pack[$i]['pack_num']) 
                                                                                        ?>&id=<?php // echo ($pack[$i]['id']) 
                                                            ?>'><img src="./img/delete.png" alt="icone" width="15mm" height="15mm"></a></td> -->
                                                <td><a href='packop.php?pack_num=<?php echo $pack[$i]['pack_num']; ?>'>
                                                        <?php echo $pack[$i]['number']; ?>
                                                    </a></td>
                                                <td><a href='gamme.php?model_id=<?php echo $pack[$i]['model_id']; ?>'>
                                                        <?php echo $pack[$i]['model']; ?>
                                                    </a></td>
                                                <td><a href='pack.php?of_num=<?php echo $pack[$i]['of_num']; ?>'>
                                                        <?php echo $pack[$i]['of_num']; ?>
                                                    </a></td>
                                                <!-- <td>
                                                    <?php // echo $pack[$i]['tag_rfid']; 
                                                    ?>
                                                </td> -->
                                                <td>
                                                    <?php echo $pack[$i]['prod_line']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $pack[$i]['color']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $pack[$i]['size']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $pack[$i]['quantity']; ?>
                                                </td>
                                                <td>
                                                <?php
                                                echo '<small>' . $pack[$i]['lastop'] . '</small>';
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