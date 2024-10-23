<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Simotex DigiTex By Advantry X</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="img/favicon.ico" />

    <!-- Custom fonts for this template-->
    <link href="css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 CSS -->
    <link href="css/select2.min.css" rel="stylesheet" />
    <!-- Select2 JS -->
    <script src="js/select2.min.js"></script>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include("sideBare.php"); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <h1 class="h3 mb-2 text-gray-800">Méthode </h1>
                    <p class="mb-4"></p>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Objectif du jour:</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <?php
                                date_default_timezone_set('Africa/Tunis');
                                require_once './php/config.php';
                                ?>
                                <form action="objectif.php" method="post">
                                    <label for="prodline" name="chaineprod" value=""> veuillez choisir la chaine de
                                        production :</label><br>
                                    <select
                                        class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle col-xl-2 col-md-2 mb-3 ml-2"
                                        name="prodline">
                                        <option value="">-- Choisissez la chaine --</option>
                                        <?php $sql2 = "SELECT * FROM `init__prod_line`";
                                        $result2 = mysqli_query($con, $sql2);
                                        while ($row2 = mysqli_fetch_assoc($result2)) { ?>
                                            <option value1='<?php echo $row2["id"] ?>'>
                                            <?php echo $row2['prod_line'];
                                        } ?>
                                            </option>
                                    </select><br>
                                    <label for="model1" name="model" value=""> veuillez choisir le modèle :</label><br>
                                    <!-- <select
                                        class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle col-xl-2 col-md-2 mb-3 ml-2"
                                        name="model1"> -->
                                    <select class="form-select form-control " id="modelid"
                                        aria-label="Default select example" name="model1" style="width:200px" multiple>
                                        <?php
                                        // include ("php/config.php");
                                        // $sql = "SELECT * FROM `init__model`";
                                        // $req = $con->prepare($sql);
                                        // $req->execute();
                                        // $result = $req->get_result();
                                        // $models = $result->fetch_all(MYSQLI_ASSOC);
                                        // foreach ($models as $model) {
                                        //     echo "<option value='{$model['id']}'>{$model['model']}</option>";
                                        // }
                                        ?>
                                        <?php
                                        $sql1 = "SELECT * FROM `init__model`";
                                        $result1 = mysqli_query($con, $sql1);
                                        while ($row1 = mysqli_fetch_assoc($result1)) { ?>
                                            <option value1='<?php echo $row1["id"] ?>'>
                                            <?php echo $row1['model'];
                                        } ?>
                                            </option>
                                    </select><br>
                                    <label for="obj1" name="objectif" value=""> Insérer l'objectif :</label><br>
                                    <input type="number" name="obj1" class="form-control col-xl-1 col-md-2 mb-1 ml-2 "
                                        value="" min="0"><br>

                                    <!-- <label for="effectif" name="eff" value=""> Insérer nombre d'effectif :</label><br>
                                    <input type="number" name="effectif" class="form-control col-xl-1 col-md-2 mb-1 ml-2 " value=""><br> -->

                                    <input
                                        class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle col-xl-1 col-md-2 mb-3 ml-2"
                                        type="submit" name="Bouton" value="Enregistrer">
                                    <input
                                        class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle col-xl-1 col-md-2 mb-3 ml-2"
                                        type="submit" name="Bouton2" value="Supprimer">
                                </form>
                                <?php
                                //header("content-Type: application/json");


                                $obj1 = 0;
                                $model1 = '';
                                $prodline = '';
                                if (isset($_POST['Bouton'])) {
                                    if ((isset($_POST['obj1']) && !empty($_POST['obj1'])) || (isset($_POST['model1']) && !empty($_POST['model1'])) || (isset($_POST['prodline']) && !empty($_POST['prodline']))) {
                                        // $effectif = $_POST['effectif'];
                                        $obj1 = $_POST['obj1'];
                                        $model1 = $_POST['model1'];

                                        $prodline = $_POST['prodline'];
                                    } else {
                                        die("");
                                    }

                                    $query2 = "SELECT * FROM `init__prod_line`";
                                    $rslt2 = $con->query($query2);

                                    $tab2 = [];
                                    while ($item2 = $rslt2->fetch_assoc()) {
                                        $tab2[] = $item2;
                                    }

                                    $query3 = "SELECT * FROM `init__model`";
                                    $rslt3 = $con->query($query3);

                                    $tab3 = [];
                                    while ($item3 = $rslt3->fetch_assoc()) {
                                        $tab3[] = $item3;
                                    }
                                    $id1 = 0;
                                    $modelid1 = 0;
                                    for ($i = 0; $i < count($tab2); $i++) {
                                        if ($tab2[$i]['prod_line'] == $prodline) {
                                            $id1 = $tab2[$i]['id'];
                                        }
                                    }
                                    for ($i = 0; $i < count($tab3); $i++) {
                                        if ($tab3[$i]['model'] == $model1) {
                                            $modelid1 = $tab3[$i]['id'];
                                        }
                                    }
                                    if ($modelid1 != 0 && $obj1 != 0) {
                                        $sql4 = "SELECT * FROM `prod__prod_line` WHERE `model_id`=$modelid1 AND `prod_line_id`=$id1 AND `cur_date`=CURRENT_DATE";
                                        $rslt4 = $rsltt = $con->query($sql4);
                                        $tab4 = [];
                                        while ($item4 = $rslt4->fetch_assoc()) {
                                            $tab4[] = $item4;
                                        }
                                        $sql6 = "SELECT SUM(`unit_time`) AS t FROM `prod__gamme` WHERE `model_id`=$modelid1 ";
                                        $rslt6 = $rsltt = $con->query($sql6);
                                        $tab6 = [];
                                        while ($item4 = $rslt6->fetch_assoc()) {
                                            $tab6[] = $item4;
                                        }
                                        $t = $tab6[0]['t'];
                                        // $obj_percent=round(((($t*$obj1)/(540*$effectif))*100), 2) ;

                                        if (count($tab4) != 0) {
                                            $sql5 = "UPDATE `prod__prod_line`SET `objective`=$obj1 ,`remain`=null WHERE `model_id`=$modelid1 AND `prod_line_id`=$id1";
                                            $rslt5 = $con->query($sql5);
                                        } else {
                                            $sql3 = "INSERT INTO `prod__prod_line`(`prod_line_id`, `model_id`, `objective`,`remain`, `cur_date`) VALUES ($id1, $modelid1, $obj1, null, curdate())";
                                            $rsltt = $con->query($sql3);
                                        }
                                    }
                                }
                                if (isset($_POST['Bouton2'])) {
                                    if ((isset($_POST['model1']) && !empty($_POST['model1'])) || (isset($_POST['prodline']) && !empty($_POST['prodline']))) {

                                        $model1 = $_POST['model1'];
                                        $prodline = $_POST['prodline'];
                                    } else {
                                        die("");
                                    }

                                    $query2 = "SELECT * FROM `init__prod_line`";
                                    $rslt2 = $con->query($query2);

                                    $tab2 = [];
                                    while ($item2 = $rslt2->fetch_assoc()) {
                                        $tab2[] = $item2;
                                    }

                                    $query3 = "SELECT * FROM `init__model`";
                                    $rslt3 = $con->query($query3);

                                    $tab3 = [];
                                    while ($item3 = $rslt3->fetch_assoc()) {
                                        $tab3[] = $item3;
                                    }
                                    $id1 = 0;
                                    $modelid1 = 0;
                                    for ($i = 0; $i < count($tab2); $i++) {
                                        if ($tab2[$i]['prod_line'] == $prodline) {
                                            $id1 = $tab2[$i]['id'];
                                        }
                                    }
                                    for ($i = 0; $i < count($tab3); $i++) {
                                        if ($tab3[$i]['model'] == $model1) {
                                            $modelid1 = $tab3[$i]['id'];
                                        }
                                    }
                                    if ($modelid1 != 0 && $prodline != 0) {
                                        $sql4 = "SELECT * FROM `prod__prod_line` WHERE `model_id`=$modelid1 AND `prod_line_id`=$id1 AND `cur_date`=CURRENT_DATE";
                                        $rslt4 = $rsltt = $con->query($sql4);
                                        $tab4 = [];
                                        while ($item4 = $rslt4->fetch_assoc()) {
                                            $tab4[] = $item4;
                                        }
                                        if (count($tab4) != 0) {
                                            $idobj = $tab4[0]['id'];
                                            $sql5 = "DELETE FROM `prod__prod_line` WHERE `id`=$idobj";
                                            $rslt5 = $con->query($sql5);
                                        } else {
                                            die("");
                                        }
                                    }
                                } ?>
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
                        <span>Copyright &copy; Advantry X 2023</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
    <script>
        $(document).ready(function() {
            $('#modelid').select2({
                placeholder: '--Sélectionner un modèle--',
                tags: false,
                tokenSeparators: [',', ' '],
                maximumSelectionLength: 1,
                language: "fr"
            });
        });
    </script>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top"> <i class="fas fa-angle-up"></i> </a>

    <!-- Bootstrap core JavaScript-->
    <!-- <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>!-->


    <!-- <script src="js/jquery.min.js"></script> -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <!-- 

</body>

</html>