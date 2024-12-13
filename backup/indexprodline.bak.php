<?php
session_start();
date_default_timezone_set('Africa/Tunis');
require_once './php/config.php';
require_once './php/configobj.php';

// $query2 = "SELECT
//             -- subquery.`pack_num`,
//             subquery.`quantity`,
//             subquery.`defective_pcs`
//             -- subquery.`cur_dt`
//         FROM (
//             SELECT
//                 -- MIN(`prod__eol_control`.`pack_num`) as `pack_num`,
//                 MIN(`prod__eol_control`.`quantity`) as `quantity`,
//                 MIN(`prod__eol_control`.`defective_pcs`) as `defective_pcs`
//                 -- MIN(`prod__eol_control`.`updated_at`) as `cur_dt`
//             FROM
//                 `prod__eol_control`
//             WHERE
//                 `group` = '$prodline'
//                 AND DATE(`prod__eol_control`.`updated_at`) = CURRENT_DATE
//                 AND `prod__eol_control`.`ctrl_state` = 1
//             GROUP BY
//                 `prod__eol_control`.`pack_num`
//         ) as subquery;";
// $rslt2 = $con->query($query2);

// $tab2 = [];
// while ($item2 = $rslt2->fetch_assoc()) {
//     $tab2[] = $item2;
// }

// $qfab = array_sum(array_column($tab2, 'quantity'));
// $qdf = array_sum(array_column($tab2, 'defective_pcs'));
// $cq = $qfab > 0 ? ($qdf / $qfab) * 100 : 0;

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
    <?php require_once './php/config.php';
    if (isset($_GET["prod_line"])) {
        $prodline = $_GET["prod_line"];
    ?>
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
                        <h3 class="h3 mb-0 ml-4 mt-4 text-primary">Chaine:
                            <?php echo ($prodline) ?>
                        </h3>
                        <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle mt-4"
                            type="button" id="deroulantb" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <?php echo ($prodline) ?>

                        </button>
                        <?php $sql = "SELECT * FROM init__prod_line";
                        $result = $con->query($sql);
                        $prodlines = [];
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $prodlines[] = $row['prod_line'];
                            }
                        } ?>
                        <div class="dropdown-menu" aria-labelledby="deroulantb">
                            <!-- <a href="index.php"><button class="dropdown-item" type="button"> TOUS </button></a> -->
                            <?php foreach ($prodlines as $line) { ?>
                                <a href="indexprodline.php?prod_line=<?php echo $line; ?>"><button class="dropdown-item"
                                        type="button"><?php echo $line; ?></button></a>
                            <?php } ?>
                        </div>
                        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Objectif -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                objectif</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="Obj1">

                                                <?php
                                                $query = "SELECT `prod__prod_line`.`objective`, `init__prod_line`.`prod_line`, `init__model`.`model` FROM `prod__prod_line` 
                                            INNER JOIN `init__prod_line` ON `prod__prod_line`.`prod_line_id`= `init__prod_line`.`id`
                                            INNER JOIN `init__model` ON `init__model`.`id`= `prod__prod_line`.`model_id`
                                            WHERE `prod__prod_line`.`cur_date`= DATE_FORMAT(CURRENT_DATE, '%Y-%m-%d') AND `init__prod_line`.`prod_line` = '$prodline' ORDER BY `prod__prod_line`.`id` DESC ";
                                                $rslt = $con->query($query);

                                                $tab4 = [];
                                                while ($item = $rslt->fetch_assoc()) {
                                                    $tab4[] = $item;
                                                }
                                                $i3 = 0;
                                                $obj = 0;
                                                if (count($tab4) > 0) {
                                                    for ($i = 0; $i < count($tab4); $i++) {
                                                        echo $tab4[$i]['model'] . ': ' . $tab4[$i]['objective'] . '<br>';
                                                    }
                                                } else {
                                                    echo 0;
                                                } ?>
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
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Quantité Engagée</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="QENG1">
                                                <?php
                                                $quer = "SELECT 
                                                    MAX(`pack_qty`) AS total_pack_qty 
                                                FROM 
                                                    `prod__pack_operation`
                                                WHERE 
                                                    `cur_date` = CURRENT_DATE
                                                    AND `prod_line` = '$prodline'
                                                    AND `pack_num` NOT IN (
                                                        SELECT `pack_num`
                                                        FROM `prod__pack_operation`
                                                        WHERE `cur_date` < CURRENT_DATE
                                                        AND `prod_line` = '$prodline'
                                                        GROUP BY `pack_num`
                                                    )
                                                GROUP BY 
                                                    `pack_num`;";
                                                $rsl = $con->query($quer);

                                                $tabl = [];
                                                while ($items = $rsl->fetch_assoc()) {
                                                    $tabl[] = $items;
                                                }

                                                $ieng = 0;
                                                $qengaged = 0;
                                                for ($ieng = 0; $ieng < count($tabl); $ieng++) {
                                                    $qengaged += $tabl[$ieng]['total_pack_qty'];
                                                }
                                                echo ($qengaged); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quantité Encoure -->
                        <!-- <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Quantité Encours </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="QENC1"> -->
                        <?php
                        $query = "SELECT
                                                SUM(t1.`pack_qty`) AS qte_enc
                                            FROM
                                                (
                                                SELECT DISTINCT
                                                    `pack_num`,
                                                    `pack_qty`
                                                FROM
                                                    `prod__pack_operation` WHERE `prod_line` = '$prodline'
                                            ) t1
                                            WHERE
                                                t1.`pack_num` NOT IN(
                                                SELECT
                                                    `pack_num`
                                                FROM
                                                    `prod__eol_control` WHERE `prod__eol_control`.`ctrl_state`=1 AND `prod__eol_control`.`returned`=0
                                            )";
                        $rslt = $con->query($query);

                        $tab = [];
                        while ($item = $rslt->fetch_assoc()) {
                            $tab[] = $item;
                        }

                        $query2 = "SELECT
                                                subquery.`pack_num`,
                                                subquery.`quantity`,
                                                subquery.`defective_pcs`,
                                                subquery.`defects_num`,
                                                subquery.`cur_dt`,
                                                subquery.returned
                                            FROM (
                                                SELECT
                                                    `prod__eol_control`.`pack_num`,
                                                    /*MAX(*/`prod__eol_control`.`quantity`/*)*/ as `quantity`,
                                                    /*MAX(*/`prod__eol_control`.`defective_pcs`/*)*/ as `defective_pcs`,
                                                    /*MAX(*/`prod__eol_control`.`defects_num`/*)*/ as `defects_num`,
                                                    /*MAX(*/`prod__eol_control`.`updated_at`/*)*/ as `cur_dt`,
                                                    /*MAX(*/`prod__eol_control`.`returned`/*)*/ as returned
                                                FROM
                                                    `prod__eol_control`
                                                WHERE
                                                `group` = '$prodline'
                                                    AND DATE(`prod__eol_control`.`updated_at`) = CURRENT_DATE AND `prod__eol_control`.`ctrl_state`=1 /*AND `prod__eol_control`.`returned`=0*/
                                                /*GROUP BY
                                                    `prod__eol_control`.`pack_num`*/
                                            ) as subquery;";
                        $rslt2 = $con->query($query2);

                        $tab2 = [];
                        while ($item2 = $rslt2->fetch_assoc()) {
                            $tab2[] = $item2;
                        }
                        $qdf = 0;
                        $qfab = 0;
                        $ifab = 0;
                        $cq = 0;
                        while ($ifab < count($tab2)) {
                            if ($tab2[$ifab]['returned'] == 0) {
                                $qfab += $tab2[$ifab]['quantity'];
                            }
                            $qdf += $tab2[$ifab]['defective_pcs'];
                            if ($qfab > 0) {
                                $cq = ($qdf / ($qfab)) * 100;
                            } else {
                                $cq = 0;
                            }
                            $ifab++;
                        }

                        $ienc = 0;
                        $qencours = 0;
                        while ($ienc < count($tab)) {
                            $qencours += $tab[$ienc]['qte_enc'];

                            $ienc++;
                        }
                        /*   echo ($qencours);*/ ?>
                        <!-- </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

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
                                                <?php
                                                $query = "SELECT
  SUM(`pack_qty`) AS qte_fab 
FROM
    `prod__pack_operation` WHERE `prod__pack_operation`.`cur_date` = CURRENT_DATE AND `prod__pack_operation`.`opn_code`='5072' AND `prod__pack_operation`.`prod_line` ='$prodline' 
";
                                                $rslt3 = $con->query($query);

                                                $qfabA = 0;
                                                while ($item3 = $rslt3->fetch_assoc()) {
                                                    $qfabA += $item3['qte_fab'];
                                                }


                                                echo ($qfabA); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Controle Qualité -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Contrôle Qualité</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="CQ1">
                                                <?php echo (round($cq, 2)); ?>
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
                                                Nombre des opératrices présentes
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="op1"> <a
                                                    href="presence.php?prodline=<?php echo $prodline; ?>">
                                                    <?php $queryP = "SELECT
    COUNT(*) AS presence
FROM
    prod__presence
WHERE
    p_state = 1 AND id IN(
    SELECT
        MAX(id)
    FROM
        prod__presence
    GROUP BY
        operator
) AND `prod_line` = '$prodline' AND cur_date = CURRENT_DATE;";
                                                    $rsltP = $con->query($queryP);
                                                    $tabP = [];
                                                    while ($itemP = $rsltP->fetch_assoc()) {
                                                        $tabP[] = $itemP;
                                                    }
                                                    echo ($tabP[0]['presence']);
                                                    ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- CETTE PARTIE PHP POUR LE CHART -->

                    <div class="row">
                        <!-- Quantité Chart -->
                        <div class="col">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Quantité Fabriquée</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Voir plus:</div>
                                            <a class="dropdown-item" href="allpacks.php">Paquets</a>
                                            <div class="dropdown-divider"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChartQte"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CETTE PARTIE PHP POUR LE CHART -->
                    <?php
                    // header("content-Type: application/json");
                    date_default_timezone_set('Africa/Tunis');
                    require_once './php/config.php';

                    $query2 = "SELECT
  SUM(`pack_qty`) AS quantity ,cur_date
FROM
    `prod__pack_operation` WHERE `prod__pack_operation`.`opn_code`='5072' AND `prod__pack_operation`.`prod_line` ='$prodline' GROUP BY 
  `cur_date` ORDER by cur_date desc 
                        LIMIT 7;";
                    $rslt2 = $con->query($query2);

                    $tab2 = [];
                    while ($item2 = $rslt2->fetch_assoc()) {
                        $tab2[] = $item2;
                    }

                    $qfab1 = 0;
                    $qfab2 = 0;
                    $qfab3 = 0;
                    $qfab4 = 0;
                    $qfab5 = 0;
                    $qfab6 = 0;
                    $qfab7 = 0;  // Ajouté pour correspondre au jour J-6

                    for ($i1 = 0; $i1 < count($tab2); $i1++) {
                        switch ($tab2[$i1]['cur_date']) {
                            case date('Y-m-d'):
                                $qfab1 += $tab2[$i1]['quantity'];
                                break;
                            case date('Y-m-d', strtotime("-1 day")):
                                $qfab2 += $tab2[$i1]['quantity'];
                                break;
                            case date('Y-m-d', strtotime("-2 days")):
                                $qfab3 += $tab2[$i1]['quantity'];
                                break;
                            case date('Y-m-d', strtotime("-3 days")):
                                $qfab4 += $tab2[$i1]['quantity'];
                                break;
                            case date('Y-m-d', strtotime("-4 days")):
                                $qfab5 += $tab2[$i1]['quantity'];
                                break;
                            case date('Y-m-d', strtotime("-5 days")):
                                $qfab6 += $tab2[$i1]['quantity'];
                                break;
                            case date('Y-m-d', strtotime("-6 days")): // Ajouté pour correspondre au jour J-6
                                $qfab7 += $tab2[$i1]['quantity'];
                                break;
                        }
                    }

                    $date = [
                        date('d-m-Y', strtotime("-6 days")),
                        date('d-m-Y', strtotime("-5 days")),
                        date('d-m-Y', strtotime("-4 days")),
                        date('d-m-Y', strtotime("-3 days")),
                        date('d-m-Y', strtotime("-2 days")),
                        date('d-m-Y', strtotime("-1 day")),
                        date('d-m-Y')
                    ];
                    $qtefab = [$qfab7, $qfab6, $qfab5, $qfab4, $qfab3, $qfab2, $qfab1];
                    ?>
                    <script>
                        var ctx = document.getElementById("myAreaChartQte");
                        var myLineChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: <?php echo json_encode($date); ?>,
                                datasets: [{
                                    label: "Quantité Fabriquée ",
                                    lineTension: 0.3,
                                    backgroundColor: "rgba(128, 156, 237)",
                                    borderColor: "rgba(78, 115, 223, 1)",
                                    pointRadius: 3,
                                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                                    pointBorderColor: "rgba(78, 115, 223, 1)",
                                    pointHoverRadius: 3,
                                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                                    pointHitRadius: 10,
                                    pointBorderWidth: 2,
                                    data: <?php echo json_encode($qtefab);
                                        } ?>,
                                }],
                            },
                            options: {
                                maintainAspectRatio: false,
                                layout: {
                                    padding: {
                                        left: 10,
                                        right: 25,
                                        top: 25,
                                        bottom: 0
                                    }
                                },
                                scales: {
                                    xAxes: [{
                                        time: {
                                            unit: 'date'
                                        },
                                        gridLines: {
                                            display: false,
                                            drawBorder: false
                                        },
                                        ticks: {
                                            maxTicksLimit: 7
                                        }
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            maxTicksLimit: 5,
                                            padding: 10,
                                            // Include a dollar sign in the ticks
                                            callback: function(value, index, values) {
                                                return number_format(value);
                                            }
                                        },
                                        gridLines: {
                                            color: "rgb(234, 236, 244)",
                                            zeroLineColor: "rgb(234, 236, 244)",
                                            drawBorder: false,
                                            borderDash: [2],
                                            zeroLineBorderDash: [2]
                                        }
                                    }],
                                },
                                legend: {
                                    display: false
                                },
                                tooltips: {
                                    backgroundColor: "rgb(255,255,255)",
                                    bodyFontColor: "#858796",
                                    titleMarginBottom: 10,
                                    titleFontColor: '#6e707e',
                                    titleFontSize: 14,
                                    borderColor: '#dddfeb',
                                    borderWidth: 1,
                                    xPadding: 15,
                                    yPadding: 15,
                                    displayColors: false,
                                    intersect: false,
                                    mode: 'index',
                                    caretPadding: 10,
                                    callbacks: {
                                        label: function(tooltipItem, chart) {
                                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                            return datasetLabel + number_format(tooltipItem.yLabel);
                                        }
                                    }
                                }
                            }
                        });
                    </script>
                    <!-- Engagée chart-->
                    <!-- CETTE PARTIE PHP POUR LE CHART -->

                    <div class="row">
                        <!-- Quantité Chart -->
                        <div class="col">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Quantité Engagée</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>

                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CETTE PARTIE PHP POUR LE CHART -->
                    <?php

                    // header("content-Type: application/json");
                    date_default_timezone_set('Africa/Tunis');
                    require_once './php/config.php';

                    $query2 = "SELECT `qty_eng` ,cur_date FROM `prod__indicator` where cur_date >= NOW() - INTERVAL 7 DAY AND prod_line='$prodline';";
                    $rslt2 = $con->query($query2);

                    $tab2 = [];
                    while ($item2 = $rslt2->fetch_assoc()) {
                        $tab2[] = $item2;
                    }

                    $qeng1 = 0;
                    $qeng2 = 0;
                    $qeng3 = 0;
                    $qeng4 = 0;
                    $qeng5 = 0;
                    $qeng6 = 0;
                    $i1 = 0;
                    for ($i1 = 0; $i1 < count($tab2); $i1++) {
                        switch ($tab2[$i1]['cur_date']) {
                            case date('Y-m-d'):
                                $qeng1 += $tab2[$i1]['qty_eng'];
                                break;
                            case date('Y-m-d', strtotime("-1 day")):
                                $qeng2 += $tab2[$i1]['qty_eng'];
                                break;
                            case date('Y-m-d', strtotime("-2 day")):
                                $qeng3 += $tab2[$i1]['qty_eng'];
                                break;
                            case date('Y-m-d', strtotime("-3 day")):
                                $qeng4 += $tab2[$i1]['qty_eng'];
                                break;
                            case date('Y-m-d', strtotime("-4 day")):
                                $qeng5 += $tab2[$i1]['qty_eng'];
                                break;
                            case date('Y-m-d', strtotime("-5 day")):
                                $qeng6 += $tab2[$i1]['qty_eng'];
                                break;
                        }
                    }
                    $date = [date('d-m-Y', strtotime("-5 day")), date('d-m-Y', strtotime("-4 day")), date('d-m-Y', strtotime("-3 day")), date('d-m-Y', strtotime("-2 day")), date('d-m-Y', strtotime("-1 day")), date('d-m-Y')];
                    $qteeng = [$qeng6, $qeng5, $qeng4, $qeng3, $qeng2, $qeng1];
                    // echo json_encode($qtefab);
                    ?>
                    <script>
                        var ctx = document.getElementById("myAreaChart");
                        var myLineChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: <?php echo json_encode($date); ?>,
                                datasets: [{
                                    label: "Quantité Engagée ",
                                    lineTension: 0.3,
                                    backgroundColor: "rgba(128, 156, 237)",
                                    borderColor: "rgba(78, 115, 223, 1)",
                                    pointRadius: 3,
                                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                                    pointBorderColor: "rgba(78, 115, 223, 1)",
                                    pointHoverRadius: 3,
                                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                                    pointHitRadius: 10,
                                    pointBorderWidth: 2,
                                    data: <?php echo json_encode($qteeng);
                                            ?>,
                                }],
                            },
                            options: {
                                maintainAspectRatio: false,
                                layout: {
                                    padding: {
                                        left: 10,
                                        right: 25,
                                        top: 25,
                                        bottom: 0
                                    }
                                },
                                scales: {
                                    xAxes: [{
                                        time: {
                                            unit: 'date'
                                        },
                                        gridLines: {
                                            display: false,
                                            drawBorder: false
                                        },
                                        ticks: {
                                            maxTicksLimit: 7
                                        }
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            maxTicksLimit: 5,
                                            padding: 10,
                                            // Include a dollar sign in the ticks
                                            callback: function(value, index, values) {
                                                return number_format(value);
                                            }
                                        },
                                        gridLines: {
                                            color: "rgb(234, 236, 244)",
                                            zeroLineColor: "rgb(234, 236, 244)",
                                            drawBorder: false,
                                            borderDash: [2],
                                            zeroLineBorderDash: [2]
                                        }
                                    }],
                                },
                                legend: {
                                    display: false
                                },
                                tooltips: {
                                    backgroundColor: "rgb(255,255,255)",
                                    bodyFontColor: "#858796",
                                    titleMarginBottom: 10,
                                    titleFontColor: '#6e707e',
                                    titleFontSize: 14,
                                    borderColor: '#dddfeb',
                                    borderWidth: 1,
                                    xPadding: 15,
                                    yPadding: 15,
                                    displayColors: false,
                                    intersect: false,
                                    mode: 'index',
                                    caretPadding: 10,
                                    callbacks: {
                                        label: function(tooltipItem, chart) {
                                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                            return datasetLabel + number_format(tooltipItem.yLabel);
                                        }
                                    }
                                }
                            }
                        });
                    </script>

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

    <!-- Page level custom scripts -->
    <script src="js/script1.js"></script>

</body>

</html>