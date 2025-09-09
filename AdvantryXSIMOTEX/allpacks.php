<?php
session_start();
require_once './php/config.php';
$models = [];


// Traitement du formulaire lorsque soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialisation des variables pour les valeurs par défaut ou les valeurs du formulaire
    $packets = $_POST['paquet'] ?? '';
    $models = $_POST['model'] ?? [];

    $results = [];

    $sql = " SELECT
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
                                        po.model_id,
                                        po.lastop
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
                                   ";

    $whereClause = "";

    if (!empty($packets)) {

        $whereClause .= " AND  pp.`pack_num` ='$packets'";
    }
    if (!empty($models)) {
        $modelList = implode("','", $models);
        $whereClause .= " AND  im.`id` IN ('$modelList')";
    }

    if (!empty($whereClause)) {
        $sql .= " WHERE 1=1 $whereClause ";
    }

    $sql .= " ORDER BY `po`.`lastop`  DESC;";
    $req = $con->query($sql);

    if ($req && $req->num_rows > 0) {
        // Récupérer les résultats dans un tableau associatif
        while ($row = $req->fetch_assoc()) {
            $results[] = $row;
        }
    }
    $_SESSION['resultsPa'] = $results;
    $_SESSION['modelsPa'] = $models;

    $_SESSION['paquetP'] = $packets;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
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
    <link href="css/select2.min.css" rel="stylesheet" />


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

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mt-4 text-gray-800">Paquets </h1>
                    <p class="mb-4"></p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <?php require_once './php/config.php'; ?>
                            <h6 class="m-0 font-weight-bold text-primary"></h6>
                            <form class="d-flex flex-wrap" id="filterForm" method="POST">

                                <div class="col-md-4">
                                    <label for="model" class="form-label d-block">Modèle</label>
                                    <select id="model" name="model[]" class="form-select form-control" multiple>
                                        <?php
                                        $sql3 = "SELECT * FROM `init__model` ORDER by import_dt DESC";
                                        $result3 = mysqli_query($con, $sql3);
                                        while ($row3 = mysqli_fetch_assoc($result3)) { ?>
                                            <option value='<?php echo $row3['id'] ?>'
                                                <?php echo in_array($row3['id'],  $_SESSION['modelsPa'] ?? []) ? 'selected' : ''; ?>>
                                                <?php echo $row3['model']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="operation" class="form-label d-block">Référence Paquet</label>
                                    <input type="text" class="form-control" id="paquet" name="paquet"
                                        value="<?php echo isset($packets) ? $packets : ''; ?>">
                                </div>

                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-primary" id="submitButton">Valider</button>
                                </div>

                            </form>
                            <!-- <div class="mb-0 mt-2 mr-2"><a href='edit.php?TAB=<?php //echo ("p2_paquet")   
                                                                                    ?>'><img src="./img/add-file.png" alt="icone" width="25mm" height="25mm"></a></div> -->

                        </div>
                        <div class="card-body">
                            <?php
                            if (!empty($_SESSION['resultsPa'])) { ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <!-- <th>Modifier</th> -->
                                                <th>Réf Paquet</th>
                                                <th>Modèle</th>
                                                <th>Ordre de fabrication</th>
                                                <th>Tag ID</th>
                                                <th>Chaine de production</th>
                                                <th>Réf Couleur</th>
                                                <th>Taille</th>
                                                <th>Quantité</th>
                                                <th>Date</th>
                                                <!-- <th>Nombre des opérations effectuées</th> -->
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php foreach ($_SESSION['resultsPa'] as $row) { ?>

                                                <tr>
                                                    <!-- <td><a href='edit.php?pack_num=<?php echo ($row['pack_num']) ?>'><img
                                                            src="./img/edit.png" alt="icone" width="15mm" height="15mm"></a>
                                                </td> -->
                                                    <!-- &emsp;<a href='deleteconf.php?pack=<?php // echo ($pack[$i]['pack_num'])   
                                                                                            ?>&id=<?php // echo ($pack[$i]['id'])   
                                                                                                    ?>'><img src="./img/delete.png" alt="icone" width="15mm" height="15mm"></a></td> -->
                                                    <td><a href='packop.php?pack_num=<?php echo $row['pack_num']; ?>'>
                                                            <?php echo $row['pack_num']; ?>
                                                        </a></td>
                                                    <td><a href='gamme.php?model_id=<?php echo $row['model_id']; ?>'>
                                                            <?php echo $row['model']; ?>
                                                        </a></td>
                                                    <td><a href='pack.php?of_num=<?php echo $row['of_num']; ?>'>
                                                            <?php echo $row['of_num']; ?>
                                                        </a></td>
                                                    <td>
                                                        <?php echo $row['tag_rfid']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['prod_line']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['color']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['size']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['quantity']; ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $p = $row['pack_num'];
                                                        $sql4 = "SELECT * FROM `prod__pack_operation` Where `pack_num`='$p'
                                                ORDER BY `prod__pack_operation`.`id` DESC LIMIT 1";
                                                        $rsl4 = $con->query($sql4);
                                                        $date = [];
                                                        while ($item4 = $rsl4->fetch_assoc()) {
                                                            $date[] = $item4;
                                                        }
                                                        $j = 0;
                                                        while ($j < count($date)) {
                                                            echo '<small>' . $date[$j]['cur_date'] . '<br> H:' . $date[$j]['cur_time'] . '</small>' ?>
                                                    </td>
                                                <?php
                                                            $j++;
                                                        }

                                                ?>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } else if ($_SERVER["REQUEST_METHOD"] == "POST") { ?>
                                <div class="alert alert-warning" role="alert">
                                    Aucun résultat trouvé.
                                </div>
                            <?php } ?>
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
                        <span>Copyright &copy; Advantry X <?php echo date("Y"); ?></span>
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
    <script src="js/sb-admin-2.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                language: {
                    search: "Rechercher :",
                    lengthMenu: "Afficher _MENU_ éléments par page",
                    info: "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                    infoEmpty: "Aucun élément à afficher",
                    infoFiltered: "(filtré de _MAX_ éléments au total)",
                    zeroRecords: "Aucun enregistrement correspondant trouvé",
                    paginate: {
                        first: "Premier",
                        previous: "Précédent",
                        next: "Suivant",
                        last: "Dernier"
                    }
                }
            });

            $('#model').select2({
                placeholder: '--Sélectionner un modèle--',
                language: "fr"
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("filterForm").addEventListener("submit", function(event) {
                var packets = document.getElementById("paquet").value.trim();

                var models = document.getElementById("model").value.trim();


                // Vérifie si au moins un champ est rempli
                if (!models && !packets) {
                    event.preventDefault(); // Empêche la soumission du formulaire
                    alert("Veuillez remplir au moins un champ pour filtrer les résultats.");
                }
            });
        });
    </script>
</body>

</html>