<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="https://advantryx.com/">
        <div class="sidebar-brand-text mx-3">
            <img src="./img/LogoAdvantryXpng.png" alt="Logo" style="width: 45mm; height: 12mm;">
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Départements
    </div>

    <!-- Nav Item - Production -->
    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Atelier de production</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Chaines de Production:</h6>
                <?php
                /* require_once './php/config.php';
                 $query = "SELECT `prod_line` FROM `init__prod_line` ";
                 $rslt = $con->query($query);

                 $tab4 = [];
                 while ($item = $rslt->fetch_assoc()) {
                     $tab4[] = $item;
                 }
                 for ($i = 0; $i < count($tab4); $i++) {
                     ?>
                     <a class="collapse-item"
                         href="dashboardchaine.php?prod_line=<?php echo $tab4[$i]['prod_line'] . "&prod=" . $tab4[$i + 1]['prod_line']; ?>"
                         target="_blank">
                         <?php echo $tab4[$i]['prod_line'];
                 }*/ ?>
                <?php
                require_once './php/config.php';
                require_once './php/configobj.php';

                // Récupérer toutes les chaînes de production depuis la base de données
                $query = "SELECT prod_line FROM init__prod_line";
                $rslt = $con->query($query);

                $tab4 = [];
                if ($rslt) {
                    while ($item = $rslt->fetch_assoc()) {
                        $tab4[] = $item;
                    }
                } else {
                    echo "La requête a échoué : " . $con->error;
                }

                // Vérifier si $tab4 n'est pas vide avant de boucler
                if (!empty($tab4)) {
                    $total_lines = count($tab4);
                    for ($i = 0; $i < $total_lines; $i++) {
                        $current_prod = $tab4[$i]['prod_line'];
                        $next_prod_index = ($i + 1) % $total_lines;
                        $next_prod = $tab4[$next_prod_index]['prod_line'];


                        if ($i == $total_lines - 1 && (!isset($_GET['prod']) || !in_array($_GET['prod'], array_column($tab4, 'prod_line')))) {
                            $next_prod = $tab4[0]['prod_line']; // Redirection vers la première chaîne
                        }


                        $url = "dashboardchaine.php?prod_line=" . urlencode($current_prod);
                        if ($current_prod != $next_prod) {
                            $url .= "&prod=" . urlencode($next_prod);
                        }
                ?>
                        <a class="collapse-item" href="<?php echo $url; ?>" target="_blank">
                            <?php echo htmlspecialchars($current_prod); ?>
                        </a>
                <?php
                    }
                } else {
                    echo "Aucune ligne de production trouvée.";
                }
                ?>


                </a>
            </div>
        </div>
    </li> -->


    <!-- Nav Item - Méthode -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-folder"></i>
            <span>Méthode</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Bureau de méthode:</h6>
                <a class="collapse-item" href="objectif.php">Insertion d'objectif</a>
                <a class="collapse-item" href="ordreDeFab.php">Ordre de fabrication</a>
                <a class="collapse-item" href="gammeequilibrage.php">Affectation Opération – Box</a>
                <!-- <a class="collapse-item" href="allpacks.php">Paquets</a>
                <a class="collapse-item" href="allgamme.php">Modèle-Gamme</a> -->
                <a class="collapse-item" href="p_operation.php">Opérations</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsecq" aria-expanded="true"
            aria-controls="collapsecq">
            <i class="fa fa-check-square"></i>
            <span>Contrôle qualité</span>
        </a>
        <div id="collapsecq" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Partie de contrôle qualité:</h6>
                <!-- <a class="collapse-item" href="./controle_quality/dashboard/index.php" target="_blank">Tableau
                    de bord</a> -->
                <a class="collapse-item" href="controle.php">Contrôle bout de chaine</a>
                <a class="collapse-item" href="controlechaine.php">Contrôle sur chaine</a>
                <a class="collapse-item" href="defautec.php">Liste des défauts</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Maintenace -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
            aria-controls="collapsePages">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Maintenance</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Position Box:</h6>

                <a class="collapse-item" href="implantation.php">Machine - Box</a>

            </div>


        </div>
    </li>

    <!-- Nav Item - Ressources Humaines -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRH" aria-expanded="true"
            aria-controls="collapseRH">
            <i class="fas fa-fw fa-table"></i>
            <span>Ressources humaine</span>
        </a>
        <div id="collapseRH" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="rh.php">Opératrices</a>
                <a class="collapse-item" href="presence.php">Présence</a>
                <!-- <a class="collapse-item" href="performanceoperatrice.php">Performances Journalières</a> -->
                <a class="collapse-item" href="performanceparheure.php">Performances par Heure</a>
            </div>
        </div>
    </li>

</ul>
<!-- End of Sidebar -->