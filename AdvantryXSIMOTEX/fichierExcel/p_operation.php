<?php
session_start();
require '../vendor/autoload.php';
require_once "../php/config.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

if (!empty($_SESSION['results'])) {

    $startDate = $_SESSION['startDate'] ?? null;
    $endDate = $_SESSION['endDate'] ?? null;
    // Nom des colonnes
    $fields = array_merge(['Réf de l\'OF', 'Réf Modèle', 'Chaine', 'Numéro Paquet', 'Quantité Paquet', 'Numéro Opération', 'Désignation d\'opération', 'Temps d\'opération', 'Opératrice', 'Smartbox', 'Date & Heure']);
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    // Ajouter le titre à la première ligne
    $titre = "Opérations";
    if ($startDate !== null && $endDate !== null) {
        $titre .= " - Du: " . $startDate . " Au: " . $endDate;
    }
    if ($startDate !== null && $endDate == null) {
        $titre .= " - Date: " . $startDate;
    }
    if ($startDate == null && $endDate !== null) {
        $titre .= "à partir de la date actuelle Jusqu'à_" . $endDate;
    }
    $sheet->setCellValue('A1', $titre);


    // Définir la largeur de fusion pour qu'elle couvre toutes les colonnes de votre tableau
    $columnCount = count($fields);
    $lastColumn = chr(64 + $columnCount); // Convertir le nombre de colonnes en lettre

    $sheet->mergeCells("A1:{$lastColumn}1"); // Fusionner les cellules pour le titre
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

    foreach ($_SESSION['results'] as $row) {
        $columnIndex = 'A';
        $sheet->setCellValue($columnIndex++ . $rowIndex, $row['of_num']);
        $sheet->setCellValue($columnIndex++ . $rowIndex, $row['model']);
        $sheet->setCellValue($columnIndex++ . $rowIndex, $row['prod_line']);
        $sheet->setCellValue($columnIndex++ . $rowIndex, $row['pack_num']);
        $sheet->setCellValue($columnIndex++ . $rowIndex, $row['pack_qty']);
        $sheet->setCellValue($columnIndex++ . $rowIndex, $row['operation_num']);
        $sheet->setCellValue($columnIndex++ . $rowIndex, $row['designation']);
        $sheet->setCellValue($columnIndex++ . $rowIndex, $row['unit_time']);
        $sheet->setCellValue($columnIndex++ . $rowIndex, $row['operator'] . ' | ' . $row['nomOp']);
        $sheet->setCellValue($columnIndex++ . $rowIndex, $row['smartbox']);
        $sheet->setCellValue($columnIndex++ . $rowIndex, $row['cur_date']);
        $rowIndex++;
    }
    // Définir le nom du fichier et les en-têtes
    $fileName = "Opérations.xlsx";
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=\"$fileName\"");

    // Créer un Writer et enregistrer le fichier en sortie
    $writer = new Xlsx($spreadsheet);
    $writer->save("php://output");

    exit;
}
