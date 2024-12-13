<?php

session_start();

require_once './php/config.php';

$output = "";

if (isset($_POST["submit"])) {
  $prod_line = isset($_POST["prod_line"]) ? $_POST["prod_line"] : null;
  // $model = isset($_POST["model"]) ? $_POST["model"] : null;
  $date = isset($_POST["date"]) ? $_POST["date"] : null;


  /* $sql = "SELECT
   CONCAT(t12.operatrice, '-', emp.first_name,
         ' ',
         emp.last_name
     ) AS operatrice,
     t12.num_operation,
     CAST( t12.op_n AS REAL) AS num,
     t12.model,
     t12.08h30,
     t12.09h30,
     t12.10h30,
     t12.11h30,
     t12.12h30,
     t12.14h00,
     t12.15h00,
     t12.16h00,
     t12.16h30,
     /*t12.18h00,
     t22.08h30_pd,
     t22.09h30_pd,
     t22.10h30_pd,
     t22.11h30_pd,
     t22.12h30_pd,
     t22.14h00_pd,
     t22.15h00_pd,
     t22.16h00_pd,
     t22.16h30_pd
   /*  t22.18h00_pd*/
  /*FROM
    (
    SELECT
        t1.operatrice,
        t1.num_operation,
        t1.op_n,
        t1.model,
        t1.`08h30` AS `08h30`,
        t1.`09h30` AS `09h30`,
        t1.`10h30` AS `10h30`,
        t1.`11h30` AS `11h30`,
        t1.`12h30` AS `12h30`,
        t1.`14h00` AS `14h00`,
        t1.`15h00` AS `15h00`,
        t1.`16h00` AS `16h00`,
        t1.`16h30` AS `16h30`
      /*  t1.`18h00` AS `18h00`*/

  /* FROM
       (
       SELECT
           MIN(prod__pack_operation.operator) AS operatrice,
           CONCAT(
               MIN(prod__pack_operation.operation_num),
               '-',
               MIN(prod__pack_operation.designation)
           ) AS num_operation,
           MIN(prod__pack_operation.model) AS model,
           MIN(prod__pack_operation.operation_num) AS op_n, 
           SUM(
               CASE WHEN TIME(prod__pack_operation.cur_time) BETWEEN '07:30:00' AND '08:30:00' THEN prod__pack_operation.pack_qty ELSE 0
           END
   ) AS `08h30`,
   SUM(
       CASE WHEN TIME(prod__pack_operation.cur_time) BETWEEN '07:30:00' AND '09:30:00' THEN prod__pack_operation.pack_qty ELSE 0
   END
 ) AS `09h30`,
 SUM(
   CASE WHEN TIME(prod__pack_operation.cur_time) BETWEEN '07:30:00' AND '10:30:00' THEN prod__pack_operation.pack_qty ELSE 0
 END
 ) AS `10h30`,
 SUM(
   CASE WHEN TIME(prod__pack_operation.cur_time) BETWEEN '07:30:00' AND '11:30:00' THEN prod__pack_operation.pack_qty ELSE 0
 END
 ) AS `11h30`,
 SUM(
   CASE WHEN TIME(prod__pack_operation.cur_time) BETWEEN '07:30:00' AND '12:30:00' THEN prod__pack_operation.pack_qty ELSE 0
 END
 ) AS `12h30`,
 SUM(
   CASE WHEN TIME(prod__pack_operation.cur_time) BETWEEN '07:30:00' AND '14:00:00' THEN prod__pack_operation.pack_qty ELSE 0
 END
 ) AS `14h00`,
 SUM(
   CASE WHEN TIME(prod__pack_operation.cur_time) BETWEEN '07:30:00' AND '15:00:00' THEN prod__pack_operation.pack_qty ELSE 0
 END
 ) AS `15h00`,
 SUM(
   CASE WHEN TIME(prod__pack_operation.cur_time) BETWEEN '07:30:00' AND '16:00:00' THEN prod__pack_operation.pack_qty ELSE 0
 END
 ) AS `16h00`,
 SUM(
   CASE WHEN TIME(prod__pack_operation.cur_time) BETWEEN '07:30:00' AND '16:30:00' THEN prod__pack_operation.pack_qty ELSE 0
 END
 ) AS `16h30`

 FROM
   prod__pack_operation
 WHERE
   /*prod__pack_operation.model LIKE '%$model%' 
           AND prod__pack_operation.cur_date = DATE_FORMAT('$date', '%Y-%m-%d')  AND prod__pack_operation.prod_line LIKE '%$prod_line%'
 GROUP BY
   prod__pack_operation.operation_num, 
   prod__pack_operation.operator
 ) t1
 ) t12
 LEFT JOIN(
   SELECT t11.operatrice_pd,
       t11.`08h30` AS `08h30_pd`,
       t11.`09h30` AS `09h30_pd`,
       t11.`10h30` AS `10h30_pd`,
       t11.`11h30` AS `11h30_pd`,
       t11.`12h30` AS `12h30_pd`,
       t11.`14h00` AS `14h00_pd`,
       t11.`15h00` AS `15h00_pd`,
       t11.`16h00` AS `16h00_pd`,
       t11.`16h30` AS `16h30_pd`
     /*  t11.`18h00` AS `18h00_pd`*/

  /* FROM
       (
       SELECT
           MIN(prod__downtime.operator) AS operatrice_pd,
           SUM(
               CASE WHEN TIME(prod__downtime.cur_time) BETWEEN '07:30:00' AND '08:30:00' THEN prod__downtime.downtime ELSE 0
           END
   ) AS `08h30`,
   SUM(
       CASE WHEN TIME(prod__downtime.cur_time) BETWEEN '07:30:00' AND '09:30:00' THEN prod__downtime.downtime ELSE 0
   END
 ) AS `09h30`,
 SUM(
   CASE WHEN TIME(prod__downtime.cur_time) BETWEEN '07:30:00' AND '10:30:00' THEN prod__downtime.downtime ELSE 0
 END
 ) AS `10h30`,
 SUM(
   CASE WHEN TIME(prod__downtime.cur_time) BETWEEN '07:30:00' AND '11:30:00' THEN prod__downtime.downtime ELSE 0
 END
 ) AS `11h30`,
 SUM(
   CASE WHEN TIME(prod__downtime.cur_time) BETWEEN '07:30:00' AND '12:30:00' THEN prod__downtime.downtime ELSE 0
 END
 ) AS `12h30`,
 SUM(
   CASE WHEN TIME(prod__downtime.cur_time) BETWEEN '07:30:00' AND '14:00:00' THEN prod__downtime.downtime ELSE 0
 END
 ) AS `14h00`,
 SUM(
   CASE WHEN TIME(prod__downtime.cur_time) BETWEEN '07:30:00' AND '15:00:00' THEN prod__downtime.downtime ELSE 0
 END
 ) AS `15h00`,
 SUM(
   CASE WHEN TIME(prod__downtime.cur_time) BETWEEN '07:30:00' AND '16:00:00' THEN prod__downtime.downtime ELSE 0
 END
 ) AS `16h00`,
 SUM(
   CASE WHEN TIME(prod__downtime.cur_time) BETWEEN '07:30:00' AND '16:30:00' THEN prod__downtime.downtime ELSE 0
 END
 ) AS `16h30`
 /*SUM(
   CASE WHEN TIME(prod__downtime.cur_time) BETWEEN '07:30:00' AND '17:10:00' THEN prod__downtime.downtime ELSE 0
 END
 ) AS `18h00`
 FROM
   prod__downtime
 WHERE
   prod__downtime.cur_date = DATE_FORMAT('$date', '%Y-%m-%d')  AND prod__downtime.prod_line LIKE '%$prod_line%'
 GROUP BY
   prod__downtime.operator
 ) t11
 ) t22
 ON
   t12.operatrice = t22.operatrice_pd INNER JOIN init__employee emp ON t12.operatrice = emp.matricule
 ORDER BY
   num ASC;";
   $res = mysqli_query($con, $sql);
   $pres = [];
   while ($item1 = $res->fetch_assoc()) {
     $pres[] = $item1;
   }

   $output .= "
     <html>
     <head>
       <style>
         .container {
           display: grid;
           grid-template-columns: 1fr 6fr 1fr;
           gap: 10px;
           height: 100px;
         }

             .logo {
           background-color: #fff;
           display: flex;
           justify-content: center;
           align-items: center;
           font-size: 16px;
           border: 1px solid #000;
           padding: 10px;
         }

             .formulaire {
           background-color: #fff;
           display: flex;
           justify-content: center;
           align-items: center;
           font-size: 16px;
           padding: 10px;
         }

             .model {
           background-color: #fff;
           display: flex;
           justify-content: center;
           align-items: center;
           font-size: 16px;
           padding: 10px;
         }

             .page-info {
           display: flex;
           justify-content: space-between;
           border-top: 1px solid transparent; 
           padding: 10px;
         }

             .page-info h3 {
           margin: 0;
         }

             table {
           width: 100%;
           border-collapse: collapse;
           margin-top: 20px;
         }

             th,
         td {
           padding: 10px;
           text-align: center;
           border: 1px solid #000;
         }

             th {
           background-color: #fff;
           color: #000;
         }
       </style>
     </head>
     <body>
       <div class='container'>
         <div class='formulaire'>
           <table>
             <tr>
             <td rowspan='2' colspan='2'> MahdCo <br> Tunisie </td>
               <td colspan='15'>Formulaire</td>
               <td colspan='5'>" . "</td>
             </tr>
             <tr>
               <td colspan='15'>Rendement de la chaine de fabrication</td>
               <td colspan='5'>Page 1 sur 1</td>
             </tr>
           <tr>
             <th style='border: 0' ><h3>Date:  " . $date . "</h3></th>
             <th style='border: 0'><h3>CHAINE:  " . $prod_line . "</h3></th>
           </tr>
           <tr>
             <th>NOM & PRENOM</th>
             <th>OPERATION</th>
             <th>MODELE</th>
             <th>07h30-08h30</th>
             <th>T.Perdu</th>
             <th>07h30-09h30</th>
             <th>T.Perdu</th>
             <th>07h30-10h30</th>
             <th>T.Perdu</th>
             <th>07h30-11h30</th>
             <th>T.Perdu</th>
             <th>07h30-12h30</th>
             <th>T.Perdu</th>
             <th>07h30-14h00</th>
             <th>T.Perdu</th>
             <th>07h30-15h00</th>
             <th>T.Perdu</th>
             <th>07h30-16h00</th>
             <th>T.Perdu</th>
             <th>07h30-16h30</th>
             <th>T.Perdu</th>
           </tr>
     ";
   for ($i = 0; $i < count($pres); $i++) {

     $output .= "
         <tr>
           <td>" . ($pres[$i]['operatrice']) . "</td>
           <td>" . ($pres[$i]['num_operation']) . "</td>
           <td>" . ($pres[$i]['model']) . "</td>
           <td>" . ($pres[$i]['08h30']) . "</td>
           <td>" . ($pres[$i]['08h30_pd']) . "</td>
           <td>" . ($pres[$i]['09h30']) . "</td>
           <td>" . ($pres[$i]['09h30_pd']) . "</td>
           <td>" . ($pres[$i]['10h30']) . "</td>
           <td>" . ($pres[$i]['10h30_pd']) . "</td>
           <td>" . ($pres[$i]['11h30']) . "</td>
           <td>" . ($pres[$i]['11h30_pd']) . "</td>
           <td>" . ($pres[$i]['12h30']) . "</td>
           <td>" . ($pres[$i]['12h30_pd']) . "</td>
           <td>" . ($pres[$i]['14h00']) . "</td>
           <td>" . ($pres[$i]['14h00_pd']) . "</td>
           <td>" . ($pres[$i]['15h00']) . "</td>
           <td>" . ($pres[$i]['15h00_pd']) . "</td>
           <td>" . ($pres[$i]['16h00']) . "</td>
           <td>" . ($pres[$i]['16h00_pd']) . "</td>
           <td>" . ($pres[$i]['16h30']) . "</td>
           <td>" . ($pres[$i]['16h30_pd']) . "</td>
     
         </tr>
         ";
   }

   $output .= "</table>
             </div>
         </div>
     </body>
 </html>
 ";

   header("Content-Type:application/xls");
   header("Content-Disposition: attachment; filename=perf_heure_CH_" . $prod_line . "_D_" . $date . "_M_" . $model . ".xls");

   echo $output;*/
  $sql2 = "SELECT schedule FROM prod__work_schedule WHERE id = 1";
  $result = $con->query($sql2);
  $schedule = [];

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $schedule = json_decode($row['schedule'], true);
  }
  $dayOfWeek = date('N', strtotime($date));

  $schedulePart = ($dayOfWeek >= 1 && $dayOfWeek <= 5) ? $schedule[0] : $schedule[1];

  $periods = [];
  foreach ($schedulePart as $period => $times) {
    if (!in_array($period, $periods)) {
      $periods[] = $period;
    }
  }
  $sql_pack = "
     SELECT 
         CONCAT(op.operator, '-', emp.first_name, ' ', emp.last_name) AS operatrice,
         CONCAT(MIN(op.operation_num), '-', MIN(op.designation)) AS num_operation,
         MIN(pg.qte_h) AS qte_h,
         MIN(op.model) AS model";

  foreach ($periods as $period) {
    // Remplacement des caractères invalides dans l'alias de colonne
    $formatted_period = str_replace(':', '_', $period);

    $start_time = $schedulePart[$period]['start'];
    $end_time = $schedulePart[$period]['end'];
    $sql_pack .= ",
         '$period' AS period_$formatted_period,
         0 AS downtime_$formatted_period,
         SUM(
             CASE 
                 WHEN TIME(op.cur_time) BETWEEN '$start_time' AND '$end_time'
                 THEN op.pack_qty
                 ELSE 0
             END
         ) AS pack_qty_$formatted_period";
  }

  $sql_pack .= "
  FROM 
      prod__pack_operation op
      JOIN init__employee emp ON op.operator = emp.matricule
      INNER JOIN init__model im ON im.model = op.model
      LEFT JOIN prod__gamme pg ON op.operation_num = pg.operation_num AND im.id = pg.model_id";

  if ($prod_line !== null && $prod_line !== 'Tous') {
    $sql_pack .= " WHERE op.prod_line = '$prod_line'";
  } else if ($prod_line == 'Tous') {
    $sql_pack .= " WHERE op.prod_line LIKE '%%'";
  }

  $sql_pack .= " AND op.cur_date = DATE_FORMAT('$date', '%Y-%m-%d') 
  GROUP BY 
      op.operator,
      op.operation_num,
      op.designation,
      op.model";


  $res_pack = mysqli_query($con, $sql_pack);
  $packResults = [];
  while ($row = $res_pack->fetch_assoc()) {
    $packResults[] = $row;
  }
  $sql_downtime = "
 SELECT 
 CONCAT(
   reqInter.operator,
   '-',
   emp.first_name,
   ' ',
   emp.last_name
 ) AS operatrice,
   null AS num_operation,
 null AS model,
 0 AS  qte_h
  ";
  foreach ($periods as $period) {
    $formatted_period = str_replace(':', '_', $period);
    $start_time = $schedulePart[$period]['start'];
    $end_time = $schedulePart[$period]['end'];
    $sql_downtime .= ",
     '$period' AS period_$formatted_period,
     0 AS pack_qty_$formatted_period,
     SUM(
         IF(
             TIME(reqMon.created_at) BETWEEN '$start_time' AND '$end_time'
             AND (TIME(reqEndM.created_at) BETWEEN '$start_time' AND '$end_time' OR reqEndM.created_at IS NULL),
            
             TIMESTAMPDIFF(MINUTE, reqMon.created_at, COALESCE(reqEndM.created_at, '$end_time')),
             0
         )
     ) AS downtime_$formatted_period";
  }
  $sql_downtime .= "
 FROM 
   aleas__req_interv reqInter
   JOIN init__employee emp ON reqInter.operator = emp.matricule
   JOIN aleas__mon_interv reqMon ON reqInter.id = reqMon.req_interv_id
   LEFT JOIN aleas__end_mon_interv reqEndM ON reqInter.id = reqEndM.req_interv_id";

  if ($prod_line !== null && $prod_line !== 'Tous') {
    $sql_downtime .= " WHERE reqInter.group = '$prod_line'";
  } else if ($prod_line == 'Tous') {
    $sql_downtime .= " WHERE reqInter.group LIKE '%%'";
  }
  $sql_downtime .= "  AND DATE(reqInter.created_at)='$date' 
 GROUP BY 
   reqInter.operator";

  $res_downtime = mysqli_query($con, $sql_downtime);
  $downtimeResults = [];
  while ($row = $res_downtime->fetch_assoc()) {
    $downtimeResults[] = $row;
  }
  $combinedResults = [];

  foreach ($packResults as $row) {
    $operator = $row['operatrice'];
    if (isset($combinedResults[$operator])) {
      foreach ($periods as $period) {
        $formatted_period = str_replace(':', '_', $period);
        $combinedResults[$operator]["pack_qty_$formatted_period"] += $row["pack_qty_$formatted_period"];
      }
    } else {

      $combinedResults[$operator] = $row;
    }
  }

  foreach ($downtimeResults as $row) {
    $operator = $row['operatrice'];
    if (isset($combinedResults[$operator])) {

      foreach ($periods as $period) {
        $formatted_period = str_replace(':', '_', $period);
        $combinedResults[$operator]["downtime_$formatted_period"] += $row["downtime_$formatted_period"];
      }
    } else {

      $combinedResults[$operator] = $row;
    }
  }

  $output = "
 <html xmlns:x='urn:schemas-microsoft-com:office:excel'>
 <head>
     <meta http-equiv='content-type' content='application/vnd.ms-excel; charset=UTF-8'>
 
        <style>
         .container {
           display: grid;
           grid-template-columns: 1fr 6fr 1fr;
           gap: 10px;
           height: 100px;
         }

             .logo {
           background-color: #fff;
           display: flex;
           justify-content: center;
           align-items: center;
           font-size: 16px;
           border: 1px solid #000;
           padding: 10px;
         }

             .formulaire {
           background-color: #fff;
           display: flex;
           justify-content: center;
           align-items: center;
           font-size: 16px;
           padding: 10px;
         }

             .model {
           background-color: #fff;
           display: flex;
           justify-content: center;
           align-items: center;
           font-size: 16px;
           padding: 10px;
         }

             .page-info {
           display: flex;
           justify-content: space-between;
           border-top: 1px solid transparent; 
           padding: 10px;
         }

             .page-info h3 {
           margin: 0;
         }

             table {
           width: 100%;
           border-collapse: collapse;
           margin-top: 20px;
         }

             th,
         td {
           padding: 10px;
           text-align: center;
           border: 1px solid #000;
         }

             th {
           background-color: #fff;
           color: #000;
         }
       </style>
   </head>
   <body>
     <div class='container'>
         <div class='formulaire'>
           <table>
             <tr>
             <td rowspan='2' colspan='2'> MahdCo <br> Tunisie </td>
               <td colspan='15'>Formulaire</td>
               <td colspan='5'>" . "</td>
             </tr>
             <tr>
               <td colspan='15'>Rendement de la chaine de fabrication</td>
               <td colspan='5'>Page 1 sur 1</td>
             </tr>
           <tr>
             <th style='border: 0' ><h3>Date:  " . $date . "</h3></th>
             <th style='border: 0'><h3>CHAINE:  " . $prod_line . "</h3></th>
           </tr>
           <tr>
           <th>NOM & PRENOM</th>
           <th>OPERATION</th>
           <th>MODELE</th>";
  foreach ($periods as $period) {
    $output .= "<th>$period</th><th>T.Perdu</th>";
  }

  $output .= "</tr>";
  foreach ($combinedResults as $operator => $data) {
    $model = $data['model'];
    $num_operation = $data['num_operation'];

    $output .= "<tr>
         <td>$operator</td>
         <td>$num_operation</td>
         <td>$model</td>"; // Assurez-vous d'avoir la bonne valeur pour 'Operation'

    foreach ($periods as $period) {
      $formatted_period = str_replace(':', '_', $period);
      $downtime = isset($data["downtime_$formatted_period"]) ? $data["downtime_$formatted_period"] : 0;
      $pack_qty = isset($data["pack_qty_$formatted_period"]) ? $data["pack_qty_$formatted_period"] : 0;
      $output .= "<td>{$pack_qty}" . /*"<hr>" . $data['qte_h'] .*/ "</td><td>{$downtime} minutes</td>";
    }

    $output .= "</tr>";
  }


  $output .= "
         </table>
       </div>
     </div>
   </body>
   </html>";
  header('Content-Type: application/xslx');
  header("Content-Disposition: attachment; filename=perf_heure_CH_" . $prod_line . "_D_" . $date . ".xls");

  echo $output;
}


if (isset($_POST["submit1"])) {
  $prod_line = isset($_POST["prod_line"]) ? $_POST["prod_line"] : null;
  $date_interval = isset($_POST["date"]) ? $_POST["date"] : null;

  $sql = "SELECT
  `prod__operator_perf`.`prod_line`,
  `prod__operator_perf`.`operator`,
  `prod__operator_perf`.`presence`,
  `init__employee`.`first_name`,
  `init__employee`.`last_name`,
  `init__employee`.`card_rfid`,
  `init__employee`.`qualification`,
  `prod__operator_perf`.`performance`,
  `prod__operator_perf`.`prod_time`,
  COALESCE(`total_downtime`.`downtime`, 0) AS `downtime`,
  COALESCE(`prod__overtime`.`overtime`, 0) AS `overtime`,
  `prod__operator_perf`.`cur_date`
FROM
  `prod__operator_perf`
INNER JOIN `init__employee` ON `prod__operator_perf`.`operator` = `init__employee`.`matricule`
LEFT JOIN `prod__overtime` ON `prod__overtime`.`operator` = `init__employee`.`matricule` AND `prod__operator_perf`.`cur_date` = `prod__overtime`.`cur_date`
LEFT JOIN(
  SELECT 
                                                    MAX(`aleas__req_interv`.`operator`) AS `operator`,
                                                    SUM(
                                                        TIMESTAMPDIFF(
                                                            MINUTE,
                                                            `aleas__mon_interv`.`created_at`,
                                                            `aleas__end_mon_interv`.`created_at`
                                                        )
                                                    ) AS `downtime`,
                                                    MAX(`aleas__end_mon_interv`.`created_at`) AS date_aleas
                                                FROM
                                                    `aleas__req_interv`
                                                LEFT JOIN `aleas__end_mon_interv` ON `aleas__end_mon_interv`.`req_interv_id` = `aleas__req_interv`.`id`
                                                LEFT JOIN `aleas__mon_interv` ON `aleas__mon_interv`.`req_interv_id` = `aleas__req_interv`.`id`
                                         
                                                GROUP BY
                                                    `aleas__req_interv`.`operator`,
                                                    DATE(`aleas__end_mon_interv`.`created_at`)) AS `total_downtime`
ON
  `prod__operator_perf`.`operator` = `total_downtime`.`operator` AND `prod__operator_perf`.`cur_date` = DATE(`total_downtime`.date_aleas)";

  if ($prod_line !== null && $prod_line !== 'Tous') {
    $sql .= " WHERE `prod__operator_perf`.`prod_line` = '$prod_line'";
  }
  if ($prod_line == 'Tous') {
    $sql .= " WHERE `prod__operator_perf`.`prod_line` LIKE '%%'";
  }
  if ($date_interval !== null) {
    $sql .= " AND `prod__operator_perf`.`cur_date` = DATE_FORMAT('$date_interval', '%Y-%m-%d')";
  }

  $res = mysqli_query($con, $sql);
  $pres = [];
  while ($item1 = $res->fetch_assoc()) {
    $pres[] = $item1;
  }
  $output .= "
            <table class='table' bordered='1'>
                                
            <tr>
            <th>Matricule</th>
            <th>Nom et Prenom</th>
            <th>Chaine de production</th>
            <th>Temps de présence</th>
            <th>Temps perdu</th>
            <th>Performance</th>
            <th>Minutes produites</th>
            <th>Date</th>
            
        </tr>
    ";
  for ($i = 0; $i < count($pres); $i++) {

    $output .= '
        <tr>
                                    <td>' . ($pres[$i]["operator"]) . '</td>
                                    <td>' . ($pres[$i]["first_name"] . ' ' . $pres[$i]["last_name"]) . '</td>
                                    <td>' . ($pres[$i]["prod_line"]) . '</td>
                                    <td>' . ($pres[$i]["presence"]) . '</td>
                                    <td>' . ($pres[$i]["downtime"]) . '</td>
                                    <td>' . ($pres[$i]["performance"]) . '</td>
                                    <td>' . ($pres[$i]["prod_time"]) . '</td>
                                    <td>' . ($pres[$i]["cur_date"]) . '</td>
                                    </tr>
        ';
  }

  $output .= '</table>';

  header('Content-Type:application/xls');
  header('Content-Disposition: attachment; filename=performance_journaliere.xls');

  echo $output;
}
if (isset($_POST["submit3"])) {
  $prod_line = isset($_POST["prod_line"]) ? $_POST["prod_line"] : null;
  $operatrice = isset($_POST["operatrice"]) ? $_POST["operatrice"] : null;
  $date = isset($_POST["date"]) ? $_POST["date"] : null;

  $sql = "SELECT
  CONCAT(emp.first_name, ' ', emp.last_name) AS optc,
  ppo.pack_num AS `pack_num`,
  ppo.pack_qty AS `pack_qty`,
  MAX(
      CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'M01' THEN ppd.defect_num
  END
) AS `M01`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'M02' THEN ppd.defect_num
END
) AS `M02`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'M03' THEN ppd.defect_num
END
) AS `M03`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'M04' THEN ppd.defect_num
END
) AS `M04`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'M05' THEN ppd.defect_num
END
) AS `M05`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'M06' THEN ppd.defect_num
END
) AS `M06`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'M07' THEN ppd.defect_num
END
) AS `M07`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'M08' THEN ppd.defect_num
END
) AS `M08`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'M09' THEN ppd.defect_num
END
) AS `M09`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'M10' THEN ppd.defect_num
END
) AS `M10`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'M11' THEN ppd.defect_num
END
) AS `M11`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'M12' THEN ppd.defect_num
END
) AS `M12`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'M13' THEN ppd.defect_num
END
) AS `M13`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'M14' THEN ppd.defect_num
END
) AS `M14`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F01' THEN ppd.defect_num
END
) AS `F01`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F02' THEN ppd.defect_num
END
) AS `F02`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F03' THEN ppd.defect_num
END
) AS `F03`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F04' THEN ppd.defect_num
END
) AS `F04`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F05' THEN ppd.defect_num
END
) AS `F05`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F06' THEN ppd.defect_num
END
) AS `F06`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F07' THEN ppd.defect_num
END
) AS `F07`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F08' THEN ppd.defect_num
END
) AS `F08`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F09' THEN ppd.defect_num
END
) AS `F09`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F10' THEN ppd.defect_num
END) AS `F10`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F11' THEN ppd.defect_num
END
) AS `F11`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F12' THEN ppd.defect_num
END
) AS `F12`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F13' THEN ppd.defect_num
END
) AS `F13`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F14' THEN ppd.defect_num
END
) AS `F14`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F15' THEN ppd.defect_num
END
) AS `F15`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F16' THEN ppd.defect_num
END
) AS `F16`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F17' THEN ppd.defect_num
END
) AS `F17`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F18' THEN ppd.defect_num
END
) AS `F18`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F19' THEN ppd.defect_num
END
) AS `F19`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F20' THEN ppd.defect_num
END) AS `F20`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F21' THEN ppd.defect_num
END
) AS `F21`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F22' THEN ppd.defect_num
END
) AS `F22`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F23' THEN ppd.defect_num
END
) AS `F23`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F24' THEN ppd.defect_num
END
) AS `F24`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F25' THEN ppd.defect_num
END
) AS `F25`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F26' THEN ppd.defect_num
END
) AS `F26`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F27' THEN ppd.defect_num
END
) AS `F27`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F28' THEN ppd.defect_num
END
) AS `F28`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F29' THEN ppd.defect_num
END
) AS `F29`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F20' THEN ppd.defect_num
END) AS `F30`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F31' THEN ppd.defect_num
END
) AS `F31`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F32' THEN ppd.defect_num
END
) AS `F32`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F33' THEN ppd.defect_num
END
) AS `F33`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F34' THEN ppd.defect_num
END
) AS `F34`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F35' THEN ppd.defect_num
END
) AS `F35`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F36' THEN ppd.defect_num
END
) AS `F36`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F37' THEN ppd.defect_num
END
) AS `F37`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F38' THEN ppd.defect_num
END
) AS `F38`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F39' THEN ppd.defect_num
END
) AS `F39`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F40' THEN ppd.defect_num
END) AS `F40`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F41' THEN ppd.defect_num
END
) AS `F41`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F42' THEN ppd.defect_num
END
) AS `F42`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F43' THEN ppd.defect_num
END
) AS `F43`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F44' THEN ppd.defect_num
END
) AS `F44`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F45' THEN ppd.defect_num
END
) AS `F45`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F46' THEN ppd.defect_num
END
) AS `F46`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F47' THEN ppd.defect_num
END
) AS `F47`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F48' THEN ppd.defect_num
END
) AS `F48`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F49' THEN ppd.defect_num
END
) AS `F49`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F50' THEN ppd.defect_num
END) AS `F50`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F51' THEN ppd.defect_num
END
) AS `F51`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F52' THEN ppd.defect_num
END
) AS `F52`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F53' THEN ppd.defect_num
END
) AS `F53`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F54' THEN ppd.defect_num
END
) AS `F54`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F55' THEN ppd.defect_num
END
) AS `F55`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F56' THEN ppd.defect_num
END
) AS `F56`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F57' THEN ppd.defect_num
END
) AS `F57`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F58' THEN ppd.defect_num
END
) AS `F58`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F59' THEN ppd.defect_num
END
) AS `F59`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F60' THEN ppd.defect_num
END) AS `F60`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F61' THEN ppd.defect_num
END
) AS `F61`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F62' THEN ppd.defect_num
END
) AS `F62`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F63' THEN ppd.defect_num
END
) AS `F63`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F64' THEN ppd.defect_num
END
) AS `F64`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F65' THEN ppd.defect_num
END
) AS `F65`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F66' THEN ppd.defect_num
END
) AS `F66`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F67' THEN ppd.defect_num
END
) AS `F67`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F68' THEN ppd.defect_num
END
) AS `F68`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F69' THEN ppd.defect_num
END
) AS `F69`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'F70' THEN ppd.defect_num
END
) AS `F70`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'FI01' THEN ppd.defect_num
END
) AS `FI01`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'FI02' THEN ppd.defect_num
END
) AS `FI02`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'FI03' THEN ppd.defect_num
END
) AS `FI03`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'FI04' THEN ppd.defect_num
END
) AS `FI04`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'FI05' THEN ppd.defect_num
END
) AS `FI05`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'FI06' THEN ppd.defect_num
END
) AS `FI06`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'FI07' THEN ppd.defect_num
END
) AS `FI07`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'FI11' THEN ppd.defect_num
END
) AS `FI08`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'FI09' THEN ppd.defect_num
END
) AS `FI09`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'FI10' THEN ppd.defect_num
END
) AS `FI10`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'FI11' THEN ppd.defect_num
END
) AS `FI11`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'FI12' THEN ppd.defect_num
END
) AS `FI12`,
MAX(
  CASE WHEN pc.pack_num = ppo.pack_num AND ppd.defect_code = 'FI13' THEN ppd.defect_num
END
) AS `FI13`,
MAX(pcc.defects_num) AS defectueux,
MAX(pcc.defective_pcs) AS `def`
FROM
  `prod__pack_operation` ppo
JOIN `prod__eol_control` pc /* ON ppo.pack_num = pc.pack_num */
JOIN `prod__eol_pack_defect` ppd ON
  pc.pack_num = ppd.pack_num
JOIN `init__employee` emp ON
  ppo.operator = emp.matricule
JOIN `prod__eol_control` pcc ON
  pcc.pack_num = ppo.pack_num
WHERE
  ppo.`operator` = '$operatrice' AND ppo.`prod_line` = '$prod_line' AND ppo.`cur_date` = DATE_FORMAT('$date', '%Y-%m-%d')
GROUP BY
  ppo.pack_num,
  ppo.pack_qty;";
  $res = mysqli_query($con, $sql);
  $row = [];
  while ($item1 = $res->fetch_assoc()) {
    $row[] = $item1;
  }

  $output .= "<html>
  <head>
    <style>
      .container {
        display: grid;
        grid-template-columns: 1fr 6fr 1fr;
        gap: 10px;
        height: 100px;
      }

      .logo {
        background-color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 16px;
        border: 1px solid #000;
        padding: 10px;
      }

      .formulaire {
        background-color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 16px;
        padding: 10px;
      }

      .model {
        background-color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 16px;
        padding: 10px;
      }

      .page-info {
        display: flex;
        justify-content: space-between;
        border-top: 1px solid transparent;
        padding: 10px;
      }

      .page-info h3 {
        margin: 0;
      }

      table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
      }

      th,
      td {
        padding: 10px;
        text-align: center;
        border: 1px solid #000;
      }

      th {
        background-color: #fff;
        color: #000;
      }
      .monTexte {
        writing-mode: vertical-lr;
        text-orientation: lr;
      }
      .v-line {
        border-left: thick solid #000;
        height: 100%;
        left: 50%;
        position: absolute;
      }
    </style>
  </head>
  <body>
    <div class='container'>
      <div class='formulaire'>
        <table>
          <tr>
            <td rowspan='2' colspan='4'>
              MahdCo
            </td>
            <td rowspan='2' colspan='8'>
              Formulaire <br />
              Contrôle Qualité Bout de Chaine
            </td>
            <td rowspan='2' colspan='4'>Page 1 sur 1</td>
          </tr>
          <tr></tr>

          <tr>
            <td style='border: 0' colspan='4'><h3>Date: " . $date . "</h3></td>
            <td style='border: 0' colspan='4'>
              <h3>Controlleuse: " . $operatrice . "-" . $row[0]['optc'] . "</h3>
            </td>
            <td style='border: 0' colspan='4'>
              <h3>GRP: " . $prod_line . "</h3>
            </td>
          </tr>
          <tr>
            <th style='border: 0'></th>
            <th style='border: 0'></th>
            <th style='border: 0'></th>";
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th>Numéro de paquet</th>
            <th>Quantité de paquet</th>";
  }
  $output .= "<th rowspan='2'> TOTALE </th></tr>
          <tr>
            <th style='border: 0'></th>
            <th>CODE DEFAUT</th>
            <th>DEFAUT</th>";
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th>" . $row[$i]['pack_num'] . "</th>
              <th>" . $row[$i]['pack_qty'] . "</th>";
  }

  $output .= "
  </tr>
          <!-- ------------------------------------------------------------------------------------------------------ -->
          <tr>
            <th rowspan='14' class='monTexte'>Défauts Matière</th>
            <th>M01</th>
            <th>Défaut d'impression (Tâche encre, uniformité des couleurs des motifs impression…) </th>";
  $SUMM01 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['M01'] . "</th>";
    $SUMM01 += $row[$i]['M01'];
  }
  $output .= "<th>" . $SUMM01 . "</th>";
  $output .= "
          </tr>
          <tr>
            <th>M02</th>
            <th>Défaut tricôtage</th>";
  $SUMM02 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['M02'] . "</th>";
    $SUMM02 += $row[$i]['M02'];
  }
  $output .= "<th>" . $SUMM02 . "</th>";
  $output .= "
          </tr>
          <tr>
            <th>M03</th>
            <th>Trou</th>";
  $SUMM03 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['M03'] . "</th>";
    $SUMM03 += $row[$i]['M03'];
  }
  $output .= "<th>" . $SUMM03 . "</th>";
  $output .= "
          </tr>
          <tr>
            <th>M04</th>
            <th> </th>";
  $SUMM04 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['M04'] . "</th>";
    $SUMM04 += $row[$i]['M04'];
  }
  $output .= "<th>" . $SUMM04 . "</th>";
  $output .= "
          </tr>
          <tr>
          <th>M05</th>
            <th>Grattage</th>";
  $SUMM05 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['M05'] . "</th>";
    $SUMM05 += $row[$i]['M05'];
  }
  $output .= "<th>" . $SUMM05 . "</th>";
  $output .= "
          </tr>
          <tr>
            <th>M06</th>
            <th>Nœud visible à l'endroit</th>";
  $SUMM06 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['M06'] . "</th>";
    $SUMM06 += $row[$i]['M06'];
  }
  $output .= "<th>" . $SUMM06 . "</th>";
  $output .= "
          </tr>
          <tr>
            <th>M07</th>
            <th>Nuance</th>";
  $SUMM07 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['M07'] . "</th>";
    $SUMM07 += $row[$i]['M07'];
  }
  $output .= "<th>" . $SUMM07 . "</th>";
  $output .= "
          </tr>
          <tr>
            <th>M08</th>
            <th>Nuance bonnet</th>";
  $SUMM08 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['M08'] . "</th>";
    $SUMM08 += $row[$i]['M08'];
  }
  $output .= "<th>" . $SUMM08 . "</th>";
  $output .= "
          </tr>
          <tr>
            <th>M09</th>
            <th>Accessoire nuancé</th>";
  $SUMM09 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['M09'] . "</th>";
    $SUMM09 += $row[$i]['M09'];
  }
  $output .= "<th>" . $SUMM09 . "</th>";
  $output .= "
          </tr>
          <tr>
            <th>M10</th>
            <th>Accessoire blessant ou irritant (coupante …)</th>";
  $SUMM10 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['M10'] . "</th>";
    $SUMM10 += $row[$i]['M10'];
  }
  $output .= "<th>" . $SUMM10 . "</th";
  $output .= "
          </tr>
          <tr>
            <th>M11</th>
            <th>Défaut sens tissu</th>";
  $SUMM11 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['M11'] . "</th>";
    $SUMM11 += $row[$i]['M11'];
  }
  $output .= "<th>" . $SUMM11 . "</th>";
  $output .= "
          </tr>
          <tr>
            <th>M12</th>
            <th>Fil tiré</th>";
  $SUMM12 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['M12'] . "</th>";
    $SUMM12 += $row[$i]['M12'];
  }
  $output .= "<th>" . $SUMM12 . "</th>";
  $output .= "
          </tr>
          <tr>
            <th>M13</th>
            <th>Accessoire nuancé</th>";
  $SUMM13 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['M13'] . "</th>";
    $SUMM13 += $row[$i]['M13'];
  }
  $output .= "<th>" . $SUMM13 . "</th>";
  $output .= "
          </tr>
          <tr>
            <th>M14</th>
            <th>Accessoire blessant ou irritant (coupante …)</th>";
  $SUMM14 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['M14'] . "</th>";
    $SUMM14 += $row[$i]['M14'];
  }
  $output .= "<th>" . $SUMM14 . "</th>";
  $output .= "
          </tr>
          <tr>
          <th rowspan='70' class='monTexte'>Défauts Fabrication</th>
            <th>F01</th>
            <th>Envers et endroit des composant de l'article</th>";
  $SUMF01 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F01'] . "</th>";
    $SUMF01 += $row[$i]['F01'];
  }
  $output .= "<th>" . $SUMF01 . "</th>";
  $output .= "
          </tr>
          <tr>
            <th>F02</th>
            <th>Point sauté</th>";
  $SUMF02 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F02'] . "</th>";
    $SUMF02 += $row[$i]['F02'];
  }
  $output .= "<th>" . $SUMF02 . "</th>";
  $output .= "
          </tr>
          <tr>
            <th>F03</th>
            <th>Point cassé</th>";
  $SUMF03 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F03'] . "</th>";
    $SUMF03 += $row[$i]['F03'];
  }
  $output .= "<th>" . $SUMF03 . "</th>";
  $output .= "
          </tr>
          <tr>
            <th>F04</th>
            <th>Mauvais réglage  ( point trop lâché)</th>";
  $SUMF04 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F04'] . "</th>";
    $SUMF04 += $row[$i]['F04'];
  }
  $output .= "<th>" . $SUMF04 . "</th>";
  $output .= "
          </tr>
          <tr>
          <th>F05</th>
          <th>Plis</th>";
  $SUMF05 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F05'] . "</th>";
    $SUMF05 += $row[$i]['F05'];
  }
  $output .= "<th>" . $SUMF05 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F06</th>
          <th>Couture échappée</th>";
  $SUMF06 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F06'] . "</th>";
    $SUMF06 += $row[$i]['F06'];
  }
  $output .= "<th>" . $SUMF06 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F07</th>
          <th>Couture irrégulière</th>";
  $SUMF07 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F07'] . "</th>";
    $SUMF07 += $row[$i]['F07'];
  }
  $output .= "<th>" . $SUMF07 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F08</th>
          <th>Surpiqûre  échappée</th>";
  $SUMF08 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F08'] . "</th>";
    $SUMF08 += $row[$i]['F08'];
  }
  $output .= "<th>" . $SUMF08 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F09</th>
          <th>Surpiqûre irrégulière</th>";
  $SUMF09 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F09'] . "</th>";
    $SUMF09 += $row[$i]['F09'];
  }
  $output .= "<th>" . $SUMF09 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F10</th>
          <th>Elastique échappé</th>";
  $SUMF10 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F10'] . "</th>";
    $SUMF10 += $row[$i]['F10'];
  }
  $output .= "<th>" . $SUMF10 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F11</th>
          <th>Accessoire  décousue</th>";
  $SUMF11 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F11'] . "</th>";
    $SUMF11 += $row[$i]['F11'];
  }
  $output .= "<th>" . $SUMF11 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F12</th>
          <th>Accessoire  décentré</th>";
  $SUMF12 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F12'] . "</th>";
    $SUMF12 += $row[$i]['F12'];
  }
  $output .= "<th>" . $SUMF12 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F13</th>
          <th>Accessoire de travers</th>";
  $SUMF13 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F13'] . "</th>";
    $SUMF13 += $row[$i]['F13'];
  }
  $output .= "<th>" . $SUMF13 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F14</th>
          <th>Platitude bas entregorge  (aspect non plat)</th>";
  $SUMF14 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F14'] . "</th>";
    $SUMF14 += $row[$i]['F14'];
  }
  $output .= "<th>" . $SUMF14 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F15</th>
          <th>Dessous ( doublure ) visible à l'endroit </th>";
  $SUMF15 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F15'] . "</th>";
    $SUMF15 += $row[$i]['F15'];
  }
  $output .= "<th>" . $SUMF15 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F16</th>
          <th>Trou d'aiguille</th>";
  $SUMF16 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F16'] . "</th>";
    $SUMF16 += $row[$i]['F16'];
  }
  $output .= "<th>" . $SUMF16 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F17</th>
          <th>Assymétrie taille( devant ou dos)</th>";
  $SUMF17 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F17'] . "</th>";
    $SUMF17 += $row[$i]['F17'];
  }
  $output .= "<th>" . $SUMF17 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F18</th>
          <th>Assymétrie décolleté</th>";
  $SUMF18 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F18'] . "</th>";
    $SUMF18 += $row[$i]['F18'];
  }
  $output .= "<th>" . $SUMF18 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F19</th>
          <th>Assymétrie emmanchure</th>";
  $SUMF19 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F19'] . "</th>";
    $SUMF19 += $row[$i]['F19'];
  }
  $output .= "<th>" . $SUMF19 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F20</th>
          <th>Assymétrie dos (haut ou bas)</th>";
  $SUMF20 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F20'] . "</th>";
    $SUMF20 += $row[$i]['F20'];
  }
  $output .= "<th>" . $SUMF20 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F21</th>
          <th>Assymétrie bretelle</th>";
  $SUMF21 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F21'] . "</th>";
    $SUMF21 += $row[$i]['F21'];
  }
  $output .= "<th>" . $SUMF21 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F22</th>
          <th>Assymétrie position bretelle</th>";
  $SUMF22 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F22'] . "</th>";
    $SUMF22 += $row[$i]['F22'];
  }
  $output .= "<th>" . $SUMF22 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F23</th>
          <th>Assymétrie cuisse</th>";
  $SUMF23 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F23'] . "</th>";
    $SUMF23 += $row[$i]['F23'];
  }
  $output .= "<th>" . $SUMF23 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F24</th>
          <th>Assymétrie côté</th>";
  $SUMF24 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F24'] . "</th>";
    $SUMF24 += $row[$i]['F24'];
  }
  $output .= "<th>" . $SUMF24 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F25</th>
          <th>Assymétrie découpé</th>";
  $SUMF25 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F25'] . "</th>";
    $SUMF25 += $row[$i]['F25'];
  }
  $output .= "<th>" . $SUMF25 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F26</th>
          <th>Assymétrie pince</th>";
  $SUMF26 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F26'] . "</th>";
    $SUMF26 += $row[$i]['F26'];
  }
  $output .= "<th>" . $SUMF26 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F27</th>
          <th>Assymétrie tour bonnet </th>";
  $SUMF27 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F27'] . "</th>";
    $SUMF27 += $row[$i]['F27'];
  }
  $output .= "<th>" . $SUMF27 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F28</th>
          <th>Mélange taille bretelle</th>";
  $SUMF28 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F28'] . "</th>";
    $SUMF28 += $row[$i]['F28'];
  }
  $output .= "<th>" . $SUMF28 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F29</th>
          <th>Bretelle déformé</th>";
  $SUMF29 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F29'] . "</th>";
    $SUMF29 += $row[$i]['F29'];
  }
  $output .= "<th>" . $SUMF29 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F30</th>
          <th>Griffe ou vignette incliné</th>";
  $SUMF30 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F30'] . "</th>";
    $SUMF30 += $row[$i]['F30'];
  }
  $output .= "<th>" . $SUMF30 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F31</th>
          <th>Griffe ou vignette décousu</th>";
  $SUMF31 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F31'] . "</th>";
    $SUMF31 += $row[$i]['F31'];
  }
  $output .= "<th>" . $SUMF31 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F32</th>
          <th>Griffe de travers</th>";
  $SUMF32 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F32'] . "</th>";
    $SUMF32 += $row[$i]['F32'];
  }
  $output .= "<th>" . $SUMF32 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F33</th>
          <th>Mélange taille d'agraffe</th>";
  $SUMF33 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F33'] . "</th>";
    $SUMF33 += $row[$i]['F33'];
  }
  $output .= "<th>" . $SUMF33 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F34</th>
          <th>Fronces mal réparties (bonnet, emmanchures, cuisse, découpes …)</th>";
  $SUMF34 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F34'] . "</th>";
    $SUMF34 += $row[$i]['F34'];
  }
  $output .= "<th>" . $SUMF34 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F35</th>
          <th>Fil de fronce apparent  à l'endroit</th>";
  $SUMF35 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F35'] . "</th>";
    $SUMF35 += $row[$i]['F35'];
  }
  $output .= "<th>" . $SUMF35 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F36</th>
          <th>Glaçage visible à l'endroit </th>";
  $SUMF36 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F36'] . "</th>";
    $SUMF36 += $row[$i]['F36'];
  }
  $output .= "<th>" . $SUMF36 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F37</th>
          <th>Trace d'aiguille</th>";
  $SUMF37 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F37'] . "</th>";
    $SUMF37 += $row[$i]['F37'];
  }
  $output .= "<th>" . $SUMF37 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F38</th>
          <th>Point d'arrêt mal racordé</th>";
  $SUMF38 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F38'] . "</th>";
    $SUMF38 += $row[$i]['F38'];
  }
  $output .= "<th>" . $SUMF38 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F39</th>
          <th>Manque point d'arrêt</th>";
  $SUMF39 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F39'] . "</th>";
    $SUMF39 += $row[$i]['F39'];
  }
  $output .= "<th>" . $SUMF39 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F40</th>
          <th>Manque Accessoire</th>";
  $SUMF40 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F40'] . "</th>";
    $SUMF40 += $row[$i]['F40'];
  }
  $output .= "<th>" . $SUMF40 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F41</th>
          <th>Vrillage</th>";
  $SUMF41 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F41'] . "</th>";
    $SUMF41 += $row[$i]['F41'];
  }
  $output .= "<th>" . $SUMF41 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F42</th>
          <th>Ondulé (décolleté, emmanchures, centre, cuisse, découpes ..)</th>";
  $SUMF42 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F42'] . "</th>";
    $SUMF42 += $row[$i]['F42'];
  }
  $output .= "<th>" . $SUMF42 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F43</th>
          <th>Présence d'éléments étrangers (aiguilles, épingle, bout d'aiguille cassé …)</th>";
  $SUMF43 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F43'] . "</th>";
    $SUMF43 += $row[$i]['F43'];
  }
  $output .= "<th>" . $SUMF43 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F44</th>
          <th>Défaut repassage (faux pli, Mal repassé …)</th>";
  $SUMF44 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F44'] . "</th>";
    $SUMF44 += $row[$i]['F44'];
  }
  $output .= "<th>" . $SUMF44 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F45</th>
          <th>Absence d'étiquette ou information</th>";
  $SUMF45 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F45'] . "</th>";
    $SUMF45 += $row[$i]['F45'];
  }
  $output .= "<th>" . $SUMF45 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F46</th>
          <th>Ecriture vignette illisble</th>";
  $SUMF46 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F46'] . "</th>";
    $SUMF46 += $row[$i]['F46'];
  }
  $output .= "<th>" . $SUMF46 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F47</th>
          <th>Mélange de taille</th>";
  $SUMF47 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F47'] . "</th>";
    $SUMF47 += $row[$i]['F47'];
  }
  $output .= "<th>" . $SUMF47 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F48</th>
          <th>Erreur écriture du logo (transfert, sérigraphie, broderie …)</th>";
  $SUMF48 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F48'] . "</th>";
    $SUMF48 += $row[$i]['F48'];
  }
  $output .= "<th>" . $SUMF48 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F49</th>
          <th>Absence de marquage du logo (transfert, sérigraphie, broderie …)</th>";
  $SUMF49 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F49'] . "</th>";
    $SUMF49 += $row[$i]['F49'];
  }
  $output .= "<th>" . $SUMF49 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F50</th>
          <th>Craquage</th>";
  $SUMF50 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F50'] . "</th>";
    $SUMF50 += $row[$i]['F50'];
  }
  $output .= "<th>" . $SUMF50 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F51</th>
          <th>Point filant au rasage</th>";
  $SUMF51 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F51'] . "</th>";
    $SUMF51 += $row[$i]['F51'];
  }
  $output .= "<th>" . $SUMF51 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F52</th>
          <th>Sens de couture</th>";
  $SUMF52 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F52'] . "</th>";
    $SUMF52 += $row[$i]['F52'];
  }
  $output .= "<th>" . $SUMF52 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F53</th>
          <th>Valeur de couture (non respecté)</th>";
  $SUMF53 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F53'] . "</th>";
    $SUMF53 += $row[$i]['F53'];
  }
  $output .= "<th>" . $SUMF53 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F54</th>
          <th>Nombre de points/ cm (non respecté)</th>";
  $SUMF54 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F54'] . "</th>";
    $SUMF54 += $row[$i]['F54'];
  }
  $output .= "<th>" . $SUMF54 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F55</th>
          <th>Longueur raccord insuffisant</th>";
  $SUMF55 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F55'] . "</th>";
    $SUMF55 += $row[$i]['F55'];
  }
  $output .= "<th>" . $SUMF55 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F56</th>
          <th>Jeu armarure</th>";
  $SUMF56 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F56'] . "</th>";
    $SUMF56 += $row[$i]['F56'];
  }
  $output .= "<th>" . $SUMF56 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F57</th>
          <th>PA non solide</th>";
  $SUMF57 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F57'] . "</th>";
    $SUMF57 += $row[$i]['F57'];
  }
  $output .= "<th>" . $SUMF57 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F58</th>
          <th>PA décalé</th>";
  $SUMF58 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F58'] . "</th>";
    $SUMF58 += $row[$i]['F58'];
  }
  $output .= "<th>" . $SUMF58 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F59</th>
          <th>Bride incliné</th>";
  $SUMF59 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F59'] . "</th>";
    $SUMF59 += $row[$i]['F59'];
  }
  $output .= "<th>" . $SUMF59 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F60</th>
          <th>Bretelle mal monté au niveau réglages</th>";
  $SUMF60 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F60'] . "</th>";
    $SUMF60 += $row[$i]['F60'];
  }
  $output .= "<th>" . $SUMF60 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F61</th>
          <th>Accessoire décoratif mal monté</th>";
  $SUMF61 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F61'] . "</th>";
    $SUMF61 += $row[$i]['F61'];
  }
  $output .= "<th>" . $SUMF61 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F62</th>
          <th>Non élasticité de la coutur : la couture ne craque pas mais bloquée</th>";
  $SUMF62 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F62'] . "</th>";
    $SUMF62 += $row[$i]['F62'];
  }
  $output .= "<th>" . $SUMF62 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F63</th>
          <th>Bretelle retourné</th>";
  $SUMF63 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F63'] . "</th>";
    $SUMF63 += $row[$i]['F63'];
  }
  $output .= "<th>" . $SUMF63 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F64</th>
          <th>Recommodage mal fait</th>";
  $SUMF64 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F64'] . "</th>";
    $SUMF64 += $row[$i]['F64'];
  }
  $output .= "<th>" . $SUMF64 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F65</th>
          <th>Trace lustrage</th>";
  $SUMF65 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F65'] . "</th>";
    $SUMF65 += $row[$i]['F65'];
  }
  $output .= "<th>" . $SUMF65 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F66</th>
          <th> &nbsp;</th>";
  $SUMF66 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F66'] . "</th>";
    $SUMF66 += $row[$i]['F66'];
  }
  $output .= "<th>" . $SUMF66 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F67</th>
          <th>Pince irrégulier (mal fait)</th>";
  $SUMF67 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F67'] . "</th>";
    $SUMF67 += $row[$i]['F67'];
  }
  $output .= "<th>" . $SUMF67 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F68</th>
          <th>Platitude basque</th>";
  $SUMF68 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F68'] . "</th>";
    $SUMF68 += $row[$i]['F68'];
  }
  $output .= "<th>" . $SUMF68 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F69</th>
          <th>Enfilage armature inversé</th>";
  $SUMF69 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F69'] . "</th>";
    $SUMF69 += $row[$i]['F69'];
  }
  $output .= "<th>" . $SUMF69 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>F70</th>
          <th>Bretelle inversé</th>";
  $SUMF70 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['F70'] . "</th>";
    $SUMF70 += $row[$i]['F70'];
  }
  $output .= "<th>" . $SUMF70 . "</th>";
  $output .= "
        </tr>
        <!-- ------------------------------------------------------------------------------------------------------ -->
        <tr>
        <th rowspan='13' class='monTexte'>Défauts Matière</th>
          <th>FI01</th>
          <th>Article non épluché</th>";
  $SUMFI01 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['FI01'] . "</th>";
    $SUMFI01 += $row[$i]['FI01'];
  }
  $output .= "<th>" . $SUMFI01 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>FI02</th>
          <th>Tâche visible à l'endroit</th>";
  $SUMFI02 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['FI02'] . "</th>";
    $SUMFI02 += $row[$i]['FI02'];
  }
  $output .= "<th>" . $SUMFI02 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>FI03</th>
          <th>Tâche à l'intérieur (petit)</th>";
  $SUMFI03 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['FI03'] . "</th>";
    $SUMFI03 += $row[$i]['FI03'];
  }
  $output .= "<th>" . $SUMFI03 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>FI04</th>
          <th>Coupe ciseaux</th>";
  $SUMFI04 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['FI04'] . "</th>";
    $SUMFI04 += $row[$i]['FI04'];
  }
  $output .= "<th>" . $SUMFI04 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>FI05</th>
          <th>Fil non coupé</th>";
  $SUMFI05 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['FI05'] . "</th>";
    $SUMFI05 += $row[$i]['FI05'];
  }
  $output .= "<th>" . $SUMFI05 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>FI06</th>
          <th>Elastique coupé</th>";
  $SUMFI06 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['FI06'] . "</th>";
    $SUMFI06 += $row[$i]['FI06'];
  }
  $output .= "<th>" . $SUMFI06 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>FI07</th>
          <th>Excédent au niveau armature ( asepct non propre )</th>";
  $SUMFI07 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['FI07'] . "</th>";
    $SUMFI07 += $row[$i]['FI07'];
  }
  $output .= "<th>" . $SUMFI07 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>FI08</th>
          <th>Trace de pointage </th>";
  $SUMFI08 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['FI08'] . "</th>";
    $SUMFI08 += $row[$i]['FI08'];
  }
  $output .= "<th>" . $SUMFI08 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>FI09</th>
          <th>Bretelle mal dégarni</th>";
  $SUMFI09 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['FI09'] . "</th>";
    $SUMFI09 += $row[$i]['FI09'];
  }
  $output .= "<th>" . $SUMFI09 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>FI10</th>
          <th>Défaut marquage</th>";
  $SUMFI10 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['FI10'] . "</th>";
    $SUMFI10 += $row[$i]['FI10'];
  }
  $output .= "<th>" . $SUMFI10 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>FI11</th>
          <th>Bretelle décalé dû au mal reglage</th>";
  $SUMFI11 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['FI11'] . "</th>";
    $SUMFI11 += $row[$i]['FI11'];
  }
  $output .= "<th>" . $SUMFI11 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>FI12</th>
          <th>Nœud décoratif mal fait</th>";
  $SUMFI12 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['FI12'] . "</th>";
    $SUMFI12 += $row[$i]['FI12'];
  }
  $output .= "<th>" . $SUMFI12 . "</th>";
  $output .= "
        </tr>
        <tr>
          <th>FI13</th>
          <th>Ecran visble à l'endroit</th>";
  $SUMFI13 = 0;
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['FI13'] . "</th>";
    $SUMFI13 += $row[$i]['FI13'];
  }
  $output .= "<th>" . $SUMFI13 . "</th>";
  $output .= "
        </tr>
        <!-- ------------------------------------------------------------------------------------------------------ -->
        <tr>
          <th colspan='3'>TOTAL DEFECTUEUX\DEFAUTS </th>";
  for ($i = 0; $i < count($row); $i++) {
    $output .= "<th colspan='2'>" . $row[$i]['def'] . " \ " . $row[$i]['defectueux'] . "</th>";
  }
  $output .= "
        </tr>
        </table>
      </div>
    </div>
  </body>
</html>";
  $output .= '</table>';

  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment; filename=CQ_BCh' . $date . '.xls');

  echo $output;
}
