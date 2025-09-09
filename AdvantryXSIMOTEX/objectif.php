<?php
session_start();
date_default_timezone_set('Africa/Tunis');
require_once './php/config.php';

// --- Enregistrement d'un objectif ---
if (isset($_POST['Bouton'])) {
    $obj1 = $_POST['obj1'] ?? 0;
    $model1 = $_POST['model1'] ?? '';
    $prodline = $_POST['prodline'] ?? '';

    if ($obj1 > 0 && $model1 !== '' && $prodline !== '') {
        // Récupérer les IDs
        $resLine = $con->query("SELECT id FROM init__prod_line WHERE prod_line='$prodline' LIMIT 1");
        $resModel = $con->query("SELECT id FROM init__model WHERE model='$model1' LIMIT 1");

        if ($resLine->num_rows > 0 && $resModel->num_rows > 0) {
            $idLine = $resLine->fetch_assoc()['id'];
            $idModel = $resModel->fetch_assoc()['id'];

            $check = $con->query("SELECT id FROM prod__prod_line 
                                  WHERE model_id=$idModel AND prod_line_id=$idLine 
                                  AND cur_date=CURDATE()");

            if ($check->num_rows > 0) {
                // Update
                $con->query("UPDATE prod__prod_line 
                             SET objective=$obj1, remain=NULL 
                             WHERE model_id=$idModel AND prod_line_id=$idLine 
                             AND cur_date=CURDATE()");
                $_SESSION['message'] = " Objectif mis à jour avec succès.";
                $_SESSION['message_type'] = "success";
            } else {
                // Insert
                $con->query("INSERT INTO prod__prod_line (prod_line_id, model_id, objective, remain, cur_date) 
                             VALUES ($idLine, $idModel, $obj1, NULL, CURDATE())");
                $_SESSION['message'] = " Nouvel objectif enregistré avec succès.";
                $_SESSION['message_type'] = "success";
            }
        } else {
            $_SESSION['message'] = "⚠️ Erreur : Modèle ou chaîne introuvable.";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "⚠️ Erreur : Veuillez remplir tous les champs obligatoires.";
        $_SESSION['message_type'] = "error";
    }

    header("Location: objectif.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SIMOTEX | DigiTex By Advantry X</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico" />
    <link href="css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/select2.min.css" rel="stylesheet" />
    <style>
        th, td { white-space: nowrap; }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include ("sideBare.php") ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <h1 class="h3 mb-2 text-gray-800">Méthode</h1>
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

                            <!-- Message de succès/erreur -->
                            <?php if (isset($_SESSION['message'])): ?>
                                <div class="alert alert-<?php echo ($_SESSION['message_type'] === 'success') ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                                    <?php echo $_SESSION['message']; ?>
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                                <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                            <?php endif; ?>

                            <form id="objectifForm" action="objectif.php" method="post">
                                <div class="form-group">
                                    <label for="prodline">Chaîne de production <span class="required">*</span></label>
                                    <select class="form-control form-select" name="prodline" id="prodline" required>
                                        <option value="">-- Sélectionnez une chaîne de production --</option>
                                        <?php 
                                        $sql2 = "SELECT * FROM `init__prod_line`";
                                        $result2 = mysqli_query($con, $sql2);
                                        while ($row2 = mysqli_fetch_assoc($result2)) { ?>
                                            <option value="<?php echo $row2['prod_line'] ?>">
                                                <?php echo $row2['prod_line']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="model1">Modèle <span class="required">*</span></label>
                                    <select class="form-control form-select" id="modelid" name="model1" required>
                                        <option value="">-- Sélectionnez un modèle --</option>
                                        <?php
                                        $sql1 = "SELECT * FROM `init__model`";
                                        $result1 = mysqli_query($con, $sql1);
                                        while ($row1 = mysqli_fetch_assoc($result1)) { ?>
                                            <option value="<?php echo $row1['model'] ?>">
                                                <?php echo $row1['model']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="obj1">Objectif de production <span class="required">*</span></label>
                                    <input type="number" name="obj1" id="obj1" class="form-control" placeholder="Entrez l'objectif de production" min="1" required>
                                </div>

                                <input class="btn btn-primary btn-sm" type="submit" name="Bouton" value="Enregistrer">
                            </form>

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
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>
    <script src="js/datatables-demo.js"></script>
    <script src="js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#modelid').select2({
                placeholder: '--Sélectionner un modèle--',
                tags: false,
                allowClear: true,
                language: "fr"
            });
        });

        // Disparition automatique du message après 4s
        setTimeout(() => {
            let alert = document.querySelector('.alert');
            if(alert){
                alert.style.display = 'none';
            }
        }, 4000);
    </script>
</body>
</html>
