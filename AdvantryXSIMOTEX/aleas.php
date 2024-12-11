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
                    <h1 class="h3 mt-4 text-gray-800">Ressources Humaine </h1>
                    <p class="mb-4"></p>

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
                                <h6 class="m-0 font-weight-bold text-primary">Hors Standards Pour
                                    <?php echo ($name . ' (' . $op . ') '); ?> :
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Chaine de production</th>
                                                <!-- <th>Machine</th> -->
                                                <th>SmartBox</th>
                                                <th>Demande monitrice</th>
                                                <th>Monitrice</th>
                                                <th>Heure arrivé monitrice</th>
                                                <th>Type d'arret</th>
                                                <th>Duré Hors Standards (min)</th>
                                                <th>Maintenancier</th>
                                                <th>Date & Heure Début maintenancier</th>
                                                <th>Date & Heure Fin maintenancier</th>
                                                <th>Date & Heure retour prod</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            //tab paquet
                                            $query1 = "SELECT
                                            `T`.`id` AS `id`,
                                            `T`.`operator` AS `operator`,
                                            TIMESTAMPDIFF(
                                                MINUTE,
                                                `T`.`mon_created_at`,
                                                `T`.`end_created_at`
                                            ) AS `downtime`,
                                            `T`.`created_at` AS demande_mon,
                                            `T`.`group` AS prodline,
                                            `T`.`machine_id` AS machine,
                                            `T`.`smartbox` AS smartbox,
                                            `T`.`mon_created_at` AS arrive_mon,
                                            `T`.`monitor` AS monitrice,
                                            `T`.`designation` AS type_aleas,
                                            `T`.`maint_created_at` AS arrive_maint,
                                            `T`.`maintainer` AS maintenancier,
                                            `E`.`end_maint_created_at` AS fin_maint, -- Modification ici
                                            `T`.`end_created_at` AS retour_prod
                                        FROM (
                                            SELECT
                                                `R`.`id`,
                                                `R`.`operator`,
                                                `R`.`created_at`,
                                                `R`.`group`,
                                                `R`.`machine_id`,
                                                `R`.`smartbox`,
                                                `M`.`created_at` AS `mon_created_at`,
                                                `M`.`monitor`,
                                                `T`.`designation`,
                                                `D`.`created_at` AS `maint_created_at`,
                                                `D`.`maintainer`,
                                                `E`.`end_created_at`
                                            FROM (
                                                SELECT
                                                    `id`,
                                                    `operator`,
                                                    `created_at`,
                                                    `group`,
                                                    `machine_id`,
                                                    `smartbox`
                                                FROM
                                                    `aleas__req_interv`
                                                WHERE
                                                    `operator` = $op
                                            ) AS `R`
                                            LEFT JOIN (
                                                SELECT
                                                    `req_interv_id`,
                                                    MAX(`created_at`) AS `created_at`,
                                                    MAX(`monitor`) AS `monitor`
                                                FROM
                                                    `aleas__mon_interv`
                                                GROUP BY
                                                    `req_interv_id`
                                            ) AS `M` ON `M`.`req_interv_id` = `R`.`id`
                                            LEFT JOIN (
                                                SELECT
                                                    `req_interv_id`,
                                                    MAX(`created_at`) AS `created_at`,
                                                    MAX(`maintainer`) AS `maintainer`
                                                FROM
                                                    `aleas__maint_dispo`
                                                GROUP BY
                                                    `req_interv_id`
                                            ) AS `D` ON `D`.`req_interv_id` = `R`.`id`
                                            LEFT JOIN (
                                                SELECT
                                                    `req_interv_id`,
                                                    MAX(`created_at`) AS `end_created_at`
                                                FROM
                                                    `aleas__end_mon_interv`
                                                GROUP BY
                                                    `req_interv_id`
                                            ) AS `E` ON `E`.`req_interv_id` = `R`.`id`
                                            INNER JOIN (
                                                SELECT
                                                    `id`,
                                                    `designation`
                                                FROM
                                                    `init__aleas_type`
                                            ) AS `T` ON `T`.`id` = (
                                                SELECT `aleas_type_id`
                                                FROM `aleas__mon_interv`
                                                WHERE `req_interv_id` = `R`.`id`
                                                ORDER BY `created_at` DESC
                                                LIMIT 1
                                            )
                                        ) AS `T`
                                        LEFT JOIN (
                                            SELECT
                                                `req_interv_id`,
                                                MAX(`created_at`) AS `end_maint_created_at`
                                            FROM
                                                `aleas__end_maint_interv`
                                            GROUP BY
                                                `req_interv_id`
                                        ) AS `E` ON `E`.`req_interv_id` = `T`.`id`;";
                                            $rsl1 = $con->query($query1);
                                            $pack = [];
                                            while ($item1 = $rsl1->fetch_assoc()) {
                                                $pack[] = $item1;
                                            }
                                            for ($i = 0; $i < count($pack); $i++) { ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $pack[$i]['prodline']; ?>
                                                    </td>

                                                    <td>
                                                        <?php echo $pack[$i]['smartbox']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $pack[$i]['demande_mon']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $pack[$i]['monitrice']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $pack[$i]['arrive_mon']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $pack[$i]['type_aleas']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $pack[$i]['downtime']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $pack[$i]['maintenancier']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $pack[$i]['arrive_maint']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $pack[$i]['fin_maint']; ?>
                                                    </td>
                                                    <td>
                                                <?php echo $pack[$i]['retour_prod'];
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