<?php
require_once __DIR__ . '/php/config.php';

header('Content-Type: text/html; charset=utf-8');

$packNum = isset($_GET['pack_num']) ? trim($_GET['pack_num']) : '';
if ($packNum === '') {
    http_response_code(400);
    echo '<tr class="details-row"><td colspan="13" class="text-center">Paramètre pack_num manquant.</td></tr>';
    exit;
}

$packNumEscaped = $con->real_escape_string($packNum);

$query = "SELECT 
    pc.id,
    pc.pack_num,
    pc.`group` AS prod_line,
    pc.quantity,
    pc.defects_num,
    pc.defective_pcs,
    pc.ctrl_state,
    pc.returned,
    pc.created_at,
    pp.of_num,
    pp.size,
    pp.color,
    po.operator AS operator_matricule
FROM prod__eol_control pc
LEFT JOIN prod__packet pp ON pc.pack_num = pp.pack_num
LEFT JOIN (
    SELECT pack_num, operator
    FROM prod__pack_operation
    WHERE designation LIKE '%contr%'
) po ON pc.pack_num = po.pack_num
WHERE pc.pack_num = '$packNumEscaped'
ORDER BY pc.created_at DESC";

$result = $con->query($query);
if (!$result) {
    http_response_code(500);
    echo '<tr class="details-row"><td colspan="13"><div class="alert alert-danger mb-0">Erreur SQL.</div></td></tr>';
    exit;
}

if ($result->num_rows === 0) {
    echo '<tr class="details-row" data-parent="' . htmlspecialchars($packNum) . '"><td colspan="13" class="text-center">Aucun détail trouvé.</td></tr>';
    exit;
}

$index = 0; // to skip first (latest) log row
while ($row = $result->fetch_assoc()) {
    if ($index === 0) { $index++; continue; }

    // Fetch defects only for this control row (by eol_control_id)
    $controlId = (int)$row['id'];
    $defectsRes = $con->query(
        "SELECT d.code, d.designation, ped.defect_num
         FROM prod__eol_pack_defect ped
         LEFT JOIN init__eol_defect d ON ped.defect_code = d.code
         WHERE ped.eol_control_id = $controlId"
    );
    $designation = '';
    $defectLabel = '';
    if ($defectsRes && $defectsRes->num_rows > 0) {
        $codes = [];
        $labels = [];
        while ($d = $defectsRes->fetch_assoc()) {
            $codes[] = $d['code'] . ' : ' . $d['defect_num'];
            $labels[] = $d['designation'];
        }
        $designation = implode("\n", $codes);
        $defectLabel = implode('   /   ', $labels);
    }

    $status = $row['returned'] == 0 ? '<span class="text-success">Validé</span>' : '<span class="text-danger">Retour prod</span>';
    $date = date('Y-m-d', strtotime($row['created_at']));
    $time = date('H:i:s', strtotime($row['created_at']));

    echo '<tr class="details-row" data-parent="' . htmlspecialchars($packNum) . '">';
    echo '<td><a href="packop.php?pack_num=' . htmlspecialchars($row['pack_num']) . '">' . htmlspecialchars($row['pack_num']) . '</a></td>';
    echo '<td><a href="pack.php?of_num=' . htmlspecialchars($row['of_num']) . '">' . htmlspecialchars($row['of_num']) . '</a></td>';
    echo '<td>' . htmlspecialchars($row['operator_matricule']) . '</td>';
    echo '<td>' . htmlspecialchars($row['prod_line']) . '</td>';
    echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
    echo '<td>' . htmlspecialchars($row['size']) . '</td>';
    echo '<td>' . htmlspecialchars($row['color']) . '</td>';
    echo '<td>' . $status . '</td>';
    echo '<td>' . htmlspecialchars($row['defective_pcs']) . '</td>';
    echo '<td><pre class="mb-0" style="white-space:pre-wrap">' . htmlspecialchars($designation) . '</pre></td>';
    echo '<td class="defect-label">' . htmlspecialchars($defectLabel) . '</td>';
    echo '<td>' . 'D: ' . htmlspecialchars($date) . '<br>T: ' . htmlspecialchars($time) . '</td>';
    echo '<td></td>';
    echo '</tr>';
    $index++;
}

if ($index <= 1) {
    echo '<tr class="details-row" data-parent="' . htmlspecialchars($packNum) . '"><td colspan="13" class="text-center">Aucun historique supplémentaire.</td></tr>';
}
