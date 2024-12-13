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
                    <h1 class="h3 mt-4 text-gray-800">Ressources Humaine</h1>
                    <p class="mb-4"></p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Présence :</h6>
                            <!-- <div class="col-md-3 float-right"> -->
                            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                <h3 class="h3 mb-0 ml-4 mt-4 text-primary">
                                    <?php
                                    if (isset($_GET["prodline"])) {
                                        $prodline = $_GET["prodline"];
                                        $sql = "SELECT
                                                    `operator`,
                                                    `prod_line`,
                                                    prod__presence.`machine_id`,
                                                    CONCAT(
                                                        init__employee.first_name,
                                                        ' ',
                                                        init__employee.last_name
                                                    ) AS employee,
                                                    /* init__machine.designation, */
                                                    init__employee.qualification,
                                                    prod__presence.cur_date,
                                                    prod__presence.cur_time
                                                FROM
                                                    prod__presence
                                                INNER JOIN init__employee ON init__employee.matricule = prod__presence.operator
                                                /* INNER JOIN init__machine ON prod__presence.machine_id = init__machine.machine_id */
                                                WHERE
                                                    p_state = 1 AND prod__presence.id IN(
                                                    SELECT
                                                        MAX(id)
                                                    FROM
                                                        prod__presence
                                                    GROUP BY
                                                        operator
                                                ) AND prod__presence.`prod_line` LIKE '%$prodline%' AND prod__presence.cur_date = CURRENT_DATE;";

                                        echo $prodline == 'CH_Q' ? 'Chaine Qualité' : 'Chaine: ' . $prodline;
                                    } else {
                                        $sql = "SELECT
                                                    `operator`,
                                                    `prod_line`,
                                                    prod__presence.`machine_id`,
                                                    CONCAT(
                                                        init__employee.first_name,
                                                        ' ',
                                                        init__employee.last_name
                                                    ) AS employee,
                                                    /*init__machine.designation,*/
                                                    init__employee.qualification,
                                                    prod__presence.cur_date,
                                                    prod__presence.cur_time
                                                FROM
                                                    prod__presence
                                                INNER JOIN init__employee ON init__employee.matricule = prod__presence.operator
                                                /* INNER JOIN init__machine ON prod__presence.machine_id = init__machine.machine_id */
                                                WHERE
                                                    p_state = 1 AND prod__presence.id IN(
                                                    SELECT
                                                        MAX(id)
                                                    FROM
                                                        prod__presence
                                                    GROUP BY
                                                        operator
                                                )  AND prod__presence.cur_date = CURRENT_DATE;";

                                        echo '';
                                    }
                                    ?>
                                </h3>
                                <button
                                    class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle mt-4"
                                    type="button" id="deroulantb" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    Chaine de production
                                </button>
                                <div class="dropdown-menu" aria-labelledby="deroulantb">
                                    <a href="presence.php">
                                        <button class="dropdown-item" type="button">TOUS</button>
                                    </a>

                                    <?php
                                    $query = "SELECT prod_line FROM init__prod_line ORDER BY id ASC;";
                                    $rslt = $con->query($query);

                                    $tab4 = [];
                                    while ($item = $rslt->fetch_assoc()) {
                                        $tab4[] = $item;
                                    }
                                    ?>

                                    <?php for ($i = 0; $i < count($tab4); $i++) { ?>
                                        <a class="collapse-item" href="presence.php?prodline=<?php echo $tab4[$i]['prod_line']; ?>">
                                            <button class="dropdown-item" type="button">
                                                <?php echo $tab4[$i]['prod_line'] == 'CH_Q' ? 'Chaine Qualité' : $tab4[$i]['prod_line']; ?>
                                            </button>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Matricule</th>
                                            <th>Nom & Prénom</th>
                                            <th>Chaine de production</th>
                                            <th>Date & Heure</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $presence = mysqli_query($con, $sql);
                                        $pres = [];
                                        while ($rowp_presence = mysqli_fetch_assoc($presence)) {
                                            $pres[] = $rowp_presence;
                                        }
                                        ?>

                                        <?php for ($i = 0; $i < count($pres); $i++) { ?>
                                            <tr>
                                                <td>
                                                    <?php echo ($pres[$i]['operator']); ?>
                                                </td>
                                                <td>
                                                    <?php echo ($pres[$i]['employee']); ?>
                                                </td>
                                                <td>
                                                    <?php echo ($pres[$i]['prod_line']); ?>
                                                </td>
                                                <td>
                                                    <?php echo ('Date: ' . $pres[$i]['cur_date'] . '<br> Heure: ' . $pres[$i]['cur_time']); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
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