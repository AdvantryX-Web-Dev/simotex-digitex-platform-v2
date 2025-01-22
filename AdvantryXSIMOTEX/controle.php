<?php

session_start();

require_once './php/config.php';

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
                            <h6 class="m-0 font-weight-bold text-primary">Contrôle qualité bout de chaine:</h6>
                            <!-- <div class="mb-0 mt-2 mr-2"><a href='edit.php?TAB=<?php //echo ("p2_paquet") 
                                                                                    ?>'><img src="./img/add-file.png" alt="icone" width="25mm" height="25mm"></a></div> -->
                            <form action="./fichierExcel/controleQualité.php" method="post">
                                <div class="col-md-3 float-right">
                                    <button type="submit" name="submit3"
                                        class="btn btn-primary float-right">Rapport</button>
                                </div>
                                <div class="col-md-2 float-right">
                                    <select name="prod_line" class="form-control"> Chaine de Production
                                        <option>Tous</option>
                                        <?php
                                        $result = $con->query("SELECT `prod_line` FROM `init__prod_line` WHERE prod_line NOT LIKE 'CH_Q' ORDER BY id ASC");
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option>{$row['prod_line']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- 
                                <div class="col-md-2 float-right">
                                    <select name="operatrice" class="form-control"> Opératrice
                                        <option>Opératrice</option>
                                        <?php /*
                                        $result = $con->query("SELECT `matricule` FROM `init__employee` ");
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option>{$row['matricule']}</option>";
                                        }
                                        */ ?>
                                    </select>
                                </div> -->

                                <div class="col-md-2 float-right">
                                    <input type="date" name="date" class="form-control float-right">
                                </div>

                            </form>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Réf Paquet</th>
                                            <th>Ordre de fabrication</th>
                                            <!-- <th>Tag ID</th> -->
                                            <th>Chaine de production</th>
                                            <th>Quantité</th>
                                            <th>Statut</th>
                                            <th>Nombre des défauts</th>
                                            <th>Nombre des piéces défaillantes </th>
                                            <th>Défauts</th>
                                            <th>Date de controle</th>
                                            <th>Heure de controle</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php

                                        $query3 = "SELECT
                                        pp.number,
                                        /*MAX(*/ pc.`pack_num`/*)*/ AS `pack_num`,
                                        pp.`of_num`,
                                        /*MAX(*/ pc.`group`/*)*/ AS `prod_line`,
                                        /*MAX(*/ pc.`defects_num`/*)*/ AS `defects_num`,
                                        /*MAX(*/ pc.`defective_pcs`/*)*/ AS `defective_pcs`,
                                        /*MAX(*/ pc.`quantity`/*)*/ AS `quantity`,
                                        /*MAx(*/ pc.ctrl_state/*)*/ AS ctrl_state,
                                        /*MAX(*/ pc.`returned`/*)*/ AS `returned`,
                                        DATE(/*MAX(*/ pc.`created_at`/*)*/) AS cur_date,
                                        TIME(/*MAX(*/ pc.`created_at`/*)*/) AS cur_time,
                                        /*MAX(*/ 
                                            defect_designations.`designation`
                                        /*)*/ AS `designation`
                                    FROM
                                        `prod__eol_control` pc
                                    LEFT JOIN(
                                        -- SELECT `prod__eol_pack_defect`.`pack_num`,
                                        SELECT `prod__eol_pack_defect`.`eol_control_id`,
                                            GROUP_CONCAT(
                                                CONCAT(
                                                    `init__eol_defect`.`code`,
                                                    ' : ',
                                                    `prod__eol_pack_defect`.`defect_num`
                                                ) SEPARATOR '\n'
                                            ) AS `designation`
                                        FROM
                                            `prod__eol_pack_defect`
                                        LEFT JOIN `init__eol_defect` ON `prod__eol_pack_defect`.`defect_code` = `init__eol_defect`.`code`
                                        GROUP BY
                                            `prod__eol_pack_defect`.`eol_control_id`
                                    ) AS defect_designations
                                    ON
                                        -- defect_designations.`pack_num` = pc.`pack_num`
                                        defect_designations.`eol_control_id` = pc.`id`
                                    LEFT JOIN `prod__packet` pp ON
                                        pc.`pack_num` = pp.`pack_num`
                                    WHERE
                                        pc.ctrl_state = 1
                                    /*GROUP BY
                                        pc.pack_num  */
                                    ORDER BY `cur_date` DESC, pp.`of_num` ASC;";

                                        $rsl3 = $con->query($query3);
                                        $pack = [];
                                        while ($item3 = $rsl3->fetch_assoc()) {
                                            $pack[] = $item3;
                                        }

                                        for ($i = 0; $i < count($pack); $i++) {
                                        ?>
                                            <tr>
                                                <td><a href='packop.php?pack_num=<?php echo $pack[$i]['pack_num']; ?>'>
                                                        <?php echo $pack[$i]['number']; ?>
                                                    </a></td>
                                                <td><a href='pack.php?of_num=<?php echo $pack[$i]['of_num']; ?>'>
                                                        <?php echo $pack[$i]['of_num']; ?>
                                                    </a></td>
                                                <td>
                                                    <?php echo $pack[$i]['prod_line']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $pack[$i]['quantity']; ?>
                                                </td>
                                                <td>
                                                    <?php if ($pack[$i]['returned'] == 0) {
                                                        echo "<h6 class='text-success'> Validé </h6>";
                                                    } else {
                                                        echo "<h6 class='text-danger'> Retour prod </h6>";
                                                    } ?>
                                                </td>
                                                <td>
                                                    <?php echo $pack[$i]['defects_num']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $pack[$i]['defective_pcs']; ?>
                                                </td>
                                                <td>
                                                    <?php if ($pack[$i]['returned'] == 0) {
                                                        echo '';
                                                    } else {
                                                        echo nl2br($pack[$i]['designation'] ?? '');
                                                    } ?>
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
    <!-- <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
    </div> -->

    <!-- Bootstrap core JavaScript-->
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