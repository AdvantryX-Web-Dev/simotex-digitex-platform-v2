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
                    <h1 class="h3 mb-2 mt-4 text-gray-800">Méthode </h1>
                    <p class="mb-4"></p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Suppression:</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <?php
                                require_once './php/config.php';
                                // récupérer l'ID de l'enregistrement à modifier à partir du paramètre GET
                                if (isset($_GET["model"]) || isset($_GET["operation_code"]) || isset($GET["pack_num"])) {
                                    $model = $_GET["model"];
                                    $opcode = $_GET["operation_code"];
                                    $idop = $_GET["idop"];

                                    // récupérer les données de l'enregistrement à partir de la base de données
                                    $sql = "SELECT * FROM `prod__gamme`
                        INNER JOIN `init__model` ON `prod__gamme`.`model_id`= `init__model`.`id` 
                         WHERE model_id='$model' AND operation_num='$opcode' AND id=$idop";
                                    $result = $con->query($sql);
                                    if ($result->num_rows > 0) {
                                        // afficher un formulaire pour modifier les données de l'enregistrement
                                        $row = $result->fetch_assoc();
                                ?>
                                        <div class="ml-2"> <?php echo ("Etes-vous sure de supprimer l'opération " . $opcode . "du modéle  " . $model . "sur le paquet  " . $pack . "?"); ?></div>
                                        <form method="post" action="delete.php">
                                            <div class="form-row">

                                                <!-- <label class="col ml-2">ID:</label> -->
                                                <input type="hidden" name="id" readonly="readonly" value="<?php echo $row['id']; ?>"><br>
                                                <input type="hidden" name="model_id" readonly="readonly" value="<?php echo $row['model_id']; ?>"><br>

                                                <label class="col ml-2"><br>Modèle:</label>
                                                <input type="text" name="model" readonly="readonly" class="form-control ml-2" value="<?php echo $row['model']; ?>"><br>

                                                <label class="col ml-2">Code de l'opération:</label>
                                                <textarea name="operation_code" readonly="readonly" class="form-control ml-2"><?php echo $row['operation_code']; ?></textarea><br>

                                                <label class="col ml-2">designation:</label>
                                                <textarea name="designation" class="form-control ml-2" readonly="readonly"><?php echo $row['designation']; ?></textarea><br>

                                                <input class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle ml-4 mb-2 mt-2" type="submit" name="Bouton" value="Supprimer">

                                            </div>
                                        </form>
                                    <?php
                                    } else { ?>
                                        <script>
                                            alert("Enregistrement non trouvé.");
                                        </script>
                                <?php
                                    }
                                } ?>
                                <?php
                                if (isset($_GET["of"])) {
                                    $of = $_GET["of"];

                                    // récupérer les données de l'enregistrement à partir de la base de données
                                    $sql = "SELECT * FROM `prod__of` WHERE of_num='$of'";
                                    $result = $con->query($sql);
                                    if ($result->num_rows > 0) {
                                        // afficher un formulaire pour modifier les données de l'enregistrement
                                        $row = $result->fetch_assoc();
                                ?>
                                        <div class="ml-2"> <?php echo ("Etes-vous sure de supprimer l'ordre de fabrication référencié " . $of . "?"); ?></div>
                                        <form method="post" action="delete.php">
                                            <div class="form-row">

                                                <!-- <label class="col ml-2">ID:</label> -->
                                                <input type="hidden" name="id" readonly="readonly" value="<?php echo $row['id']; ?>"><br>

                                                <label class="col ml-2">Réference de l'OF:</label>
                                                <input type="text" name="of_num" class="form-control ml-2" readonly="readonly" value="<?php echo $row['of_num']; ?>"><br>

                                                <label class="col ml-2">Client:</label>
                                                <textarea name="client" class="form-control ml-2" readonly="readonly"><?php echo $row['client']; ?></textarea><br>

                                                <label class="col ml-2">Atelier:</label>
                                                <textarea name="asm_shop" readonly="readonly" class="form-control ml-2"><?php echo $row['asm_shop']; ?></textarea><br>

                                                <label class="col ml-2">Statut de l'OF :</label>
                                                <textarea name="of_state" class="form-control ml-2" readonly="readonly"><?php echo $row['of_state']; ?></textarea><br>

                                                <label class="col ml-2">Statut de l'OF :</label>
                                                <textarea name="of_state" class="form-control ml-2" readonly="readonly"><?php echo $row['of_state']; ?></textarea><br>

                                                <input class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle ml-4 mb-2 mt-2" type="submit" name="BoutonOf" value="Supprimer">

                                            </div>
                                        </form>
                                    <?php
                                    } else { ?>
                                        <script>
                                            alert("Enregistrement non trouvé.");
                                        </script>
                                <?php
                                    }
                                } ?>
                                <?php
                                if (isset($_GET["pack"]) || isset($_GET["id"])) {
                                    $pack = $_GET["pack"];
                                    $id = $_GET["id"];

                                    // récupérer les données de l'enregistrement à partir de la base de données
                                    $sql = "SELECT * FROM `prod__packet` WHERE pack_num='$pack' AND id='$id'";
                                    $result = $con->query($sql);
                                    if ($result->num_rows > 0) {
                                        // afficher un formulaire pour modifier les données de l'enregistrement
                                        $row = $result->fetch_assoc();
                                ?>
                                        <div class="ml-2"> <?php echo ("Etes-vous sure de supprimer le paquet numéro: " . $pack . "?"); ?></div>
                                        <form method="post" action="delete.php">
                                            <div class="form-row">

                                                <!-- <label class="col ml-2">ID:</label> -->
                                                <input type="hidden" name="id" readonly="readonly" value="<?php echo $row['id']; ?>"><br>

                                                <label class="col ml-2">Numéro de paquet:</label>
                                                <input type="text" name="pack_num" readonly="readonly" class="form-control ml-2" value="<?php echo $row['pack_num']; ?>"></input><br>

                                                <label class="col ml-2">Référence de l'Of:</label>
                                                <input type="text" name="of_num" class="form-control ml-2" value="<?php echo $row['of_num']; ?>"></input><br>

                                                <label class="col ml-2">Tag:</label>
                                                <input type="text" name="tag_rfid" class="form-control ml-2" readonly="readonly" value="<?php if ($row['tag_rfid'] != '') {
                                                                                                                                            echo $row['tag_rfid'];
                                                                                                                                        } else {
                                                                                                                                            echo ('');
                                                                                                                                        } ?>"></input><br>

                                                <label class="col ml-2">Chaine de production:</label>
                                                <input name="prod_line" class="form-control ml-2" value="<?php echo $row['prod_line']; ?>"></input><br>

                                                <label class="col ml-2">Taille:</label>
                                                <input name="size" class="form-control ml-2" value="<?php echo $row['size']; ?>"></input><br>

                                                <label class="col ml-2">Référence couleur:</label>
                                                <input name="color" class="form-control ml-2" value="<?php echo $row['color']; ?>"></input><br>

                                                <label class="col ml-2">Quanitité:</label>
                                                <input name="qte" class="form-control ml-2" value="<?php echo $row['quantity']; ?>"></input><br>

                                                <input class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle ml-4 mb-2 mt-2" type="submit" name="Boutonpack" value="Supprimer">

                                            </div>
                                        </form>
                                    <?php
                                    } else { ?>
                                        <script>
                                            alert("Enregistrement non trouvé.");
                                        </script>
                                <?php
                                    }
                                } ?>

                                <?php
                                if (isset($_GET["operatrice"])) {
                                    $operatrice = $_GET["operatrice"];

                                    // récupérer les données de l'enregistrement à partir de la base de données
                                    $sql = "SELECT * FROM `init__employee` WHERE matricule='$operatrice'";
                                    $result = $con->query($sql);
                                    if ($result->num_rows > 0) {
                                        // afficher un formulaire pour modifier les données de l'enregistrement
                                        $row = $result->fetch_assoc();
                                ?>
                                        <div class="ml-2"> <?php echo ("Etes-vous sure de supprimer l'opératrice " . $row['first_name'] . $row['last_name'] . "?"); ?></div>
                                        <form method="post" action="delete.php">
                                            <div class="form-row">

                                                <!-- <label class="col ml-2">ID:</label> -->
                                                <input type="hidden" name="id" readonly="readonly" value="<?php echo $row['id']; ?>"><br>

                                                <label class="col ml-2">Matricule:</label>
                                                <input type="text" name="reg_num" class="form-control ml-2" readonly="readonly" value="<?php echo $row['matricule']; ?>"><br>

                                                <label class="col ml-2">Nom & Prénom:</label>
                                                <textarea name="full_name" class="form-control ml-2" readonly="readonly"><?php echo $row['first_name'] . $row['last_name']; ?></textarea><br>

                                                <label class="col ml-2">Carte RFID:</label>
                                                <textarea name="card_id" readonly="readonly" class="form-control ml-2"><?php echo $row['card_rfid']; ?></textarea><br>

                                                <input class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle ml-4 mb-2 mt-2" type="submit" name="Boutonoperat" value="Supprimer">

                                            </div>
                                        </form>
                                    <?php
                                    } else { ?>
                                        <script>
                                            alert("Enregistrement non trouvé.");
                                        </script>
                                <?php
                                    }
                                } ?>
                                <?php
                                require_once './php/config.php';
                                // récupérer l'ID de l'enregistrement à modifier à partir du paramètre GET
                                if (isset($_GET["modele_id"]) || isset($_GET["operationcode"]) || isset($_GET["id"])) {
                                    $model = $_GET["modele_id"];
                                    $opcode = $_GET["operationcode"];
                                    $id = $_GET["idop"];

                                    // récupérer les données de l'enregistrement à partir de la base de données
                                    $sql = "SELECT `prod__gamme`.`operation_num`, `prod__gamme`.`designation`, `prod__gamme`.`unit_time`, `prod__gamme`.`qte_h`, `prod__gamme`.`smartbox`, `prod__gamme`.`main_sb`, `prod__gamme`.`model_id`, `prod__gamme`.`id`, init__model.model, `machine_id`
                        FROM `prod__gamme` INNER JOIN `init__model` ON prod__gamme.model_id= init__model.id
                        WHERE `prod__gamme`.`model_id`=$model AND `prod__gamme`.`operation_num` LIKE '%$opcode%' AND `prod__gamme`.`id` =$id ";
                                    $result = $con->query($sql);
                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                ?>
                                        <form method="post" action="delete.php">
                                            <div class="form-row">

                                                <!-- <label>ID:</label> -->
                                                <input type="hidden" name="id" readonly="readonly" value="<?php echo $row['id']; ?>"><br>

                                                <label class="col ml-2">modèle:</label>
                                                <input type="text" name="model" readonly="readonly" class="form-control ml-2" value="<?php echo $row['model']; ?>"><br>

                                                <label class="col ml-2">Code de l'opération:</label>
                                                <textarea name="operation_num" readonly="readonly" class="form-control ml-2"><?php echo $row['operation_num']; ?></textarea><br>

                                                <label class="col ml-2">Designation:</label>
                                                <textarea name="designation" readonly="readonly" class="form-control ml-2"><?php echo $row['designation']; ?></textarea><br>

                                                <label class="col ml-2">Temps unitaire:</label>
                                                <textarea name="unit_time" readonly="readonly" class="form-control ml-2"><?php echo $row['unit_time']; ?></textarea><br>

                                                <label class="col ml-2">Quantité par heure:</label>
                                                <textarea name="qte_h" readonly="readonly" class="form-control ml-2"><?php echo $row['qte_h']; ?></textarea><br>

                                                <label class="col ml-2">Référence machine:</label>
                                                <textarea name="reference" readonly="readonly" class="form-control ml-2"><?php echo $row['machine_id']; ?></textarea><br>

                                                <label class="col ml-2">DigiTex:</label>
                                                <textarea name="smartbox" readonly="readonly" class="form-control ml-2"><?php echo $row['smartbox']; ?></textarea><br>

                                                <input class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle ml-4 mb-2 mt-2" type="submit" name="Supoperation" value="Supprimer">

                                            </div>
                                        </form>
                                    <?php
                                    } else { ?>
                                        <script>
                                            alert("Enregistrement non trouvé.");
                                        </script>
                                <?php
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