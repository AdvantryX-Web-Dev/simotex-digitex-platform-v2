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
                        <?php
                        require_once './php/config.php';
                        if (isset($_GET["of_num"])) {
                            $of = $_GET["of_num"]; ?>
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Paquet de l'OF
                                    <?php echo ($of); ?> :
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Réf Paquet</th>
                                                <th>Modèle</th>
                                                <th>Tag ID</th>
                                                <th>Chaine de production</th>
                                                <th>Réf Couleur</th>
                                                <th>Quantité</th>
                                            </tr>
                                        </thead>
                                        <!-- <tfoot>
                                            <tr>
                                                <th>Réf Paquet</th>
                                                <th>Modèle</th>
                                                <th>Tag ID</th>
                                                <th>Chaine de production</th>
                                                <th>Réf Couleur</th>
                                                <th>Quantité</th>
                                            </tr>
                                        </tfoot> -->
                                        <tbody>
                                            <?php
                                            //tab paquet
                                            $query1 = "SELECT `prod__packet`.`tag_rfid`, `prod__packet`.`of_num`, `prod__packet`.`pack_num`, 
                                    `init__model`.`model`, `prod__packet`.`number`,
                                    `prod__of`.`model_id`,
                                    `prod__packet`.`lot`, `prod__packet`.`color`, `prod__packet`.`size`, `prod__packet`.`quantity`,`prod__packet`.`prod_line` 
                                    FROM `prod__packet` 
                                    INNER JOIN `prod__of` ON `prod__packet`.`of_num`= `prod__of`.`of_num` 
                                    INNER JOIN `init__model` ON `prod__of`.`model_id`= `init__model`.`id` 
                                    WHERE `prod__of`.`of_num`='$of'";
                                            $rsl1 = $con->query($query1);
                                            $pack = [];
                                            while ($item1 = $rsl1->fetch_assoc()) {
                                                $pack[] = $item1;
                                            }
                                            for ($i = 0; $i < count($pack); $i++) { ?>
                                                <tr>
                                                    <td><a href="packop.php?pack_num=<?php echo $pack[$i]['pack_num']; ?>">
                                                            <?php echo $pack[$i]['number']; ?>
                                                        </a></td>
                                                    <td><a href="gamme.php?model_id=<?php echo $pack[$i]['model_id']; ?>">
                                                            <?php echo $pack[$i]['model']; ?>
                                                        </a></td>
                                                    <td>
                                                        <?php echo $pack[$i]['tag_rfid']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $pack[$i]['prod_line']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $pack[$i]['color']; ?>
                                                    </td>
                                                    <td>
                                                <?php echo $pack[$i]['quantity'];
                                            }
                                        } ?>
                                                    </td>
                                                </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    </div>

                    <?php
                    require_once './php/config.php';
                    if (isset($_GET["matricule"])) {
                        $op = $_GET["matricule"];
                        $sql = "SELECT * FROM `init__employee` WHERE `matricule`='$op'";
                        $rslt = mysqli_query($con, $sql);
                        if (mysqli_num_rows($rslt) > 0) {
                            $row = mysqli_fetch_array($rslt);
                            $name = $row['first_name'] . " " . $row['last_name'];
                        }
                    ?>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Paquet effectué par
                                    <?php echo ($name . ' (' . $op . ') '); ?> :
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Réf Paquet</th>
                                                <th>Modèle</th>
                                                <th>Chaine de production</th>
                                                <th>Quantité</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            //tab paquet
                                            $query1 = "SELECT MIN(`prod__pack_operation`.prod_line) AS prod_line,MIN(`prod__pack_operation`.pack_num) AS pack_num,MIN(`prod__pack_operation`.model) AS model ,MIN(`prod__pack_operation`.`cur_date`) AS cur_date, MIN(`prod__pack_operation`.`pack_qty`)AS pack_qty , MIN(`init__model`.`id`) AS `model_id` FROM `prod__pack_operation`
                                    INNER JOIN `init__model` ON `prod__pack_operation`.`model`= `init__model`.`model`
                                    WHERE `prod__pack_operation`.`operator`='$op'  GROUP BY `pack_num`";
                                            $rsl1 = $con->query($query1);
                                            $pack = [];
                                            while ($item1 = $rsl1->fetch_assoc()) {
                                                $pack[] = $item1;
                                            }
                                            for ($i = 0; $i < count($pack); $i++) { ?>
                                                <tr>
                                                    <td><a href="packop.php?pack_num=<?php echo $pack[$i]['pack_num']; ?>">
                                                            <?php echo $pack[$i]['pack_num']; ?>
                                                        </a></td>
                                                    <td><a href="gamme.php?model_id=<?php echo $pack[$i]['model_id']; ?>">
                                                            <?php echo $pack[$i]['model']; ?>
                                                        </a></td>
                                                    <td>
                                                        <?php echo $pack[$i]['prod_line']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $pack[$i]['pack_qty']; ?>
                                                    </td>
                                                    <td>
                                                <?php echo $pack[$i]['cur_date'];
                                            }
                                        } ?>
                                                    </td>
                                                </tr>
                                        </tbody>
                                    </table>
                                    <?php
                                    require_once './php/config.php';
                                    if (isset($_GET["matriculej"])) {
                                        $op = $_GET["matriculej"];
                                        $sql = "SELECT * FROM `init__employee` WHERE `matricule`='$op'";
                                        $rslt = mysqli_query($con, $sql);
                                        if (mysqli_num_rows($rslt) > 0) {
                                            $row = mysqli_fetch_array($rslt);
                                            $name = $row['first_name'] . " " . $row['last_name'];
                                        }
                                    ?>
                                        <div class="card shadow mb-4">
                                            <div class="card-header py-3">
                                                <h6 class="m-0 font-weight-bold text-primary">Paquet effectué par
                                                    <?php echo ($name . ' (' . $op . ') '); ?> :
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" id="dataTable" width="100%"
                                                        cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th>Réf Paquet</th>
                                                                <th>Modèle</th>
                                                                <th>Chaine de production</th>
                                                                <th>Quantité</th>
                                                                <th>Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            //tab paquet
                                                            $query1 = "SELECT MIN(`prod__pack_operation`.prod_line) AS prod_line,MIN(`prod__pack_operation`.pack_num) AS pack_num,MIN(`prod__pack_operation`.model) AS model ,MIN(`prod__pack_operation`.`cur_date`) AS cur_date, MIN(`prod__pack_operation`.`pack_qty`)AS pack_qty , MIN(`init__model`.`id`) AS `model_id` FROM `prod__pack_operation`
                                    INNER JOIN `init__model` ON `prod__pack_operation`.`model`= `init__model`.`model`
                                    WHERE `prod__pack_operation`.`operator`='$op' AND `prod__pack_operation`.`cur_date`= CURRENT_DATE GROUP BY `pack_num`";
                                                            $rsl1 = $con->query($query1);
                                                            $pack = [];
                                                            while ($item1 = $rsl1->fetch_assoc()) {
                                                                $pack[] = $item1;
                                                            }
                                                            for ($i = 0; $i < count($pack); $i++) { ?>
                                                                <tr>
                                                                    <td><a
                                                                            href="packop.php?pack_num=<?php echo $pack[$i]['pack_num']; ?>">
                                                                            <?php echo $pack[$i]['pack_num']; ?>
                                                                        </a></td>
                                                                    <td><a
                                                                            href="gamme.php?model_id=<?php echo $pack[$i]['model_id']; ?>">
                                                                            <?php echo $pack[$i]['model']; ?>
                                                                        </a></td>
                                                                    <td>
                                                                        <?php echo $pack[$i]['prod_line']; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $pack[$i]['pack_qty']; ?>
                                                                    </td>
                                                                    <td>
                                                                <?php echo $pack[$i]['cur_date'];
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

                            </div>




                        </div>
                        <!-- End of Main Content -->

                        <!-- Footer -->
                        <footer class="sticky-footer bg-white">
                            <!-- <div class="container my-auto"> -->
                            <div class="copyright text-center my-auto">
                                <span> Copyright &copy; Advantry X <?php echo date("Y"); ?></span>
                            </div>
                            <!-- </div> -->
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