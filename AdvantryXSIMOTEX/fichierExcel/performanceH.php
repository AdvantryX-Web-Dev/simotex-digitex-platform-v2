<?php
require '../vendor/autoload.php';
require_once "../php/config.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

if (isset($_POST["submit"])) {
    $prod_line = isset($_POST["prod_line"]) ? $_POST["prod_line"] : null;
    $date = isset($_POST["date"]) ? $_POST["date"] : null;
    $model = isset($_POST["model"]) ? $_POST["model"] : null;
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

    $sql_pack = " SELECT 
  CONCAT(MIN(e.matricule) ,'|' ,MIN(e.first_name), '-', MIN(e.last_name)) AS operatrice,
    CONCAT(op.operation_num, '-', MIN(op.designation)) AS num_operation,
    MIN(op.model) AS model,
    op.operation_num AS op_n
   ";
    foreach ($periods as $period) {
        // Remplacement des caractères invalides dans l'alias de colonne
        $formatted_period = str_replace(':', '_', $period);
        $start_time = $schedule[0][$period]['start'];
        $end_time = $schedule[0][$period]['end'];
        $sql_pack .= ",
      '$period' AS period_$formatted_period,
   0 AS downtime_$formatted_period,
      SUM(
          CASE 
              WHEN TIME(op.cur_time) BETWEEN '$start_time' AND '$end_time'
              THEN op.pack_qty
              ELSE 0
          END
          ) AS pack_qty_$formatted_period
  
          ";
    }
    $sql_pack .= "
    FROM 
        prod__pack_operation op
        JOIN init__employee e ON op.operator = e.matricule
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
    $sql_downtime = "SELECT 
       CONCAT(MIN(e.matricule) ,'|' ,MIN(e.first_name), '-', MIN(e.last_name)) AS operatrice,
      null AS num_operation
     ";
    foreach ($periods as $period) {
        $formatted_period = str_replace(':', '_', $period);
        $start_time = $schedule[0][$period]['start'];
        $end_time = $schedule[0][$period]['end'];
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
       INNER JOIN `init__employee`  e ON `reqInter`.`operator` = e.`matricule`
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
        $model = $row['model'];
        $operation = $row['num_operation'];
        $key = $operator . '_' . $model . '_' . $operation;
        if (isset($combinedResults[$key])) {
            foreach ($periods as $period) {
                $formatted_period = str_replace(':', '_', $period);
                $combinedResults[$key]["pack_qty_$formatted_period"] += $row["pack_qty_$formatted_period"];
            }
        } else {
            $combinedResults[$key] = $row;
        }
    }

    foreach ($downtimeResults as $row1) {
        $operator = $row1['operatrice'];

        // Chercher tous les modèles pour cet opérateur dans les résultats de production
        foreach ($combinedResults as $combinedKey => $combinedRow) {
            if (strpos($combinedKey, $operator) === 0) {
                // Ajouter le temps d'arrêt pour chaque modèle associé à cet opérateur
                foreach ($periods as $period) {
                    $formatted_period = str_replace(':', '_', $period);
                    $combinedResults[$combinedKey]["downtime_$formatted_period"] =
                        ($combinedResults[$combinedKey]["downtime_$formatted_period"] ?? 0) +
                        ($row1["downtime_$formatted_period"] ?? 0);
                }
            }
        }
    }




    // Nom des colonnes
    $fields = array_merge(
        ['NOM & PRENOM', 'Modèle', 'OPERATION'],
        ...array_map(fn($period) => ["$period", "T.Perdu"], $periods)
    );
    // Créer un nouveau document Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Ajouter le titre à la première ligne
    $titre = "Performance par Heure - Chaine de Fabrication: " . $prod_line . " - Date: " . $date;
    $sheet->setCellValue('A1', $titre);

    $columnCount = count($fields);
    $lastColumn = Coordinate::stringFromColumnIndex($columnCount); // Convertir le nombre de colonnes en lettre

    // Vérifiez si $lastColumn est valide
    if ($lastColumn) {
        $sheet->mergeCells("A1:{$lastColumn}1"); // Fusionner les cellules pour le titre
    } else {
        throw new Exception("Invalid column index for merging cells.");
    }
    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Définir la couleur du texte en rouge pour le titre
    $sheet->getStyle('A1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);




    // Ajouter les noms des colonnes
    $columnIndex = 'A';
    foreach ($fields as $field) {
        $sheet->setCellValue($columnIndex . '2', $field);
        $sheet->getStyle($columnIndex . '2')->getFont()->setBold(true);
        $columnIndex++;
    }

    // Ajouter les données
    $rowIndex = 3;
    foreach ($combinedResults as $operator => $data) {
        $columnIndex = 'A';
        $sheet->setCellValue($columnIndex++ . $rowIndex, $data['operatrice']);
        $sheet->setCellValue($columnIndex++ . $rowIndex, $data['model']);
        $sheet->setCellValue($columnIndex++ . $rowIndex, $data['num_operation']);


        foreach ($periods as $period) {
            $formatted_period = str_replace(':', '_', $period);
            $pack_qty = isset($data["pack_qty_$formatted_period"]) ? $data["pack_qty_$formatted_period"] : 0;
            $downtime = isset($data["downtime_$formatted_period"]) ? $data["downtime_$formatted_period"] : 0;
            // $sheet->setCellValueExplicit($columnIndex++ . $rowIndex, $pack_qty, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $pack_qty);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $downtime . " minutes");
        }
        $rowIndex++;
    }

    // Appliquer le style de retour à la ligne automatique
    $sheet->getStyle('D3:' . $columnIndex . ($rowIndex - 1))
        ->getAlignment()
        ->setWrapText(true);


    // Définir le nom du fichier et les en-têtes
    $fileName = "perf_heure_CH_" . $prod_line . "_D_" . $date . ".xlsx";
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=\"$fileName\"");

    // Créer un Writer et enregistrer le fichier en sortie
    $writer = new Xlsx($spreadsheet);
    $writer->save("php://output");

    exit;
}
