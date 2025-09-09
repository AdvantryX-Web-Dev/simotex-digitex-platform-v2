<?php date_default_timezone_set('Africa/Tunis'); ?>
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

    <?php require_once './php/config.php';
    require_once './php/configobj.php';
    if (isset($_GET["prod_line"]) || isset($_GET["prod"])) {
        $prodline = isset($_GET["prod_line"]) ? $_GET["prod_line"] : '';
        $prod = isset($_GET["prod"]) ? $_GET["prod"] : '';
        // $prod1=$_GET["prod1"];
    ?>
        <script>
            function autoRefresh() {
                window.location = window.location.href = "dashboardchaine.php?prod_line=<?php echo ($prod); ?>&prod=<?php echo ($prodline); ?>";
            }
            setInterval('autoRefresh()', 60000);
        </script>

</head>

<body>
    <!-- Responsive navbar-->
    <nav class="navbar navbar-expand-lg ml-5 mr-4">

        <a class="navbar-brand" href="http://advantryx.com/"> <img src="./img/logo.png" alt="Logo"
                style="width:40mm;height:15mm"> </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"></button>
        <div class="collapse navbar-collapse">
            <h3 class="navbar-nav ms-auto mb-2 mb-lg-0 ml-4 mr-4 text-gray-800" id="prodline"> Prod-Line:
            <?php echo ($prodline);
        } ?>
            </h3>
        </div>
        <div class="collapse navbar-collapse">
            <h3 class="navbar-nav ms-auto mb-2 mb-lg-0 ml-4 mr-4 text-gray-800" id="date"> </h3>
        </div>
        <div class="collapse navbar-collapse">
            <h3 class="navbar-nav ms-auto mb-2 mb-lg-0 ml-4 mr-4 text-gray-800" id="time"> </h3>
        </div>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <img src="./img/mahdco.png" alt="Logo" style="width:18mm;height:18mm">
        </div>
        <script>
            // Fonction pour formater le nombre avec un zéro devant s'il est inférieur à 10
            function formatterNombre(nombre) {
                return nombre < 10 ? `0${nombre}` : nombre;
            }

            // Sélectionner les éléments HTML pour la date et l'heure
            const dateElement = document.getElementById('date');
            const heureElement = document.getElementById('time');

            // Fonction pour afficher la date et l'heure actuelles
            function afficherDateHeure() {
                // Obtenir la date et l'heure actuelles
                const maintenant = new Date();
                const annee = maintenant.getFullYear();
                const mois = formatterNombre(maintenant.getMonth() + 1);
                const jour = formatterNombre(maintenant.getDate());
                const heures = formatterNombre(maintenant.getHours());
                const minutes = formatterNombre(maintenant.getMinutes());
                const secondes = formatterNombre(maintenant.getSeconds());

                // Afficher la date dans l'élément HTML
                dateElement.textContent = 'DATE: '.concat(`${jour}-${mois}-${annee}`);

                // Afficher l'heure dans l'élément HTML
                heureElement.textContent = 'HEURE: '.concat(`${heures}:${minutes}:${secondes}`);
            }

            afficherDateHeure();

            setInterval(afficherDateHeure, 1000);
        </script>

    </nav>
    <!--Tableau des Indicateurs-->
    <div class="row ml-2 mr-2">

        <!-- Qté Engagée -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-76 py-1">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="h4 font-weight-bold text-primary text-uppercase mb-3" style="text-align:center">
                                Quantité Engagée </div>
                            <div class="h3 font-weight-bolder text-gray-900" style="text-align:center" id="Qte">
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
                                while ($ieng < count($tabl)) {
                                    $qengaged += $tabl[$ieng]['total_pack_qty'];
                                    $ieng++;
                                }
                                echo ($qengaged); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quantité Fabriquée -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-76 py-1">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2 ">
                            <div class="h4 font-weight-bold text-success text-uppercase mb-3" style="text-align:center">
                                Quantité Fabriquée </div>
                            <div id="Fabriquée" class="h3 font-weight-bolder text-gray-900" style="text-align:center">
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
                                                                                                                )  t1
                                                                                                                WHERE
                                                                                                                    t1.`pack_num` NOT IN(
                                                                                                                    SELECT
                                                                                                                        `pack_num`
                                                                                                                    FROM
                                                                                                                        `prod__eol_control` WHERE  `prod__eol_control`.`ctrl_state`=1 AND `prod__eol_control`.`returned`=0
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
                                $qfab = 1;
                                $ifab = 0;
                                $cq = 0;
                                while ($ifab < count($tab2)) {
                                    if ($tab2[$ifab]['returned'] == 0) {
                                        $qfab += $tab2[$ifab]['quantity'];
                                    }
                                    $qdf += $tab2[$ifab]['defective_pcs'];
                                    if ($qfab > 1) {
                                        $cq = ($qdf / ($qfab - 1)) * 100;
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
                                // echo ($qencours);
                                echo ($qfab - 1);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row ml-2 mr-2">

        <!-- Controle Qualité -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-76 py-1">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="h4 font-weight-bold text-danger text-uppercase mb-3" style="text-align:center">
                                Indice de contrôle Qualité </div>
                            <div id="cq" class="text-gray-900" style="text-align:center">
                                <?php

                                if ((round($cq, 2)) >= 8) {
                                    echo "<h3 class='font-weight-bolder text-danger fas fa-caret-down '>" . round($cq, 2) . "% </h3>";
                                } else {
                                    echo "<h3 class='font-weight-bolder text-success fas fa-caret-up'>" . round($cq, 2) . "% </h3>";
                                }
                                // echo (round($cq, 2));

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Objectif -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-76 py-1">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="h4 font-weight-bold text-warning text-uppercase mb-3" style="text-align:center">
                                Objectif </div>
                            <div class="h3 font-weight-bolder text-gray-900" style="text-align:center" id="objectif">
                                <?php
                                //                                                                             $query = "SELECT `prod__prod_line`.`objective`, `init__prod_line`.`prod_line`, `init__model`.`model` FROM `prod__prod_line` 
                                // INNER JOIN `init__prod_line` ON `prod__prod_line`.`prod_line_id`= `init__prod_line`.`id`
                                // INNER JOIN `init__model` ON `init__model`.`id`= `prod__prod_line`.`model_id`
                                // WHERE `prod__prod_line`.`cur_date`= CURRENT_DATE AND `init__prod_line`.`prod_line` = '$prodline' ORDER BY `prod__prod_line`.`id` DESC ";

                                $query = "SELECT
                                                                                                                    model,
                                                                                                                    ROUND(
                                                                                                                        (
                                                                                                                            (
                                                                                                                                (
                                                                                                                                    CASE WHEN DAYOFWEEK(CURRENT_DATE) = 7 THEN 330 ELSE 510
                                                                                                                                END
                                                                                                                            ) * presence * rendement_objectif
                                                                                                                        ) / temps_de_gamme
                                                                                                                    ) 
                                                                                                                ) AS obj
                                                                                                                FROM
                                                                                                                    (
                                                                                                                    SELECT
                                                                                                                        (
                                                                                                                        SELECT
                                                                                                                            COUNT(*)
                                                                                                                        FROM
                                                                                                                            db_simotex.prod__presence
                                                                                                                        WHERE 
                                                                                                                            p_state = 1 AND id IN(
                                                                                                                            SELECT
                                                                                                                                MAX(id)
                                                                                                                            FROM
                                                                                                                                db_simotex.prod__presence WHERE `prod_line`='$prodline'
                                                                                                                            GROUP BY
                                                                                                                                operator
                                                                                                                        ) AND cur_date = CURRENT_DATE AND `prod_line`='$prodline'
                                                                                                                    ) AS presence,
                                                                                                                    (
                                                                                                                    SELECT
                                                                                                                        (Rendement_Objectif / 100)
                                                                                                                    FROM
                                                                                                                        db_inter.`GALAXY_OF_Paquet_V2`
                                                                                                                    WHERE
                                                                                                                        `Mod_Id` =(
                                                                                                                        SELECT
                                                                                                                            model
                                                                                                                        FROM
                                                                                                                            db_simotex.`prod__pack_operation` WHERE `prod_line`='$prodline'
                                                                                                                        ORDER BY
                                                                                                                            id
                                                                                                                        DESC
                                                                                                                    LIMIT 1
                                                                                                                    ) AND `Cde_Id` =(
                                                                                                                    SELECT
                                                                                                                        `of_num`
                                                                                                                    FROM
                                                                                                                        db_simotex.`prod__pack_operation` WHERE `prod_line`='$prodline'
                                                                                                                    ORDER BY
                                                                                                                        id
                                                                                                                    DESC
                                                                                                                LIMIT 1
                                                                                                                )
                                                                                                                LIMIT 1
                                                                                                                ) AS rendement_objectif,
                                                                                                                (
                                                                                                                    SELECT
                                                                                                                        SUM(`unit_time`)
                                                                                                                    FROM
                                                                                                                        db_simotex.prod__gamme pg
                                                                                                                    INNER JOIN db_simotex.init__model im
                                                                                                                    ON
                                                                                                                        pg.`model_id` = im.`id`
                                                                                                                    WHERE
                                                                                                                        im.`model` =(
                                                                                                                        SELECT
                                                                                                                            model
                                                                                                                        FROM
                                                                                                                            db_simotex.`prod__pack_operation` WHERE `prod_line`='$prodline'
                                                                                                                        ORDER BY
                                                                                                                            id
                                                                                                                        DESC
                                                                                                                    LIMIT 1
                                                                                                                    )
                                                                                                                LIMIT 1
                                                                                                                ) AS temps_de_gamme,
                                                                                                                (
                                                                                                                    SELECT
                                                                                                                        model
                                                                                                                    FROM
                                                                                                                        db_simotex.`prod__pack_operation` WHERE `prod_line`='$prodline'
                                                                                                                    ORDER BY
                                                                                                                        id
                                                                                                                    DESC
                                                                                                                LIMIT 1
                                                                                                                ) AS model
                                                                                                                ) AS calculations;";
                                $rslt = $con->query($query);

                                $tab4 = [];
                                while ($item = $rslt->fetch_assoc()) {
                                    $tab4[] = $item;
                                }
                                // $i3 = 0;
                                // $obj = 0;
                                if (count($tab4) > 0) {
                                    // for ($i = 0; $i < count($tab4); $i++) {
                                    echo $tab4[0]['model'] . ': ' . $tab4[0]['obj'] . '<br>';
                                    // }
                                } else {
                                    echo 0;
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="row ml-2 mr-2">

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-76 py-1">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="h4 font-weight-bold text-dark text-uppercase mb-3" style="text-align:center">
                                Présence </div>
                            <div class="h3 font-weight-bolder text-gray-900" style="text-align:center" id="perf">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-76 py-1">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="h4 font-weight-bold text-danger text-uppercase mb-3" style="text-align:center">
                                Notification </div>
                            <div class="h3 font-weight-bolder text-gray-900" style="text-align:center" id="perf">
                                <?php
                                $sql = "SELECT
                                CONCAT(
                                    emp.first_name,
                                    ' ',
                                    emp.last_name
                                ) AS operator,
                                `machine_id`,
                                `group`
                            FROM
                                `aleas__req_interv` al_req
                            INNER JOIN `init__employee` emp ON
                                al_req.operator = emp.matricule
                            WHERE
                                DATE(`created_at`) = CURRENT_DATE AND `group`= '$prodline' AND al_req.`id` NOT IN(
                                SELECT
                                    `aleas__mon_interv`.req_interv_id
                                FROM
                                    `aleas__mon_interv`
                            );";
                                $rslt = $con->query($sql);

                                $tab5 = [];
                                while ($item = $rslt->fetch_assoc()) {
                                    $tab5[] = $item;
                                }

                                $sql = "SELECT
                                CONCAT(
                                    emp.first_name,
                                    ' ',
                                    emp.last_name
                                )AS monitor,
                                mon.req_interv_id AS req_interv_id,
                                al_req.`group` AS `group`,
                                aleas.designation
                            FROM
                                `aleas__mon_interv` mon 
                            INNER JOIN 
                                `init__employee` emp ON mon.monitor = emp.matricule
                            INNER JOIN 
                                `aleas__req_interv` al_req ON mon.req_interv_id = al_req.id
                            INNER JOIN
                                `init__aleas_type` aleas ON aleas.id = mon.aleas_type_id
                            WHERE
                                mon.req_interv_id NOT IN (
                                    SELECT
                                        `aleas__maint_dispo`.`req_interv_id`
                                    FROM
                                        `aleas__maint_dispo`
                                )
                                AND DATE(mon.created_at) = CURRENT_DATE AND aleas.call_maint = 1 AND `group`= '$prodline'";
                                $rslt = $con->query($sql);

                                $tab6 = [];
                                while ($item = $rslt->fetch_assoc()) {
                                    $tab6[] = $item;
                                }

                                if (count($tab5) > 0 || count($tab6) > 0) {
                                    // Afficher le contenu de $tab5 s'il y a des éléments
                                    if (count($tab5) > 0) {
                                        for ($i = 0; $i < count($tab5); $i++) {
                                            echo "<h5 class='font-weight-bolder text-danger'>Monitrice: Operatrice: " . $tab5[$i]['operator'] . " | Chaine " . $tab5[0]['group'] . "</h5>";
                                        }
                                    }

                                    // Afficher le contenu de $tab6 s'il y a des éléments
                                    if (count($tab6) > 0) {
                                        for ($i = 0; $i < count($tab6); $i++) {
                                            echo "<h5 class='font-weight-bolder text-danger'>Maintenancier: Monitrice: " . $tab6[$i]['monitor'] . " | Chaine " . $tab6[$i]['group'] . "</h5>";
                                        }
                                    }
                                } else {
                                    echo "<h3 class='font-weight-bolder text-gray-900'> --- </h3>";
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!--pied de la page-->
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span><a class="h5" href="http://192.168.1.245/mahdco/digitaltwin/">DIGITAL TWIN</a></span></br>
                <span> Copyright &copy; DigiTex by Advantry X 2023 </span>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.3.js"
        integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>

</body>

</html>