<?php
session_start();
?>
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
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .defect-label {
            max-width: 250px;
            white-space: normal !important;
            word-wrap: break-word;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include("sideBare.php") ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mt-4 text-gray-800">Contrôle qualité </h1>
                    <p class="mb-4"></p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <?php
                            require_once './php/config.php';
                            // Récupérer les paramètres de filtrage depuis GET ou POST
                            $filterDate = isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : (isset($_POST['date']) && !empty($_POST['date']) ? $_POST['date'] : date('Y-m-d', strtotime('-30 days')));
                            $displayDate = $filterDate;

                            $filterProdLine = isset($_GET['prod_line']) ? $_GET['prod_line'] : (isset($_POST['prod_line']) ? $_POST['prod_line'] : 'Tous');

                            $filterOperatrice = isset($_GET['operatrice']) ? $_GET['operatrice'] : (isset($_POST['operatrice']) ? $_POST['operatrice'] : 'Opératrice');
                            ?>
                            <h6 class="m-0 font-weight-bold text-primary">Contrôle qualité bout de chaine:</h6>
                            <div class="mb-0 mt-2 mr-2"><a href='edit.php?TAB=<?php echo ("p2_paquet") ?>'><img src="./img/add-file.png" alt="icone" width="25mm" height="25mm"></a></div>
                            <form action="fichierExcel/controleQualité.php" method="post" id="exportForm" class="mb-0">

                                <!-- <form action="process.php" method="post"> -->
                                <div class="col-md-3 float-right">
                                    <button type="submit" name="submit3"
                                        class="btn btn-primary float-right">Export</button>
                                </div>
                               
                                <div class="col-md-2 float-right">
                                    <select name="prod_line" id="filterProdLine" class="form-control filter-select">
                                        <!-- Chaine de Production -->
                                        <option value="Tous" <?php echo ($filterProdLine == 'Tous') ? 'selected' : ''; ?>>Tous</option>
                                        <?php
                                        require_once './php/config.php';
                                        $result = $con->query("SELECT `prod_line` FROM `init__prod_line`");
                                        while ($row = $result->fetch_assoc()) {
                                            $selected = ($filterProdLine == $row['prod_line']) ? 'selected' : '';
                                            echo "<option value=\"{$row['prod_line']}\" {$selected}>{$row['prod_line']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2 float-right">
                                    <select name="operatrice" id="filterOperatrice" class="form-control filter-select">
                                        <!-- Opératrice -->
                                        <option value="Opératrice" <?php echo ($filterOperatrice == 'Opératrice') ? 'selected' : ''; ?>>Opératrice</option>
                                        <?php
                                        require_once './php/config.php';
                                        $result = $con->query("SELECT `matricule` FROM `init__employee` ");
                                        while ($row = $result->fetch_assoc()) {
                                            $selected = ($filterOperatrice == $row['matricule']) ? 'selected' : '';
                                            echo "<option value=\"{$row['matricule']}\" {$selected}>{$row['matricule']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2 float-right">
                                    <input type="date" name="date" id="filterDate" class="form-control float-right"
                                        placeholder="Sélectionner une date" value="<?php echo $displayDate; ?>">
                                </div>
                                <div class="clearfix"></div>

                            </form>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Réf Paquet</th>
                                            <th>Ordre de fabrication</th>
                                            <th>Matricule</th>
                                            <!-- <th>Tag ID</th> -->
                                            <th>Chaine de production</th>
                                            <th>Quantité</th>
                                            <th>Taille</th>
                                            <th>Couleur</th>
                                            <th>Statut</th>
                                            <!-- <th>Nombre des défauts</th> -->
                                            <th>Nombre des piéces défaillantes </th>
                                            <th>Défauts</th>
                                            <th>Libellé défaut</th>
                                            <th>Date & Heure de controle</th>
                                            <th>Détails</th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                    <?php
                                        // La variable $filterDate est déjà définie en haut du fichier

                                        $query = "SELECT
                                        pp.number,
                                        pc.`pack_num`,
                                        pp.`of_num`,
                                        pc.`group` AS prod_line,
                                        pc.`defects_num`,
                                        pc.`defective_pcs`,
                                        pc.`quantity`,
                                        pc.ctrl_state,
                                        pc.`returned`,
                                        DATE(pc.`created_at`) AS cur_date,
                                        TIME(pc.`created_at`) AS cur_time,
                                        defect_designations.`designation`,
                                        defect_designations.`defect_label`,
                                        po.`operator` AS operator_matricule,
                                        pp.`size` AS size,
                                        pp.`color` AS color
                                    FROM
                                        `prod__eol_control` pc
                                    INNER JOIN (
                                        SELECT `pack_num`, MAX(`created_at`) AS `max_created_at`
                                        FROM `prod__eol_control`
                                        WHERE `ctrl_state` = 1 AND `created_at` >= '$filterDate'
                                        GROUP BY `pack_num`
                                    ) lc ON lc.`pack_num` = pc.`pack_num` AND lc.`max_created_at` = pc.`created_at`
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
                                    WHERE
                                        pc.`ctrl_state` = 1
                                    ORDER BY cur_date DESC, pp.`of_num` ASC;";

                                        $result = $con->query($query);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                        ?>
                                                <tr>
                                                    <td><a href='packop.php?pack_num=<?php echo $row['pack_num']; ?>'>
                                                            <?php echo $row['pack_num']; ?>
                                                        </a></td>
                                                    <td><a href='pack.php?of_num=<?php echo $row['of_num']; ?>'>
                                                            <?php echo $row['of_num']; ?>
                                                        </a></td>
                                                    <td>
                                                        <?php echo $row['operator_matricule']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['prod_line']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['quantity']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['size'] ?? ''; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['color'] ?? ''; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($row['returned'] == 0) {
                                                            echo "<span class='text-success'>Validé</span>";
                                                        } else {
                                                            echo "<span class='text-danger'>Retour prod</span>";
                                                        } ?>
                                                    </td>
                                                    <!-- <td>
                                                        <?php echo $row['defects_num']; ?>
                                                    </td>-->
                                                    <td>
                                                        <?php echo $row['defective_pcs']; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($row['returned'] == 0) {
                                                        } else {
                                                            echo nl2br($row['designation'] ?? '');
                                                        } ?>
                                                    </td>
                                                    <td class="defect-label">
                                                        <?php if ($row['returned'] == 0) {
                                                        } else {
                                                            echo $row['defect_label'] ?? '';
                                                        } ?>
                                                    </td>
                                                   
                                                    <td>
                                                        <?php echo 'D: ' . $row['cur_date'] . '<br>T: ' . $row['cur_time']; ?>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-outline-primary toggle-details" aria-expanded="false" title="Afficher les détails" data-packnum="<?php echo htmlspecialchars($row['pack_num']); ?>">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo "Aucun résultat trouvé";
                                        }
                                        ?>

                                    </tbody>
                                </table>

                               
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Advantry X <?php echo date("Y"); ?></span>
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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                language: {
                    search: "Rechercher :",
                    lengthMenu: "Afficher _MENU_ éléments par page",
                    info: "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                    infoEmpty: "Aucun élément à afficher",
                    infoFiltered: "(filtré de _MAX_ éléments au total)",
                    zeroRecords: "Aucun enregistrement correspondant trouvé",
                    paginate: {
                        first: "Premier",
                        previous: "Précédent",
                        next: "Suivant",
                        last: "Dernier"
                    }
                }
            });

            $('#model').select2({
                placeholder: '--Sélectionner un modèle--',
                language: "fr"
            });

        });
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("filterForm").addEventListener("submit", function(event) {
                var ofs = document.getElementById("of").value.trim();

                var models = document.getElementById("model").value.trim();


                // Vérifie si au moins un champ est rempli
                if (!models && !ofs) {
                    event.preventDefault(); // Empêche la soumission du formulaire
                    alert("Veuillez remplir au moins un champ pour filtrer les résultats.");
                }
            });
        });
    </script>
    <script>
        // Inline expandable subtable for pack details under the main row
        $(document).on('click', '.toggle-details', function () {
            var $btn = $(this);
            var $icon = $btn.find('i');
            var packNum = $btn.data('packnum');
            var $tr = $btn.closest('tr');

            if ($btn.attr('aria-expanded') === 'true') {
                // collapse: close ALL detail rows and reset all toggles
                $('.details-row').remove();
                $('.toggle-details').attr('aria-expanded','false').each(function(){
                    $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                });
                return;
            }

            // collapse any other open details to avoid clutter
            $('.toggle-details[aria-expanded="true"]').each(function(){
                var $b = $(this);
                $b.attr('aria-expanded','false').find('i').removeClass('fa-eye-slash').addClass('fa-eye');
            });
            $('.details-row').remove();

            // expand current
            $btn.attr('aria-expanded', 'true');
            $icon.removeClass('fa-eye').addClass('fa-eye-slash');

            var $placeholder = $('<tr class="details-row" data-parent="' + packNum + '"><td colspan="13"><div class="text-center py-3"><div class="spinner-border text-primary" role="status"><span class="sr-only">Chargement...</span></div></div></td></tr>');
            $tr.after($placeholder);

            $.ajax({
             url: 'pack_details_rows.php',

                method: 'GET',
                data: { pack_num: packNum },
                success: function (html) {
                    $placeholder.remove();
                    $tr.after(html);
                },
                error: function () {
                    $placeholder.find('td').html('<div class="alert alert-danger mb-0">Erreur lors du chargement des détails.</div>');
                }
            });
        });
    </script>
    <script>
        // Script pour conserver les paramètres de filtrage lors de la navigation entre les pages
        document.addEventListener('DOMContentLoaded', function() {
            // Récupération des filtres
            const filterProdLine = document.getElementById('filterProdLine');
            const filterOperatrice = document.getElementById('filterOperatrice');
            const filterDate = document.getElementById('filterDate');

            // Fonction pour appliquer les filtres
            function applyFilters() {
                const form = document.getElementById('exportForm');
                form.action = window.location.pathname; // Rediriger vers la même page
                form.method = 'get';
                form.submit();
            }

            // Ajouter les événements de changement
            if (filterProdLine) filterProdLine.addEventListener('change', applyFilters);
            if (filterOperatrice) filterOperatrice.addEventListener('change', applyFilters);
            if (filterDate) filterDate.addEventListener('change', applyFilters);

            // Préserver les paramètres de pagination dans les liens de filtrage
            const paginationLinks = document.querySelectorAll('.pagination a');
            paginationLinks.forEach(link => {
                const url = new URL(link.href);
                const currentUrl = new URL(window.location.href);

                // Ajouter les paramètres de filtre actuels aux liens de pagination
                if (currentUrl.searchParams.has('prod_line')) {
                    url.searchParams.set('prod_line', currentUrl.searchParams.get('prod_line'));
                }
                if (currentUrl.searchParams.has('operatrice')) {
                    url.searchParams.set('operatrice', currentUrl.searchParams.get('operatrice'));
                }
                if (currentUrl.searchParams.has('date')) {
                    url.searchParams.set('date', currentUrl.searchParams.get('date'));
                }

                link.href = url.toString();
            });

            // Ajouter la fonctionnalité de recherche dans le tableau
            $(document).ready(function() {
                // Recherche manuelle dans le tableau
                $("#searchInput").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $("#dataTable tbody tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });

                // Tri des colonnes
                $('th').click(function() {
                    var table = $(this).parents('table').eq(0);
                    var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()));
                    this.asc = !this.asc;
                    if (!this.asc) {
                        rows = rows.reverse();
                    }
                    for (var i = 0; i < rows.length; i++) {
                        table.append(rows[i]);
                    }
                });

                function comparer(index) {
                    return function(a, b) {
                        var valA = getCellValue(a, index),
                            valB = getCellValue(b, index);
                        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB);
                    };
                }

                function getCellValue(row, index) {
                    return $(row).children('td').eq(index).text();
                }
            });
        });
    </script>

</body>

</html>