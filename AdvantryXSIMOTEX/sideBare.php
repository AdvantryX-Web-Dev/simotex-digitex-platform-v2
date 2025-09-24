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
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Départements
    </div>

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
                <a class="collapse-item" href="effectiveReel.php">Effectif réel</a>
                <a class="collapse-item" href="ordreDeFab.php">Ordre de fabrication</a>

                <a class="collapse-item" href="gammeequilibrage.php">Affectation Opération –<br />Box</a>
                <a class="collapse-item" href="allpacks.php">Paquets</a>
                <a class="collapse-item" href="p_operation.php">Opérations</a>
                <a class="collapse-item" href="recap_enCours.php">Détail en cours</a>
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
                <a class="collapse-item" href="controle.php">Contrôle bout de chaine</a>
                <!-- <a class="collapse-item" href="controlechaine.php">Contrôle sur chaine</a> -->
                <a class="collapse-item" href="controleProd.php">Retouche / chaine</a>
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
                <a class="collapse-item" href="performanceoperatrice.php">Rapport journalier</a>
                <a class="collapse-item" href="performanceparheure.php">Rapport horaire</a>
                <a class="collapse-item" href="recapGroupe.php">Rapport groupes</a>

            </div>
        </div>
    </li>
</ul>
<!-- End of Sidebar -->