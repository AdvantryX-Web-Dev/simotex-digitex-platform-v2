<?php

session_start();

date_default_timezone_set('Africa/Tunis');

require_once './php/config.php';

function getSelectedPOperationID(): string
{
    return filter_input(INPUT_GET, 'poperation', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
}
$pOperationIDStr = getSelectedPOperationID();
// echo $pOperationIDStr;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // var_dump($_POST);  // Debugging

        $postData = array_map('trim', $_POST);
        if (empty($postData['opn_id'])) {
            throw new InvalidArgumentException("Operation ID cannot be empty.");
        }
        $isDeleted = deletePOperation($con, (int)$pOperationIDStr);

        if ($isDeleted) {
            $_SESSION['success'] = "Enregistrement supprimé avec succès.";
        }

        header("Location: p_operation_admin.php");

        exit; // Ensure no further code is executed

    } catch (Exception $e) {
        // Handle exceptions gracefully
        error_log("Error in POST request: " . $e->getMessage());
        $_SESSION['error'] = "Error: " . $e->getMessage();

        // Redirect to p_operation_admin.php after error
        header("Location: p_operation_admin.php");
        exit; // Ensure no further code is executed
    }
}

function deletePOperation($con, $pOperationID)
{
    // Define the SQL query
    $query = "DELETE FROM prod__pack_operation WHERE id = ?;";

    // Prepare the SQL statement
    $stmt = $con->prepare($query);
    if (!$stmt) {
        throw new RuntimeException("Failed to prepare the statement: " . $con->error);
    }

    if (!$stmt->bind_param('s', $pOperationID)) {
        throw new RuntimeException("Failed to bind parameters: " . $stmt->error);
    }

    // Execute the query
    if (!$stmt->execute()) {
        throw new RuntimeException("Query execution failed: " . $stmt->error);
    }

    // Check if any rows were deleted
    if ($stmt->affected_rows === 0) {
        throw new RuntimeException("No record found with the specified ID.");
    }

    $stmt->close();

    return true; // Deletion successful
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
                    <h1 class="h3 mb-2 mt-4 text-gray-800">Modification :</h1>
                    <p class="mb-4"></p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Supprimer :</h6>
                        </div>

                        <div class="card-body">
                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?php
                                    echo htmlspecialchars($_SESSION['error']);
                                    unset($_SESSION['error']); // Clear the message
                                    ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>

                            <p>
                                Êtes-vous sûr de vouloir supprimer cet enregistrement ? Cette action ne peut être annulée.
                            </p>

                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?poperation=' . $pOperationIDStr; ?>">
                                <input type="hidden" id="opn-id" name="opn_id" value="<?php echo htmlspecialchars($pOperationIDStr ?? ''); ?>" required>

                                <button type="submit" class="btn btn-danger">Supprimer</button>
                                <a href="javascript:history.back()" class="btn btn-dark ml-1">Retour</a>
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

    <!-- Bootstrap core JavaScript-->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="js/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>