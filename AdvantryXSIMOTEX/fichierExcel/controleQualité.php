<?php
// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Désactiver temporairement la vérification des erreurs pour l'inclusion d'autoload.php
$errorReporting = error_reporting();
error_reporting(E_ERROR); // Afficher uniquement les erreurs fatales

// Vérifier le chemin d'accès correct pour autoload.php
$autoloadPaths = [
    __DIR__ . '/../vendor/autoload.php',
    '/var/www/html/AdvantryXSIMOTEX/vendor/autoload.php'
];

$loaded = false;
foreach ($autoloadPaths as $path) {
    if (file_exists($path)) {
        require $path;
        $loaded = true;
        break;
    }
}

// Restaurer le niveau de rapport d'erreur
error_reporting($errorReporting);

if (!$loaded) {
    die("Erreur: Impossible de trouver le fichier autoload.php. Chemins recherchés: " . implode(", ", $autoloadPaths));
}
require_once "../php/config.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Augmenter la limite de mémoire pour les gros fichiers
ini_set('memory_limit', '512M');

// Augmenter le temps d'exécution maximal
set_time_limit(300); // 5 minutes

try {
    // Récupérer les paramètres de filtrage
    $prod_line = isset($_POST["prod_line"]) ? $_POST["prod_line"] : null;
    $operatrice = isset($_POST["operatrice"]) ? $_POST["operatrice"] : null;
    $date = isset($_POST["date"]) ? $_POST["date"] : null;

    // Si aucun filtre n'est défini ou si les valeurs sont les valeurs par défaut, utiliser les 7 derniers jours
    if ((!$prod_line || $prod_line == 'Tous') && (!$operatrice || $operatrice == 'Opératrice') && !$date) {
        $defaultDate = date('Y-m-d', strtotime('-7 days'));
        $date = $defaultDate;
    }

    // Construction des conditions WHERE pour la requête
    $whereConditions = [];
    
    // Ajouter la condition de date (par défaut les 7 derniers jours)
    if ($date) {
        $whereConditions[] = "pc.`created_at` >= '$date'";
    }
    
    // Ajouter la condition de ligne de production si spécifiée et différente de "Tous"
    if ($prod_line && $prod_line != 'Tous') {
        $whereConditions[] = "pc.`group` = '$prod_line'";
    }
    
    // Ajouter la condition d'opératrice si spécifiée et différente de "Opératrice"
    if ($operatrice && $operatrice != 'Opératrice') {
        $whereConditions[] = "po.`operator` = '$operatrice'";
    }
    
    // Construire la clause WHERE
    $whereClause = "WHERE pc.ctrl_state = 1";
    if (!empty($whereConditions)) {
        $whereClause .= " AND " . implode(" AND ", $whereConditions);
    }
    
    // Utiliser la même requête que dans controle.php
    $query = "SELECT
                pp.number,
                pc.`pack_num`,
                pp.`of_num`,
                po.`operator` AS operator_matricule,
                pc.`group` AS prod_line,
                pc.`quantity`,
                pp.`size` AS size,
                pp.`color` AS color,
                pc.`defective_pcs`,
                defect_designations.`designation`,
                defect_designations.`defect_label`,
                pc.`returned`,
                DATE(pc.`created_at`) AS cur_date,
                TIME(pc.`created_at`) AS cur_time
            FROM
                `prod__eol_control` pc
            LEFT JOIN(
                SELECT `prod__eol_pack_defect`.`pack_num`,
                    GROUP_CONCAT(
                        CONCAT(
                            `init__eol_defect`.`code`,
                            ' : ',
                            `prod__eol_pack_defect`.`defect_num`
                        ) SEPARATOR '\n'
                    ) AS `designation`,
                    GROUP_CONCAT(
                        `init__eol_defect`.`designation` SEPARATOR '   /   '
                    ) AS `defect_label`
                FROM
                    `prod__eol_pack_defect`
                LEFT JOIN `init__eol_defect` ON `prod__eol_pack_defect`.`defect_code` = `init__eol_defect`.`code`
                GROUP BY
                    `prod__eol_pack_defect`.`pack_num`
            ) AS defect_designations
            ON
                defect_designations.`pack_num` = pc.`pack_num`
            LEFT JOIN `prod__packet` pp ON
                pc.`pack_num` = pp.`pack_num`
            LEFT JOIN (
                SELECT pack_num, operator
                FROM `prod__pack_operation`
                WHERE `designation` LIKE '%contr%'
            ) po ON
                pc.`pack_num` = po.`pack_num`
            $whereClause
            ORDER BY cur_date DESC, pp.`of_num` ASC";

    $result = mysqli_query($con, $query);
    
    if (!$result) {
        die("Erreur lors de l'exécution de la requête: " . mysqli_error($con));
    }
    
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    
    // Vérifier si des données ont été trouvées
    if (empty($rows)) {
        die("Aucune donnée trouvée pour les critères sélectionnés. Veuillez modifier vos filtres.");
    }
    
    // Création du fichier Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Contrôle Qualité');
    
    // Définir les en-têtes des colonnes (identiques au tableau de l'image)
    $headers = [
        'A' => 'Réf Paquet',
        'B' => 'Ordre de fabrication',
        'C' => 'Matricule',
        'D' => 'Chaine de production',
        'E' => 'Quantité',
        'F' => 'Taille',
        'G' => 'Couleur',
        'H' => 'Nombre des pièces défaillantes',
        'I' => 'Défauts',
        'J' => 'Libellé défauts',
        'K' => 'Statut',
        'L' => 'Date & Heure de controle'
    ];
    
    // Ajouter les en-têtes et formater
    foreach ($headers as $col => $header) {
        $sheet->setCellValue($col . '1', $header);
    }
    
    // Style pour les en-têtes
    $headerStyle = [
        'font' => [
            'bold' => true,
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => 'E0E0E0',
            ],
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ],
        ],
    ];
    
    $sheet->getStyle('A1:L1')->applyFromArray($headerStyle);
    
    // Ajouter les données
    $row = 2;
    foreach ($rows as $data) {
        $sheet->setCellValue('A' . $row, $data['pack_num']);
        $sheet->setCellValue('B' . $row, $data['of_num']);
        $sheet->setCellValue('C' . $row, $data['operator_matricule']);
        $sheet->setCellValue('D' . $row, $data['prod_line']);
        $sheet->setCellValue('E' . $row, $data['quantity']);
        $sheet->setCellValue('F' . $row, $data['size']);
        $sheet->setCellValue('G' . $row, $data['color']);
        $sheet->setCellValue('H' . $row, $data['defective_pcs']);
        
        // Défauts et libellés seulement si retourné
        if ($data['returned'] == 1) {
            $sheet->setCellValue('I' . $row, $data['designation'] ?? '');
            $sheet->setCellValue('J' . $row, $data['defect_label'] ?? '');
        }
        
        // Statut
        $statut = ($data['returned'] == 0) ? 'Validé' : 'Retour prod';
        $sheet->setCellValue('K' . $row, $statut);
        
        // Date et heure
        $sheet->setCellValue('L' . $row, 'D: ' . $data['cur_date'] . ' T: ' . $data['cur_time']);
        
        // Style conditionnel pour le statut
        if ($data['returned'] == 0) {
            $sheet->getStyle('K' . $row)->getFont()->getColor()->setRGB('28a745'); // Vert pour Validé
        } else {
            $sheet->getStyle('K' . $row)->getFont()->getColor()->setRGB('dc3545'); // Rouge pour Retour prod
        }
        
        $row++;
    }
    
    // Ajuster la largeur des colonnes automatiquement
    foreach (range('A', 'L') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    
    // Appliquer un style aux données
    $dataStyle = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ],
        ],
        'alignment' => [
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ];
    
    $sheet->getStyle('A1:L' . ($row - 1))->applyFromArray($dataStyle);
    
    // Définir le nom du fichier
    $filename = 'Controle_Qualite_' . date('Y-m-d') . '.xlsx';
    
    // Nettoyer tous les buffers de sortie avant d'envoyer les en-têtes
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Définir les en-têtes HTTP pour le téléchargement
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    
    // Enregistrer directement dans la sortie PHP
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
    
} catch (Exception $e) {
    die("Erreur lors de la création du fichier Excel: " . $e->getMessage() . " - Trace: " . $e->getTraceAsString());
}
?>