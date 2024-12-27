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

// Handle POST request for update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // var_dump($_POST);  // Debugging

        $postData = array_map('trim', $_POST);
        if (empty($postData['opn_id'])) {
            throw new InvalidArgumentException("Operation ID cannot be empty.");
        }
        $rowsUpdated = updatePOperation($con, [
            'id' => $postData['opn_id'],
            'model' => $postData['opn_model'],
            'client' => $postData['opn_client'],
            'of_num' => $postData['opn_of'],
            'pack_num' => $postData['opn_pack_num'],
            'operation_num' => $postData['opn_num'],
            'opn_code' => $postData['opn_code'],
            'designation' => $postData['opn_designation'],
            'unit_time' => $postData['opn_unit_time'],
            'pack_qty' => $postData['opn_pack_qty'],
            'operator' => $postData['opn_operator'],
            'prod_line' => $postData['opn_prodline'],
            'smartbox' => $postData['opn_smartbox'],
            'cur_date' => $postData['opn_date'],
            'cur_time' => $postData['opn_time']
        ]);

        if ($rowsUpdated > 0) {
            $_SESSION['success'] = "Enregistrement mis à jour avec succès.";
        } else {
            $_SESSION['warning'] = "Aucune modification n'a été apportée à l'enregistrement.";
        }

        // Redirect to the same page after successful post to avoid resubmission
        header("Location: " . $_SERVER['PHP_SELF'] . '?poperation=' . $pOperationIDStr);
        exit; // Ensure no further code is executed

    } catch (Exception $e) {
        // Handle exceptions gracefully
        error_log("Error in POST request: " . $e->getMessage());
        $_SESSION['error'] = "Error: " . $e->getMessage();

        // Redirect to the same page after successful post to avoid resubmission
        header("Location: " . $_SERVER['PHP_SELF'] . '?poperation=' . $pOperationIDStr);
        exit; // Ensure no further code is executed
    }
}

function getPOperation($con, $pOperationID)
{
    try {
        // Validate inputs
        if (empty($pOperationID)) {
            throw new InvalidArgumentException("Operation ID cannot be empty.");
        }

        // Define the SQL query
        $query = "SELECT id,
                model,
                client,
                of_num,
                pack_num,
                operation_num,
                opn_code,
                designation,
                unit_time,
                pack_qty,
                operator,
                prod_line,
                smartbox,
                cur_date,
                cur_time
            FROM prod__pack_operation
            WHERE id = ?;";

        // Prepare the SQL statement
        $stmt = $con->prepare($query);
        if (!$stmt) {
            throw new RuntimeException("Failed to prepare the statement: " . $con->error);
        }

        // Bind the parameter to the query
        if (!$stmt->bind_param('s', $pOperationID)) {
            throw new RuntimeException("Failed to bind parameters: " . $stmt->error);
        }

        // Execute the query
        if (!$stmt->execute()) {
            throw new RuntimeException("Query execution failed: " . $stmt->error);
        }

        // Fetch the result
        $result = $stmt->get_result();
        if (!$result) {
            throw new RuntimeException("Failed to retrieve the result: " . $stmt->error);
        }

        $row = $result->fetch_assoc();

        // Check if the record exists
        if (!$row) {
            throw new RuntimeException("No record found for the provided Operation ID.");
        }

        // Return the fetched result
        return $row;
    } catch (Exception $e) {
        // Handle exceptions gracefully
        error_log("Error in getPOperation: " . $e->getMessage());
        $_SESSION['error'] = "Error: " . $e->getMessage();

        return null; // Or rethrow the exception if needed
    } finally {
        // Clean up the statement
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}
$pOperationData = getPOperation($con, (int)$pOperationIDStr);
// var_dump($pOperationData);  // Debugging

function updatePOperation($con, $pOperationUpdateData)
{
    $fields = [];
    $values = [];
    foreach ($pOperationUpdateData as $key => $value) {
        $value = trim($value); // Trim spaces just in case
        if ($key !== 'id') {
            $fields[] = $key . ' = ?';
            $values[] = $value;
        }
    }
    $values[] = $pOperationUpdateData['id']; // ID for WHERE clause

    $setClause = implode(', ', $fields);
    // echo $setClause;  // Debugging

    $query = "UPDATE prod__pack_operation SET $setClause WHERE id = ?;";

    $stmt = $con->prepare($query);
    if (!$stmt) {
        throw new RuntimeException("Query preparation failed: " . $con->error);
    }

    $stmt->bind_param(str_repeat('s', count($values)), ...$values);

    if (!$stmt->execute()) {
        throw new RuntimeException("Update failed: " . $stmt->error);
    }

    $rowsUpdated = $stmt->affected_rows;
    $stmt->close();

    return $rowsUpdated;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Maille Club | DigiTex By Advantry X</title>

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
                            <h6 class="m-0 font-weight-bold text-primary">Editer :</h6>
                        </div>

                        <div class="card-body">
                            <!-- Display the message -->
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <?php
                                    echo htmlspecialchars($_SESSION['success']);
                                    unset($_SESSION['success']); // Clear the message
                                    ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['warning'])): ?>
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <?php
                                    echo htmlspecialchars($_SESSION['warning']);
                                    unset($_SESSION['warning']); // Clear the message
                                    ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>

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

                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?poperation=' . $pOperationIDStr; ?>">
                                <input type="hidden" id="opn-id" name="opn_id" value="<?php echo htmlspecialchars($pOperationData['id'] ?? ''); ?>" required>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="opn-model">Réf Modèle :</label>
                                        <input type="text" class="form-control" id="opn-model" name="opn_model" value="<?php echo htmlspecialchars($pOperationData['model'] ?? ''); ?>" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="opn-client">Client :</label>
                                        <input type="text" class="form-control" id="opn-client" name="opn_client" value="<?php echo htmlspecialchars($pOperationData['client'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="opn-of">Réf de l'OF :</label>
                                        <input type="text" class="form-control" id="opn-of" name="opn_of" value="<?php echo htmlspecialchars($pOperationData['of_num'] ?? ''); ?>" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="opn-prodline">Chaîne :</label>
                                        <input type="text" class="form-control" id="opn-prodline" name="opn_prodline" value="<?php echo htmlspecialchars($pOperationData['prod_line'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="opn-pack-num">Numéro de paquet :</label>
                                        <input type="text" class="form-control" id="opn-pack-num" name="opn_pack_num" value="<?php echo htmlspecialchars($pOperationData['pack_num'] ?? ''); ?>" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="opn-pack-qty">Quantité de paquet :</label>
                                        <input type="text" class="form-control" id="opn-pack-qty" name="opn_pack_qty" value="<?php echo htmlspecialchars($pOperationData['pack_qty'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="opn-num">Numéro d'opération :</label>
                                        <input type="text" class="form-control" id="opn-num" name="opn_num" value="<?php echo htmlspecialchars($pOperationData['operation_num'] ?? ''); ?>" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="opn-code">Code d'opération :</label>
                                        <input type="text" class="form-control" id="opn-code" name="opn_code" value="<?php echo htmlspecialchars($pOperationData['opn_code'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="opn-designation">Désignation d'opération :</label>
                                        <input type="text" class="form-control" id="opn-designation" name="opn_designation" value="<?php echo htmlspecialchars($pOperationData['designation'] ?? ''); ?>" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="opn-unit-time">Temps d'opération :</label>
                                        <input type="text" class="form-control" id="opn-unit-time" name="opn_unit_time" value="<?php echo htmlspecialchars($pOperationData['unit_time'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="opn-operator">Matricule de l'opératrice :</label>
                                        <input type="text" class="form-control" id="opn-operator" name="opn_operator" value="<?php echo htmlspecialchars($pOperationData['operator'] ?? ''); ?>" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="opn-smartbox">Smartbox :</label>
                                        <input type="text" class="form-control" id="opn-smartbox" name="opn_smartbox" value="<?php echo htmlspecialchars($pOperationData['smartbox'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="opn-date">Date :</label>
                                        <input type="date" class="form-control" id="opn-date" name="opn_date" value="<?php echo htmlspecialchars($pOperationData['cur_date'] ?? ''); ?>" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="opn-time">Heure :</label>
                                        <input type="time" class="form-control" id="opn-time" name="opn_time" value="<?php echo htmlspecialchars($pOperationData['cur_time'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                <a href="p_operation.php" class="btn btn-dark ml-1">Retour</a>
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