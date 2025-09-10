<?php
session_start();
require '../vendor/autoload.php';
require_once "../php/config.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérifier que les données de session existent
if (!isset($_SESSION['resultsCont']) || empty($_SESSION['resultsCont'])) {
    die("Aucune donnée de contrôle qualité trouvée. Veuillez d'abord effectuer une recherche.");
}

try {
    // Créer un nouveau spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Variables de date
    $startDate = $_SESSION['startDate'] ?? '';
    $endDate = $_SESSION['endDate'] ?? '';
    
    // Titre
    $titre = "Controle qualité";
    if (!empty($startDate) && !empty($endDate)) {
        $titre .= " - Du: " . $startDate . " Au: " . $endDate;
    } elseif (!empty($startDate)) {
        $titre .= " - Date: " . $startDate;
    } elseif (!empty($endDate)) {
        $titre .= " - Au: " . $endDate;
    }
    
    $sheet->setCellValue('A1', $titre);
    $sheet->mergeCells('A1:C1');
    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A1')->getFont()->getColor()->setARGB(Color::COLOR_RED);
    
    // En-têtes
    $sheet->setCellValue('A2', 'Date');
    $sheet->setCellValue('B2', 'Chaine de production');
    $sheet->setCellValue('C2', 'Nombre des pièces défaillantes');
    
    $sheet->getStyle('A2')->getFont()->setBold(true);
    $sheet->getStyle('B2')->getFont()->setBold(true);
    $sheet->getStyle('C2')->getFont()->setBold(true);
    
    // Données
    $rowIndex = 3; // Initialisation de $rowIndex
    foreach ($_SESSION['resultsCont'] as $controle) {
        $sheet->setCellValue('A' . $rowIndex, $controle['date'] ?? '');
        $sheet->setCellValue('B' . $rowIndex, $controle['prod_line'] ?? '');
        $sheet->setCellValue('C' . $rowIndex, $controle['nbPieceDefct'] ?? '');
        $rowIndex++;
    }



    // Ajuster la largeur des colonnes automatiquement
    foreach (range('A', 'C') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }
    
    // Nettoyer le buffer de sortie
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // Définir les headers pour le téléchargement
    $fileName = "Controle_Qualite_" . date('Y-m-d_H-i-s') . ".xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');
    
    // Créer le writer et sauvegarder
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
    echo "<br>Fichier: " . $e->getFile();
    echo "<br>Ligne: " . $e->getLine();
}
exit;
?>
