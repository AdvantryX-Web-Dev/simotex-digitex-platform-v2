<?php

date_default_timezone_set('Africa/Tunis');

require_once './php/config.php';

// This function retrieves and returns the 'prod_line' parameter from the URL query string
function getSelectedProdline(): string
{
    // Use filter_input to safely get the 'prod_line' parameter from the GET request
    // FILTER_SANITIZE_SPECIAL_CHARS is used to convert special characters to HTML entities
    // This helps prevent XSS attacks by sanitizing the input
    return filter_input(INPUT_GET, 'prod_line', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
    // If the 'prod_line' parameter is not set, return an empty string
}
$prodline = getSelectedProdline();
// echo $prodline;

function getPresentOperators($con, $prodline): int
{
    // SQL query to count the number of operators present on the specified
    // production line for the current date
    $sql = "SELECT 
                COUNT(*) AS present_operators 
            FROM 
                prod__presence 
            WHERE 
                p_state = 1 
                AND cur_date = CURRENT_DATE 
                AND prod_line = ? 
                AND id IN (
                    SELECT 
                        MAX(id) 
                    FROM 
                        prod__presence 
                    WHERE 
                        prod_line = ? 
                    GROUP BY 
                        operator
                )";

    // Prepare the SQL statement
    $stmt = $con->prepare($sql);

    // Check if statement preparation is successful
    if (!$stmt) {
        // Log an error message if preparation failed
        error_log("Database statement preparation failed: " . $con->error);
        return 0; // Return 0 or an appropriate value on error
    }

    // Bind the production line parameter to the SQL query
    $stmt->bind_param('ss', $prodline, $prodline);

    // Execute the prepared statement
    $stmt->execute();

    // Get the result set from the executed query
    $result = $stmt->get_result();

    // Check if any rows are returned
    if ($result->num_rows === 0) {
        return 0; // Return 0 or an appropriate value if no rows are found
    }

    // Fetch the associative array from the result set
    $row = $result->fetch_assoc();

    // Return the number of present operators
    return $row['present_operators'] ?? 0;
}
$presentOperators = getPresentOperators($con, $prodline);
// echo $presentOperators;

function calculateObjective($con, $prodline): array
{
    // Query to get the latest rendement_objectif and temps_de_gamme
    $query = "SELECT 
                `prod__prod_line`.`objective`, 
                -- `init__prod_line`.`prod_line`, 
                `init__model`.`model` 
            FROM 
                `prod__prod_line` 
            INNER JOIN `init__prod_line` ON `prod__prod_line`.`prod_line_id` = `init__prod_line`.`id` 
            INNER JOIN `init__model` ON `init__model`.`id` = `prod__prod_line`.`model_id` 
            WHERE 
                `prod__prod_line`.`cur_date` = CURDATE()
                AND `init__prod_line`.`prod_line` = ? 
            ORDER BY 
                `prod__prod_line`.`id` DESC;";

    // Prepare the query
    $stmt = $con->prepare($query);
    if (!$stmt) {
        return [];
    }

    // Bind parameters and execute
    $stmt->bind_param("s", $prodline);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all rows
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'model' => $row['model'] ?? null,
            'objective' => (int) round($row['objective'] ?? 0)
        ];
    }

    // Return rows if data exists; otherwise, return default
    return !empty($data) ? $data : [];
}
$objData = calculateObjective($con, $prodline);

function getEngagedQuantity($con, $prodline): int
{
    // SQL query to calculate the total engaged quantity for the current date and specified production line
    $sql = "SELECT 
                SUM(subquery.pack_qty) AS total_pack_qty 
            FROM 
                (
                    SELECT 
                        MAX(pack_qty) AS pack_qty 
                    FROM 
                        prod__pack_operation 
                    WHERE 
                        cur_date = CURRENT_DATE  -- Filter records for the current date
                        AND prod_line = ?        -- Filter by the specified production line
                        AND pack_num NOT IN (
                            SELECT 
                                pack_num 
                            FROM 
                                prod__pack_operation 
                            WHERE 
                                cur_date < CURRENT_DATE  -- Exclude pack numbers from previous dates
                                AND prod_line = ?        -- Ensure they belong to the same production line
                            GROUP BY 
                                pack_num
                        ) 
                    GROUP BY 
                        pack_num  -- Group by pack number and select the maximum quantity for each
                ) as subquery;";

    // Prepare the SQL statement
    $stmt = $con->prepare($sql);

    // Check if statement preparation is successful
    if (!$stmt) {
        // Log an error message if preparation failed
        error_log("Database statement preparation failed: " . $con->error);
        return 0; // Return 0 or an appropriate value on error
    }

    // Bind the production line parameter to the SQL query
    $stmt->bind_param('ss', $prodline, $prodline);

    // Execute the prepared statement
    $stmt->execute();

    // Get the result set from the executed query
    $result = $stmt->get_result();

    // Check if any rows are returned
    if ($result->num_rows === 0) {
        return 0; // Return 0 or an appropriate value if no rows are found
    }

    // Fetch the associative array from the result set
    $row = $result->fetch_assoc();

    // Return the total engaged quantity
    return $row['total_pack_qty'] ?? 0;
}
$engagedQuantity = getEngagedQuantity($con, $prodline);
// echo $engagedQuantity;

function getProducedQuantity($con, $prodline): int
{
    // SQL query to calculate the total produced quantity for the given production line on the current date
    $sql = "SELECT 
                SUM(`pack_qty`) AS total_pack_qty 
            FROM 
                `prod__pack_operation` 
            WHERE 
                `prod__pack_operation`.`cur_date` = CURRENT_DATE 
                AND `prod__pack_operation`.`opn_code` = '5072' 
                AND `prod__pack_operation`.`prod_line` = ?;";

    // Prepare the SQL statement
    $stmt = $con->prepare($sql);
    // Check if statement preparation is successful
    if (!$stmt) {
        // Log an error message if preparation failed
        error_log("Database statement preparation failed: " . $con->error);
        return 0; // Return 0 or an appropriate value on error
    }

    // Bind the production line parameter to the SQL query
    $stmt->bind_param('s', $prodline);

    // Execute the prepared statement
    $stmt->execute();

    // Get the result set from the executed query
    $result = $stmt->get_result();
    // Check if any rows are returned
    if ($result->num_rows === 0) {
        return 0; // Return 0 or an appropriate value if no rows are found
    }

    // Fetch the associative array from the result set
    $row = $result->fetch_assoc();

    // Return the total produced quantity
    return $row['total_pack_qty'] ?? 0;
}
$producedQuantity = getProducedQuantity($con, $prodline);
// echo $producedQuantity;

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
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include("sideBare.php") ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h2 class="h1 mb-0 mt-4 text-gray-800">Dashboard</h2>
                        <h3 class="h3 mb-0 mt-4 text-primary">Chaine: <?php echo ($prodline == "CH_Q" ? "Chaine Qualité" : $prodline); ?></h3>

                        <!-- START OF PRODLINES DROPDOWN LIST -->
                        <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle mt-4"
                            type="button" id="deroulantb" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <?php echo ($prodline == "CH_Q" ? "Chaine Qualité" : $prodline); ?>
                        </button>

                        <?php
                        $sql = "SELECT prod_line FROM init__prod_line
                                WHERE prod_line NOT LIKE 'CH_Q'
                                ORDER BY id ASC";

                        $prodlines = [];
                        if ($result = $con->query($sql)) {
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $prodlines[] = $row['prod_line'];
                                }
                            }
                            $result->free(); // Free result set
                        } else {
                            // Log or handle error if needed
                            error_log("Query failed: " . $con->error);
                        }
                        ?>
                        <div class="dropdown-menu" aria-labelledby="deroulantb">
                            <?php foreach ($prodlines as $line) { ?>
                                <a href="indexprodline.php?prod_line=<?php echo $line; ?>">
                                    <button class="dropdown-item" type="button"><?php echo $line; ?></button>
                                </a>
                            <?php } ?>
                            <a href="indexprodlinechq.php?prod_line=CH_Q">
                                <button class="dropdown-item" type="button">Chaine Qualité</button>
                            </a>
                        </div>
                        <!-- END OF PRODLINES DROPDOWN LIST -->
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Objectif -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">objectif</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="Obj1">
                                                <?php if (empty($objData)): ?>
                                                    _
                                                <?php else: ?>
                                                    <?php foreach ($objData as $row): ?>
                                                        <?php echo htmlspecialchars($row['model'] ?? 'N/A') . ': ' . htmlspecialchars($row['objective'] ?? 0); ?>
                                                        <br>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quantité Engagée -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Quantité Engagée</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="QENG1">
                                                <?php echo $engagedQuantity; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quantité Fabriquée -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Quantité Fabriquée
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="QFAB1">
                                                <?php echo $producedQuantity; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Controle Qualité -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Contrôle Qualité</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="CQ1">
                                                <?php

                                                $query2 = "SELECT
                                                                -- subquery.`pack_num`,
                                                                subquery.`quantity`,
                                                                subquery.`defective_pcs`
                                                                -- subquery.`cur_dt`
                                                            FROM (
                                                                SELECT
                                                                    -- MIN(`prod__eol_control`.`pack_num`) as `pack_num`,
                                                                    MIN(`prod__eol_control`.`quantity`) as `quantity`,
                                                                    MIN(`prod__eol_control`.`defective_pcs`) as `defective_pcs`
                                                                    -- MIN(`prod__eol_control`.`updated_at`) as `cur_dt`
                                                                FROM
                                                                    `prod__eol_control`
                                                                WHERE
                                                                    `group` = '$prodline'
                                                                    AND DATE(`prod__eol_control`.`updated_at`) = CURRENT_DATE
                                                                    AND `prod__eol_control`.`ctrl_state` = 1
                                                                GROUP BY
                                                                    `prod__eol_control`.`pack_num`
                                                            ) as subquery;";
                                                $rslt2 = $con->query($query2);

                                                $tab2 = [];
                                                while ($item2 = $rslt2->fetch_assoc()) {
                                                    $tab2[] = $item2;
                                                }

                                                $qfab = array_sum(array_column($tab2, 'quantity'));
                                                $qdf = array_sum(array_column($tab2, 'defective_pcs'));
                                                $cq = $qfab > 0 ? ($qdf / $qfab) * 100 : 0;

                                                echo (round($cq, 2));

                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Présence -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-dark shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                                Nombre d'opératrices présentes
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="op1"> <a
                                                    href="presence.php?prodline=<?php echo $prodline; ?>">
                                                    <?php echo $presentOperators; ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- CETTE PARTIE PHP POUR QUANTITÉS FABRIQUÉES CHART -->
                    <div class="row">
                        <div class="col">
                            <div class="card shadow mb-4">
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Quantités Fabriquées</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="prodQteChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        $stmt = $con->prepare(
                            "SELECT SUM(pack_qty) AS quantity, cur_date
                            FROM prod__pack_operation
                            WHERE opn_code = '5072'
                            AND prod_line = ?
                            GROUP BY cur_date
                            ORDER BY cur_date DESC LIMIT 7;"
                        );
                        $stmt->bind_param("s", $prodline);
                        $stmt->execute();
                        $rslt2 = $stmt->get_result();
                        $tab2 = [];
                        while ($item2 = $rslt2->fetch_assoc()) {
                            $tab2[] = $item2;
                        }
                        $stmt->close();

                        // Initialize arrays
                        $qfab = [];
                        $qfabDates = [];

                        // Iterate through the SQL result to build the arrays
                        foreach ($tab2 as $row) {
                            $qfab[] = $row['quantity']; // Collect quantities
                            $qfabDates[] = date('d-m-Y', strtotime($row['cur_date'])); // Format date for display
                        }
                        ?>

                        <script>
                            const prodQteChartCtx = document.getElementById("prodQteChart").getContext('2d');
                            const prodQteChart = new Chart(prodQteChartCtx, {
                                type: 'bar',
                                data: {
                                    labels: <?php echo json_encode(array_reverse($qfabDates)); ?>,
                                    datasets: [{
                                        label: "Quantités Fabriquées",
                                        backgroundColor: "rgba(128, 156, 237)",
                                        // borderColor: "rgba(78, 115, 223, 1)",
                                        borderWidth: 0, // Width of the bar borders
                                        // hoverBackgroundColor: "rgba(78, 115, 223, 0.75)",
                                        data: <?php echo json_encode(array_reverse($qfab)); ?>,
                                    }],
                                },
                                options: {
                                    maintainAspectRatio: false,
                                    layout: {
                                        padding: {
                                            // left: 0,
                                            right: 10,
                                            // top: 0,
                                            // bottom: 0
                                        }
                                    },
                                    scales: {
                                        xAxes: [{
                                            gridLines: {
                                                display: true, // Show grid lines on X-axis
                                                drawBorder: false, // Don't draw the border at the bottom
                                                color: "rgba(200, 200, 200, 0.2)", // Light grid line color
                                            },
                                            ticks: {
                                                maxTicksLimit: 7, // Maximum visible ticks
                                            },
                                            offset: true // Ensure grid lines are drawn at the end of the last label
                                        }],
                                        yAxes: [{
                                            ticks: {
                                                beginAtZero: true, // Start Y-axis at 0
                                            },
                                            gridLines: {
                                                color: "rgba(200, 200, 200, 0.2)", // Light grid line color
                                            }
                                        }],
                                    },
                                    legend: {
                                        display: true, // Show legend
                                        position: 'top', // Legend position
                                    },
                                    tooltips: {
                                        enabled: true, // Enable tooltips
                                    }
                                }
                            });
                        </script>
                    </div>

                    <!-- CETTE PARTIE PHP POUR QUANTITÉS ENGAGÉES CHART -->
                    <div class="row">
                        <div class="col">
                            <div class="card shadow mb-4">
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Quantités Engagées</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="engQteChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        $inClauseDates = array_map(function ($date) {
                            return date('Y-m-d', strtotime($date));
                        }, array_reverse($qfabDates));

                        // Create placeholders for each date
                        $placeholders = implode(',', array_fill(0, count($inClauseDates), '?'));

                        // Prepare the query with dynamic placeholders
                        $stmt = $con->prepare(
                            "SELECT qty_eng, cur_date
                            FROM prod__indicator
                            WHERE prod_line = ?
                            AND cur_date IN ($placeholders)
                            ORDER BY cur_date DESC LIMIT 6;"
                        );

                        // Combine prodline and dates for parameter binding
                        $params = array_merge([$prodline], $inClauseDates);

                        // Dynamically generate types string for bind_param
                        $types = str_repeat('s', count($params)); // 's' for each string

                        // Bind parameters dynamically
                        $stmt->bind_param($types, ...$params);

                        // Execute the statement
                        $stmt->execute();
                        $rslt2 = $stmt->get_result();
                        $tab2 = [];
                        while ($item2 = $rslt2->fetch_assoc()) {
                            $tab2[] = $item2;
                        }
                        $stmt->close();

                        // Print the results
                        // print_r($tab2);

                        // Initialize arrays
                        $qeng = [];
                        $qengDates = [];

                        // Iterate through the SQL result to build the arrays
                        foreach ($tab2 as $row) {
                            $qeng[] = $row['qty_eng']; // Collect quantities
                            $qengDates[] = date('d-m-Y', strtotime($row['cur_date'])); // Format date for display
                        }
                        ?>

                        <script>
                            const engQteChartCtx = document.getElementById("engQteChart");
                            const engQteChart = new Chart(engQteChartCtx, {
                                type: 'bar',
                                data: {
                                    labels: <?php echo json_encode(array_reverse($qengDates)); ?>,
                                    datasets: [{
                                        label: "Quantités Engagées",
                                        backgroundColor: "rgba(128, 156, 237)",
                                        // borderColor: "rgba(78, 115, 223, 1)",
                                        borderWidth: 0, // Width of the bar borders
                                        // hoverBackgroundColor: "rgba(78, 115, 223, 0.75)",
                                        data: <?php echo json_encode(array_reverse($qeng)); ?>,
                                    }],
                                },
                                options: {
                                    maintainAspectRatio: false,
                                    layout: {
                                        padding: {
                                            // left: 0,
                                            right: 10,
                                            // top: 0,
                                            // bottom: 0
                                        }
                                    },
                                    scales: {
                                        xAxes: [{
                                            gridLines: {
                                                display: true, // Show grid lines on X-axis
                                                drawBorder: false, // Don't draw the border at the bottom
                                                color: "rgba(200, 200, 200, 0.2)", // Light grid line color
                                            },
                                            ticks: {
                                                maxTicksLimit: 7, // Maximum visible ticks
                                            },
                                            offset: true // Ensure grid lines are drawn at the end of the last label
                                        }],
                                        yAxes: [{
                                            ticks: {
                                                beginAtZero: true, // Start Y-axis at 0
                                            },
                                            gridLines: {
                                                color: "rgba(200, 200, 200, 0.2)", // Light grid line color
                                            }
                                        }],
                                    },
                                    legend: {
                                        display: true, // Show legend
                                        position: 'top', // Legend position
                                    },
                                    tooltips: {
                                        enabled: true, // Enable tooltips
                                    }
                                }
                            });
                        </script>
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

    <!-- Bootstrap core JavaScript-->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="js/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="js/Chart.min.js"></script>
</body>

</html>