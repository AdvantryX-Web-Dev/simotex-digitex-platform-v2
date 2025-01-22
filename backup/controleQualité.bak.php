<?php
require '../vendor/autoload.php';
require_once "../php/config.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

if (isset($_POST["submit3"])) {
    $prod_line = isset($_POST["prod_line"]) ? $_POST["prod_line"] : null;
    // $operatrice = isset($_POST["operatrice"]) ? $_POST["operatrice"] : null;
    $date = isset($_POST["date"]) ? $_POST["date"] : null;

    // Récupération des codes de défaut dynamiquement
    $defectCodesQuery = "SELECT DISTINCT defect_code FROM prod__eol_pack_defect";
    $defectCodesResult = mysqli_query($con, $defectCodesQuery);

    $defectCodes = [];
    while ($row = $defectCodesResult->fetch_assoc()) {
        $defectCodes[] = $row['defect_code'];
    }

    $selectParts = [];
    foreach ($defectCodes as $code) {
        $selectParts[] = "MIN(CASE WHEN ppd.defect_code = '$code' THEN ppd.defect_num END) AS `$code`";
    }
    $selectPartStr = implode(", ", $selectParts);

    // Requête principale
    $sql = "SELECT
                -- CONCAT(emp.first_name, ' ', emp.last_name) AS optc,
                ppo.pack_num AS pack_num,
                ppo.pack_qty  AS pack_qty,
              $selectPartStr,
                MIN(pcc.defects_num) AS defectueux,
                MIN(pcc.defective_pcs) AS def
            FROM
                prod__pack_operation ppo
            -- JOIN init__employee emp ON ppo.operator = emp.matricule
            LEFT JOIN
                prod__eol_pack_defect ppd ON ppo.pack_num = ppd.pack_num
            LEFT JOIN (
                SELECT
                    pack_num,
                    MIN(defects_num) AS defects_num,
                    MIN(defective_pcs) AS defective_pcs
                FROM
                    prod__eol_control p
                where created_at =(select MIN(created_at)  FROM
                    prod__eol_control where pack_num=p.pack_num )
                GROUP BY
                    pack_num
            ) pcc ON ppo.pack_num = pcc.pack_num
         WHERE
                -- ppo.operator = '$operatrice' AND
                ppo.prod_line = '$prod_line' AND
                ppo.cur_date = DATE_FORMAT('$date', '%Y-%m-%d')
            GROUP BY
                ppo.pack_num, ppo.pack_qty";

    $res = mysqli_query($con, $sql);
    $rows = [];
    while ($item = $res->fetch_assoc()) {
        $rows[] = $item;
    }

    // Récupération des défauts et catégories
    $query = "SELECT 
                d.id AS defect_id, 
                d.code AS defect_code, 
                d.designation AS defect_name, 
                c.id AS category_id, 
                c.designation AS category_name
              FROM 
                init__eol_defect d
              JOIN 
                init__eol_def_category c 
              ON 
                d.category_id = c.id";

    $result = mysqli_query($con, $query);
    $defectsByCategory = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $defectsByCategory[$row['category_id']]['category_name'] = $row['category_name'];
        $defectsByCategory[$row['category_id']]['defects'][] = [
            'defect_id' => $row['defect_id'],
            'defect_code' => $row['defect_code'],
            'defect_name' => $row['defect_name']

        ];
    }

    // Création du fichier Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Ajout des en-têtes
    $sheet->setCellValue('A1', 'SIMOTEX');
    $sheet->setCellValue('E1', 'Formulaire Contrôle Qualité Bout de Chaine');
    $sheet->setCellValue('M1', 'Page 1 sur 1');
    $sheet->setCellValue('A3', 'Date: ' . $date);
    // $sheet->setCellValue('E3', 'Contrôleuse: ' . $operatrice);
    $sheet->setCellValue('I3', 'GRP: ' . $prod_line);
    $currentRow = 5;
    $column = 'C';

    // Première ligne : Répéter "Numéro de paquet" et "Quantité de paquet" pour chaque paquet sur la même ligne
    foreach ($rows as $row) {
        $sheet->setCellValue($column . $currentRow, 'Numéro de paquet');
        $column++;
        $sheet->setCellValue($column . $currentRow, 'Quantité de paquet');
        $column++;
    }
    $sheet->setCellValue($column . $currentRow, 'Total');
    $currentRow++;

    // Deuxième ligne : Numéros de paquets et Quantités
    $sheet->setCellValue('A' . $currentRow, 'CODE DEFAUT');
    $sheet->setCellValue('B' . $currentRow, 'DEFAUT');
    $column = 'C';
    foreach ($rows as $row) {
        $sheet->setCellValue($column . $currentRow, $row['pack_num']);
        $sheet->setCellValue(++$column . $currentRow, $row['pack_qty']);
        $column++;
    }
    $sheet->setCellValue($column . $currentRow, '');

    $currentRow++; // Passe à la ligne suivante

    // Remplissage des données de défauts par catégorie
    foreach ($defectsByCategory as $category) {
        // Ajoute le nom de la catégorie
        $sheet->mergeCells('A' . $currentRow . ':B' . $currentRow);
        $sheet->setCellValue('A' . $currentRow, $category['category_name']);
        $currentRow++; // Passe à la ligne suivante

        foreach ($category['defects'] as $defect) {
            $sheet->setCellValue('A' . $currentRow, $defect['defect_code']);
            $sheet->setCellValue('B' . $currentRow, $defect['defect_name']);
            $column = 'C'; // Commencer à la colonne C pour les données de défauts
            $totalForDefect = 0;
            foreach ($rows as $row) {
                $currentCell = $column . $currentRow;

                // Vérifie s'il existe une valeur pour le code défaut
                if (isset($row[$defect['defect_code']])) {
                    $value = $row[$defect['defect_code']];
                    $sheet->setCellValue($currentCell, $value);


                    $totalForDefect += $value;
                } else {
                    $sheet->setCellValue($currentCell, 0);
                }

                // Fusionne les colonnes C et D pour chaque pack
                $sheet->mergeCells($column . $currentRow . ':' . Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString($column) + 1) . $currentRow);

                // Incrémenter de 2 colonnes pour le prochain pack
                $column = Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString($column) + 2);
            }
            $sheet->setCellValue($column . $currentRow, $totalForDefect);
            $currentRow++;
        }
    }


    // En-tête des totaux
    $sheet->setCellValue('A' . $currentRow, 'TOTAL DEFAUTS \ TOTAL DEFECTUEUX');

    $sheet->getStyle('A' . $currentRow)->applyFromArray([
        'font' => [
            'bold' => true,
        ],
    ]);
    $column = 'C';
    $sumDef = 0;
    $sumDefecteux = 0;
    foreach ($rows as $row) {
        $currentCell = $column . $currentRow;
        $def = $row['def'];
        $defectueux = $row['defectueux'];

        $sumDef += $def;
        $sumDefecteux += $defectueux;
        // Vérifie s'il existe une valeur pour le code défaut
        if ($def != null && $defectueux != null) {
            $sheet->setCellValue($column . $currentRow, $def . " \ " . $defectueux);
        } else {
            $sheet->setCellValue($currentCell, 0 . " \ " . 0);
        }

        // Fusionne les colonnes C et D pour chaque pack
        $sheet->mergeCells($column . $currentRow . ':' . Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString($column) + 1) . $currentRow);

        // Incrémenter de 2 colonnes pour le prochain pack
        $column = Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString($column) + 2);
    }
    $nextColumn = Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString($column));
    $sheet->setCellValue($nextColumn . $currentRow, $sumDef . " \ " . $sumDefecteux);

    // Écriture dans le fichier
    $writer = new Xlsx($spreadsheet);
    $filename = 'CQ_BCh_' . $date . '.xlsx';

    // Forcer le téléchargement du fichier
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit();
}
