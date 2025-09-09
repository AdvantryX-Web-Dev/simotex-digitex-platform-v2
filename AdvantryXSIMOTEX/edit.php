<?php session_start(); ?>
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
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="img/favicon.ico" />

    <!-- Custom fonts for this template-->
    <link href="css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        th,
        td {
            white-space: nowrap;
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
                    <h1 class="h3 mb-2 mt-4 text-gray-800">Modification: </h1>
                    <p class="mb-4"></p>

                    <!-- DataTales Example  -->
                    <!-- modification sur model -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Editer:</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <?php
                                require_once './php/config.php';
                                // récupérer l'ID de l'enregistrement à modifier à partir du paramètre GET
                                if (isset($_GET["model_id"]) || isset($_GET["operation_num"])) {
                                    $model = $_GET["model_id"];
                                    $opcode = $_GET["operation_num"];

                                    // récupérer les données de l'enregistrement à partir de la base de données
                                    $sql = "SELECT `prod__gamme`.`model_id`,`init__model`.`model`,
                         `prod__of`.`of_num`,
                         `prod__pack_gamme`.`pack_num`, `prod__gamme`.`operation_num`,
                        `prod__gamme`.`designation`, `prod__gamme`.`unit_time`, `prod__gamme`.`qte_h`,
                        `init__machine`.`machine`,
                        `prod__gamme`.`smartbox`, `prod__gamme`.`import_dt`, `prod__gamme`.`id` FROM `init__model`
                        INNER JOIN `prod__of` ON `init__model`.`id`=`prod__of`.`model_id` 
                        INNER JOIN `prod__gamme` ON `init__model`.`id`= `prod__gamme`.`model_id` 
                        INNER JOIN `prod__pack_gamme` ON `prod__gamme`.`id` =`prod__pack_gamme`.`gamme_id` 
                        INNER JOIN `init__machine` ON `prod__gamme`.`machine_id`=`init__machine`.`machine_id` WHERE `init__model`.`id`=$model AND `prod__gamme`.`operation_num`=$opcode";

                                    $result = $con->query($sql);
                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                ?>
                                        <form method="post" action="update.php">
                                            <div class="form-row">

                                                <!-- <label>ID:</label> -->
                                                <input type="hidden" name="id" readonly="readonly" value="<?php echo $row['id']; ?>"><br>

                                                <!-- <label class="col ml-2">modèle ID:</label> -->
                                                <input type="hidden" name="model_id" readonly="readonly" class="form-control ml-2" value="<?php echo $row['model_id']; ?>"><br>

                                                <label class="col ml-2">modèle:</label>
                                                <input type="text" name="model" readonly="readonly" class="form-control ml-2" value="<?php echo $row['model']; ?>"><br>

                                                <label class="col ml-2">Réference de l'OF:</label>
                                                <input type="text" name="of_num" class="form-control ml-2" readonly="readonly" value="<?php echo $row['of_num']; ?>"><br>

                                                <label class="col ml-2">Numéro de paquet:</label>
                                                <input type="text" name="pack_num" class="form-control ml-2" readonly="readonly" value="<?php echo $row['pack_num']; ?>"></input><br>

                                                <label class="col ml-2">Code de l'opération:</label>
                                                <textarea name="operation_num" readonly="readonly" class="form-control ml-2"><?php echo $row['operation_num']; ?></textarea><br>

                                                <label class="col ml-2">designation:</label>
                                                <textarea name="designation" class="form-control ml-2" readonly="readonly"><?php echo $row['designation']; ?></textarea><br>

                                                <label class="col ml-2">Temps unitaire:</label>
                                                <textarea name="unit_time" class="form-control ml-2"><?php echo $row['unit_time']; ?></textarea><br>

                                                <label class="col ml-2">Quantité par heure:</label>
                                                <textarea name="qte_h" class="form-control ml-2"><?php echo $row['qte_h']; ?></textarea><br>

                                                <input class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle ml-4 mb-2 mt-2" type="submit" name="Bouton" value="Modifier">
                                                <!-- <input class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle ml-4 mb-2 mt-2 " type="submit" name="Bouton1" value="Supprimer"> -->

                                            </div>
                                        </form>
                                    <?php
                                    } else { ?>
                                        <script>
                                            alert("Enregistrement non trouvé.");
                                        </script>
                                    <?php
                                    }
                                }
                                //    INSERTION GAMME 
                                if (isset($_GET["TABLE"])):
                                    $tab = $_GET["TABLE"]; ?>
                                    <form method="post" action="update.php?TABLE=<?php echo ($tab); ?>">
                                        <div class="form-row">

                                            <label class="col ml-2">modèle:</label>
                                            <textarea type="text" name="model" class="form-control ml-2" required></textarea><br>

                                            <label class="col ml-2">Réference de l'OF:</label>
                                            <textarea type="text" name="of_num" class="form-control ml-2" required></textarea><br>

                                            <label class="col ml-2">Numéro de paquet:</label>
                                            <textarea name="pack_num" class="form-control ml-2"></textarea><br>

                                            <label class="col ml-2">Code de l'opération:</label>
                                            <textarea name="operation_code" class="form-control ml-2" required></textarea><br>

                                            <label class="col ml-2">Designation:</label>
                                            <textarea name="designation" class="form-control ml-2" required></textarea><br>

                                            <label class="col ml-2">Temps unitaire:</label>
                                            <textarea name="unit_time" class="form-control ml-2" required></textarea><br>

                                            <label class="col ml-2">Quantité par heure:</label>
                                            <textarea name="qte_h" class="form-control ml-2" required></textarea><br>

                                            <label class="col ml-2">Référence Machine:</label>
                                            <textarea name="machine_ref" class="form-control ml-2"></textarea><br>

                                            <label class="col ml-2">DigiTex:</label>
                                            <textarea name="digitex" class="form-control ml-2"></textarea><br>

                                            <label class="col ml-2">Etat de l'opération:</label>
                                            <input name="op_state" class="form-control ml-2" type="number" min="0" max="1"></input><br>

                                            <div><input class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle ml-4 mb-2 mt-2" type="submit" name="Bouton2" value="Enregistrer"></div>

                                        </div>
                                    </form>
                                    <?php endif;

                                //   MODIFICATION SUR L'OF
                                if (isset($_GET["of_num"])) {
                                    $of = $_GET["of_num"];

                                    $sql1 = "SELECT DISTINCT`prod__of`.`of_num`, `prod__of`.`start_date`, `prod__of`.`end_date`, `prod__of`.`id`,
                            `init__model`.`model`,
                            `prod__of`.`client`, `prod__of`.`asm_shop`, `prod__of`.`of_state`, `prod__of`.`quantity`, `prod__of`.`import_dt`
                            FROM `prod__of`
                            INNER JOIN `init__model` ON `prod__of`.`model_id`= `init__model`.`id` WHERE `prod__of`.`of_num`='$of'";
                                    $result1 = $con->query($sql1);

                                    while ($item = $result1->fetch_assoc()) {
                                        $row1[] = $item;
                                    }
                                    for ($i = 0; $i < count($row1); $i++) {
                                    ?>
                                        <form method="post" action="update.php?of_num=<?php echo ($of); ?>">
                                            <div class="form-row">

                                                <!-- <label>ID:</label> -->
                                                <input type="hidden" name="id" readonly="readonly" value="<?php echo $row1[$i]['id']; ?>"><br>

                                                <label class="col ml-2">Réference de l'OF:</label>
                                                <input type="text" name="of_num" readonly="readonly" class="form-control ml-2" value="<?php echo $row1[$i]['of_num']; ?>" required></input><br>

                                                <label class="col ml-2">Client:</label>
                                                <input type="text" name="client" class="form-control ml-2" value="<?php echo $row1[$i]['client']; ?>"></input><br>

                                                <label class="col ml-2">Atelier:</label>
                                                <input type="text" name="asm_shop" class="form-control ml-2" value="<?php echo $row1[$i]['asm_shop']; ?>" required></input><br>

                                                <label class="col ml-2">Statut de l'OF:</label>
                                                <input type="text" name="of_state" class="form-control ml-2" min="END" max="START" value="<?php echo $row1[$i]['of_state']; ?>" required></input><br>

                                                <label class="col ml-2">Date de Lancement:</label>
                                                <input type="date" name="start_date" class="form-control ml-2" value="<?php echo $row1[$i]['start_date']; ?>"></input><br>

                                                <label class="col ml-2">Date d'achèvement:</label>
                                                <input type="date" name="end_date" class="form-control ml-2" value="<?php echo $row1[$i]['end_date']; ?>"></input><br>

                                                <label class="col ml-2">Date d'imporation:</label>
                                                <input type="date" name="import_dt" class="form-control ml-2" value="<?php echo $row1[$i]['import_dt'];
                                                                                                                    } ?>"></input><br>

                                                <div><input class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle ml-4 mb-2 mt-2" type="submit" name="Bouton3" value="Enregistrer"></div>

                                            </div>
                                        </form>
                                        <!-- MODIFICATION SUR LE PAQUET -->
                                        <?php }
                                    if (isset($_GET["pack_num"])) {
                                        $pack = $_GET["pack_num"];

                                        $sql2 = "SELECT `prod__packet`.`id`, `prod__packet`.`pack_num`, `prod__packet`.`of_num`,
                            `init__model`.`model`,
                            `prod__packet`.`tag_rfid`, `prod__packet`.`prod_line`, `prod__packet`.`color`, `prod__packet`.`quantity`, `prod__packet`.`size`
                            FROM `prod__packet` 
                            INNER JOIN `prod__of` ON `prod__packet`.`of_num`=`prod__of`.`of_num` 
                            INNER JOIN `init__model` ON `prod__of`.`model_id` =`init__model`.`id` 
                            WHERE `prod__packet`.`pack_num` ='$pack';";
                                        $result2 = $con->query($sql2);

                                        while ($item = $result2->fetch_assoc()) {
                                            $row2[] = $item;
                                        }
                                        for ($i = 0; $i < count($row2); $i++) {
                                        ?>
                                            <form method="post" action="update.php?pack_num=<?php echo ($pack); ?>">
                                                <div class="form-row">

                                                    <!-- <label>ID:</label> -->
                                                    <input type="hidden" name="id" readonly="readonly" value="<?php echo $row2[$i]['id']; ?>"><br>

                                                    <label class="col ml-2">Numéro de paquet:</label>
                                                    <input type="text" name="pack_num" readonly="readonly" class="form-control ml-2" value="<?php echo $row2[$i]['pack_num']; ?>" required></input><br>

                                                    <label class="col ml-2">Référence de l'Of:</label>
                                                    <input type="text" name="of_num" readonly="readonly" class="form-control ml-2" value="<?php echo $row2[$i]['of_num']; ?>"></input><br>

                                                    <label class="col ml-2">Modèle:</label>
                                                    <input type="text" name="model" readonly="readonly" class="form-control ml-2" value="<?php echo $row2[$i]['model']; ?>" required></input><br>

                                                    <label class="col ml-2">Tag:</label>
                                                    <input type="text" id="tag_rfid" name="tag_rfid" class="form-control ml-2" readonly="readonly" value="<?php echo $row2[$i]['tag_rfid']; ?>" required></input><br>

                                                    <label class="col ml-2">Chaine de production:</label>
                                                    <input type="text" name="prod_line" class="form-control ml-2" value="<?php echo $row2[$i]['prod_line']; ?>"></input><br>

                                                    <label class="col ml-2">Taille:</label>
                                                    <input type="text" name="size" class="form-control ml-2" value="<?php echo $row2[$i]['size']; ?>"></input><br>

                                                    <label class="col ml-2">Référence couleur:</label>
                                                    <input type="text" name="color" class="form-control ml-2" value="<?php echo $row2[$i]['color']; ?>"></input><br>

                                                    <label class="col ml-2">Quanitité:</label>
                                                    <input type="number" name="quantity" class="form-control ml-2" value="<?php echo $row2[$i]['quantity']; ?>"></input><br>

                                                    <div><input class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle ml-4 mb-2 mt-2" type="submit" name="Bouton4" value="Enregistrer"></div>

                                                </div>
                                                <!-- <script>
                            
                            var intervalID = window.setInterval(update, 2000);
    
                        function update(){
                                    fetch("./tag.php", { mode: 'no-cors' },
                                    {
                                        method: "GET",
                                        credentials: 'same-origin',
                                        headers: {
                                            'Accept': 'text/html',
                                        }
                                    }).
                                    then(function (response) {
                                        return response.text();
                                    }).then(function (string) {
                                        document.getElementById('tag_rfid').value=string;
                                        console.log(string)
                                        
                                    })
                                }
    
                        </script> -->
                                            </form>

                                            <!-- INSERTION D'UN PAQUET -->

                                        <?php }
                                    }
                                    if (isset($_GET["TAB"])):
                                        $tab = $_GET["TAB"]; ?>
                                        <form method="post" action="update.php?TAB=<?php echo ($tab); ?>">
                                            <div class="form-row">

                                                <label class="col ml-2">Numéro de paquet:</label>
                                                <input type="text" name="pack_num" class="form-control ml-2" required></input><br>

                                                <label class="col ml-2">Référence de l'Of:</label>
                                                <input type="text" name="of_num" class="form-control ml-2" required></input><br>

                                                <label class="col ml-2">Modèle:</label>
                                                <input type="text" name="model" class="form-control ml-2" required></input><br>

                                                <label class="col ml-2">Tag:</label>
                                                <input type="text" id="tag_id" name="tag_id" class="form-control ml-2"></input><br>

                                                <label class="col ml-2">Chaine de production:</label>
                                                <input type="text" name="prod_line" class="form-control ml-2" required></input><br>

                                                <label class="col ml-2">Taille:</label>
                                                <input type="text" name="size" class="form-control ml-2" required></input><br>

                                                <label class="col ml-2">Référence couleur:</label>
                                                <input type="text" name="color" class="form-control ml-2"></input><br>

                                                <label class="col ml-2">Quanitité:</label>
                                                <input type="number" name="qte" class="form-control ml-2" required></input><br>

                                                <div><input class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle ml-4 mb-2 mt-2" type="submit" name="Bouton5" value="Enregistrer"></div>

                                            </div>
                                        </form>
                                        <!-- <script>
                            
                            var intervalID = window.setInterval(update, 2000);
    
                        function update(){
                                    fetch("./tag.php", { mode: 'no-cors' },
                                    {
                                        method: "GET",
                                        credentials: 'same-origin',
                                        headers: {
                                            'Accept': 'text/html',
                                        }
                                    }).
                                    then(function (response) {
                                        return response.text();
                                    }).then(function (string) {
                                        document.getElementById('tag_id').value=string;
                                        console.log(string)
                                        
                                    })
                                }
    
                        </script> -->
                                        <!-- Modification d'une opératrice -->
                                        <?php endif;
                                    if (isset($_GET["operatrice"])):
                                        $opertrice = $_GET["operatrice"];
                                        $sql3 = "SELECT * FROM `init__employee` WHERE `matricule`='$opertrice';";
                                        $result3 = $con->query($sql3);
                                        while ($item = $result3->fetch_assoc()) {
                                            $row3[] = $item;
                                        }

                                        $sql4 = "SELECT `card_rfid` FROM `prod__affectation_card` ORDER by `id` DESC LIMIT 1;";
                                        $result = $con->query($sql4);
                                        while ($item = $result->fetch_assoc()) {
                                            $row4[] = $item;
                                        }
                                        for ($i = 0; $i < count($row3); $i++) { ?>
                                            <form method="post" action="update.php?operatrice=<?php echo ($opertrice); ?>">
                                                <div class="form-row">

                                                    <label class="col ml-2">Matricule:</label>
                                                    <input type="text" name="matricule" class="form-control ml-2" readonly="readonly" value="<?php echo ($opertrice); ?>"></input><br>

                                                    <label class="col ml-2">Nom :</label>
                                                    <input type="text" name="last_name" class="form-control ml-2" value="<?php echo ($row3[$i]['last_name']); ?>" required></input><br>

                                                    <label class="col ml-2">Prénom :</label>
                                                    <input type="text" name="first_name" class="form-control ml-2" value="<?php echo ($row3[$i]['first_name']); ?>" required></input><br>

                                                    <div class="col-md-12 ml-2">
                                                        <div class="form-group">
                                                            <label>Fonction</label>
                                                            <select class="form-control border-select" name="qualification">
                                                                <option class="option" value="OPERATOR">Opératrice</option>
                                                                <option class="option" value="MONITOR">Monitrice</option>
                                                                <option class="option" value="MAINTAINER">Maintenancier</option>
                                                                <!-- <option class="option" value="CONTROLLER">Controleuse</option> -->
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <label class="col ml-2">Carte RFID:</label>
                                                    <?php if ($row3[$i]['card_rfid'] != '') {
                                                        $tag = $row3[$i]['card_rfid'];
                                                    } else {
                                                        $tag = $row4[0]['card_rfid'];
                                                    }
                                                    ?>
                                                    <input type="text" id="rfid" name="card_rfid" class="form-control ml-2" readonly="readonly" value="<?php echo ($tag); ?>" required></input><br>

                                                    <div><input class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle ml-4 mb-2 mt-2" type="submit" name="Bouton6" value="Enregistrer"></div>

                                                </div>
                                            </form><?php }
                                            endif;
                                                    ?>
                                    <script>
                                        var intervalID = window.setInterval(update, 2000);

                                        function update() {
                                            fetch("./rfidd.php", {
                                                mode: 'no-cors'
                                            }, {
                                                method: "GET",
                                                credentials: 'same-origin',
                                                headers: {
                                                    'Accept': 'text/html',
                                                }
                                            }).
                                            then(function(response) {
                                                return response.text();
                                            }).then(function(string) {
                                                document.getElementById('rfid').value = string;
                                                console.log(string)

                                            })
                                        }
                                    </script>
                                    <!-- Modification sur une opération de la gamme -->
                                    <?php
                                    require_once './php/config.php';
                                    // récupérer l'ID de l'enregistrement à modifier à partir du paramètre GET
                                    if (isset($_GET["modele_id"]) || isset($_GET["operationcode"]) || isset($_GET["id"])) {
                                        $model = $_GET["modele_id"];
                                        $opcode = $_GET["operationcode"];
                                        $id = $_GET["id"];

                                        // récupérer les données de l'enregistrement à partir de la base de données
                                        $sql = "SELECT `prod__gamme`.`operation_num`, `prod__gamme`.`designation`, `prod__gamme`.`unit_time`, `prod__gamme`.`qte_h`, `prod__gamme`.`smartbox`, `prod__gamme`.`main_sb`, `prod__gamme`.`model_id`, `prod__gamme`.`id`, init__model.model, `machine_id`
                        FROM `prod__gamme` INNER JOIN `init__model` ON prod__gamme.model_id= init__model.id
                        WHERE `prod__gamme`.`model_id`=$model AND `prod__gamme`.`operation_num` LIKE '%$opcode%' AND `prod__gamme`.`id` =$id ";
                                        $result = $con->query($sql);
                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_assoc();
                                    ?>
                                            <form method="post" action="update.php">
                                                <div class="form-row">

                                                    <!-- <label>ID:</label> -->
                                                    <input type="hidden" name="id" readonly="readonly" value="<?php echo $row['id']; ?>"><br>

                                                    <label class="col ml-2">modèle:</label>
                                                    <input type="text" name="model" readonly="readonly" class="form-control ml-2" value="<?php echo $row['model']; ?>"><br>

                                                    <label class="col ml-2">Code de l'opération:</label>
                                                    <textarea name="operation_num" readonly="readonly" class="form-control ml-2"><?php echo $row['operation_num']; ?></textarea><br>

                                                    <label class="col ml-2">Designation:</label>
                                                    <textarea name="designation" class="form-control ml-2"><?php echo $row['designation']; ?></textarea><br>

                                                    <label class="col ml-2">Temps unitaire:</label>
                                                    <textarea name="unit_time" class="form-control ml-2"><?php echo $row['unit_time']; ?></textarea><br>

                                                    <label class="col ml-2">Quantité par heure:</label>
                                                    <textarea name="qte_h" class="form-control ml-2"><?php echo $row['qte_h']; ?></textarea><br>

                                                    <label class="col ml-2">Référence machine:</label>
                                                    <textarea name="reference" readonly="readonly" class="form-control ml-2"><?php echo $row['machine_id']; ?></textarea><br>

                                                    <label class="col ml-2">DigiTex:</label>
                                                    <textarea name="smartbox" readonly="readonly" class="form-control ml-2"><?php echo $row['smartbox']; ?></textarea><br>

                                                    <input class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle ml-4 mb-2 mt-2" type="submit" name="Bouton7" value="Modifier">

                                                </div>
                                            </form>
                                        <?php
                                        } else { ?>
                                            <script>
                                                alert("Enregistrement non trouvé.");
                                            </script>
                                    <?php
                                        }
                                    } ?>
                            </div>

                            <!-- INSERTION DE NOUVELLE opératrice -->
                            <?php
                            if (isset($_GET["newop"])):
                                if (!empty($_GET['status'])) {
                                    switch ($_GET['status']) {
                                        case 'errMatr':
                                            $statusType = 'alert-danger';
                                            $statusMsg = 'Matricule déjà utilisée par une autre opératrice';
                                            break;
                                        case 'errCart':
                                            $statusType = 'alert-danger';
                                            $statusMsg = 'Carte RFID déjà utilisée par une autre opératrice';
                                            break;

                                        default:
                                            $statusType = '';
                                            $statusMsg = '';
                                    }
                                }
                                if (!empty($statusMsg)) { ?>
                                    <div class="col-xs-12">
                                        <div class="alert <?php echo $statusType; ?> statusM" id="statusMessage">
                                            <?php echo $statusMsg; ?> <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div> <?php } ?>
                                <?php
                                $opertrice = $_GET["newop"];

                                $sql4 = "SELECT `card_rfid` FROM `prod__affectation_card` WHERE `id`= 1;";
                                $result = $con->query($sql4);
                                while ($item = $result->fetch_assoc()) {
                                    $rowtag[] = $item;
                                } ?>
                                <form method="post" action="update.php?operatrice=<?php echo ($opertrice); ?>">
                                    <div class="form-row">

                                        <label class="col ml-2">Matricule:</label>
                                        <input type="text" name="matricule" class="form-control ml-2" required></input><br>

                                        <label class="col ml-2">Nom :</label>
                                        <input type="text" name="last_name" class="form-control ml-2" required></input><br>

                                        <label class="col ml-2">Prénom :</label>
                                        <input type="text" name="first_name" class="form-control ml-2" required></input><br>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col ml-0">Fonction</label>
                                                <select class="form-control border-select" name="qualification">
                                                    <option class="option" value="OPERATOR">Opératrice</option>
                                                    <option class="option" value="MONITOR">Monitrice</option>
                                                    <option class="option" value="MAINTAINER">Maintenancier</option>
                                                    <option class="option" value="CONTROLLER">Controleuse</option>
                                                </select>
                                            </div>
                                        </div>

                                        <label class="col ml-2">Carte RFID:</label>
                                        <?php $tag = $rowtag[0]['card_rfid'];

                                        ?>
                                        <input type="text" id="rfid" name="card_rfid" class="form-control ml-2" readonly="readonly" value="<?php echo ($tag); ?>" required></input><br>

                                        <div><input class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle ml-4 mb-2 mt-2" type="submit" name="Bouton8" value="Enregistrer"></div>

                                    </div>
                                </form>
                                </form><?php endif;
                                        ?>
                            <script>
                                var intervalID = window.setInterval(update, 2000);

                                function update() {
                                    fetch("./rfidd.php", {
                                        mode: 'no-cors'
                                    }, {
                                        method: "GET",
                                        credentials: 'same-origin',
                                        headers: {
                                            'Accept': 'text/html',
                                        }
                                    }).
                                    then(function(response) {
                                        return response.text();
                                    }).then(function(string) {
                                        document.getElementById('rfid').value = string;
                                        console.log(string)

                                    })
                                }
                            </script>
                            <!-- INSERTION DE NOUVELLE opération -->
                            <?php
                            if (isset($_GET["newoperation"]) || isset($_GET["idmodel"])):
                                $operatione = $_GET["newoperation"];
                                $idmodel = $_GET["idmodel"];

                                $sql4 = "SELECT `model` FROM `init__model` WHERE id=$idmodel;";
                                $result = $con->query($sql4);
                                $tab = [];
                                while ($item = $result->fetch_assoc()) {
                                    $tab[] = $item;
                                }

                            ?>
                                &emsp; Ajouter une opération sur le modèle <?php echo $tab[0]['model']; ?>
                                <form method="post" action="update.php?idmodel=<?php echo ($idmodel); ?>&tab=<?php echo ($operatione); ?>">
                                    <div class="form-row">
                                        <input type="hidden" name="model" readonly="readonly" value="<?php echo ($idmodel . ''); ?>"><br>
                                        <br>
                                        <label class="col ml-2">Code de l'opération:</label>
                                        <textarea name="operation_num" class="form-control ml-2"></textarea><br>

                                        <label class="col ml-2">Designation:</label>
                                        <textarea name="designation" class="form-control ml-2"></textarea><br>

                                        <label class="col ml-2">Temps unitaire:</label>
                                        <textarea name="unit_time" class="form-control ml-2"></textarea><br>

                                        <label class="col ml-2">Quantité par heure:</label>
                                        <textarea name="qte_h" class="form-control ml-2"></textarea><br>

                                        <label class="col ml-2">Référence machine:</label>
                                        <textarea name="reference" readonly="readonly" class="form-control ml-2"></textarea><br>

                                        <label class="col ml-2">DigiTex:</label>
                                        <textarea name="smartbox" readonly="readonly" class="form-control ml-2"></textarea><br>

                                        <div><input class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle ml-4 mb-2 mt-2" type="submit" name="Bouton9" value="Enregistrer"></div>

                                    </div>
                                </form><?php endif;
                                        ?>
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
                        <span> Copyright &copy; Advantry X <?php echo date("Y"); ?></span>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="js/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>