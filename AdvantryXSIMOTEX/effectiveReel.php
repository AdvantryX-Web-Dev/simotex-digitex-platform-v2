<?php
session_start();
date_default_timezone_set('Africa/Tunis');
require_once './php/config.php';

// TEMP: enable error reporting for debugging HTTP 500
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- update d'un effective reel ---
if (isset($_POST['Bouton'])) {
    $effectiveReel = isset($_POST['effectiveReel']) ? (int)$_POST['effectiveReel'] : 0;
    $prodline = isset($_POST['prodline']) ? trim($_POST['prodline']) : '';

    if ($effectiveReel > 0 && $prodline !== '') {
        if ($updateStmt = $con->prepare("UPDATE init__prod_line SET effectif_reel = ? WHERE prod_line = ?")) {
            $updateStmt->bind_param("is", $effectiveReel, $prodline);
            if ($updateStmt->execute()) {
                if ($updateStmt->affected_rows > 0) {
                    $_SESSION['message'] = " Effectif réel mis à jour avec succès.";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "⚠️ Erreur : Chaîne introuvable.";
                    $_SESSION['message_type'] = "error";
                }
            } else {
                $_SESSION['message'] = "⚠️ Erreur : Échec de la mise à jour.";
                $_SESSION['message_type'] = "error";
            }
            $updateStmt->close();
        }
    } else {
        $_SESSION['message'] = "⚠️ Erreur : Veuillez remplir tous les champs obligatoires.";
        $_SESSION['message_type'] = "error";
    }

    header("Location: effectiveReel.php");
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
                            <h6 class="m-0 font-weight-bold text-primary">Effectif réel:</h6>
                        </div>
                        <div class="card-body">

                            <!-- Message de succès/erreur -->
                            <?php if (isset($_SESSION['message'])): ?>
                                <div class="alert alert-<?php echo ($_SESSION['message_type'] === 'success') ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                                    <?php echo $_SESSION['message']; ?>
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                                <?php unset($_SESSION['message']);
                                unset($_SESSION['message_type']); ?>
                            <?php endif; ?>

                            <form id="effectiveReelForm" action="effectiveReel.php" method="post">
                                <div class="form-group">
                                    <label for="prodline">Chaîne de production <span class="required">*</span></label>
                                    <select class="form-control form-select" name="prodline" id="prodline" required>
                                        <option value="">-- Sélectionnez une chaîne de production --</option>
                                        <?php $sql2 = "SELECT * FROM `init__prod_line`";
                                        $result2 = mysqli_query($con, $sql2);
                                        while ($row2 = mysqli_fetch_assoc($result2)) { ?>

                                            <option value="<?php echo $row2['prod_line'] ?>">
                                                <?php echo $row2['prod_line']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label for="obj1">Effectif réel <span class="required">*</span></label>
                                    <input type="number" name="effectiveReel" id="effectiveReel" class="form-control" placeholder="Entrez l'Effectif réel" min="1" required>
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
        $(document).ready(function() {
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
            if (alert) {
                alert.style.display = 'none';
            }
        }, 4000);
    </script>
</body>

</html>