<?php
require_once './php/config.php';

if (isset($_POST['Bouton'])) {
  // Récupère les données du formulaire
  $id = $_POST['model_id'];
  $model = $_POST['model'];
  $of = $_POST['of_num'];
  $pack = $_POST['pack_num'];
  $opcode = $_POST['operation_num'];
  $designation = $_POST['designation'];
  $time = $_POST['unit_time'];
  $qte = $_POST['qte_h'];

  $sql1 = "UPDATE `prod__gamme` SET `designation`='$designation', `unit_time`='$time' ,`qte_h`='$qte' WHERE `model_id` = '$id' AND `operation_num`=$opcode";
  $rslt = $con->query($sql1);

  $sql2 = "UPDATE `prod__pack_operation` SET `designation`='$designation', `unit_time`='$time'  WHERE `model`='$model' AND `operation_num` = '$opcode'";
  $rslt2 = $con->query($sql2);

  // Redirige l'utilisateur vers la page précédente
  header('Location: gameequilibrage.php?model_id=' . $id);
}

if (isset($_POST['Bouton1'])) {
  // Récupère les données du formulaire
  $id = $_POST['id'];
  $model = $_POST['model'];
  $of = $_POST['of_num'];
  $pack = $_POST['pack_num'];
  $opcode = $_POST['operation_code'];
  $designation = $_POST['designation'];
  $time = $_POST['unit_time'];
  $qte = $_POST['qte_h'];

  $sql1 = "DELETE FROM `prod__gamme` WHERE `model`='$model' AND `of_num`='$of'AND `designation`='$designation'AND `unit_time`='$time' AND`qte_h`='$qte' AND `operation_code`=$opcode";
  $rslt = $con->query($sql1);
  $sql2 = "DELETE FROM `p4_pack_operation` WHERE `model`='$model' AND `of_num`='$of'AND `designation`='$designation'AND `unit_time`='$time'  AND `operation_code`LIKE '$opcode'";
  $rslt2 = $con->query($sql2);

  // Redirige l'utilisateur vers la page précédente
  header('Location: allgamme.php');
}
if (isset($_POST['Bouton2'])) {
  // Récupère les données du formulaire
  $model = $_POST['model'];
  $of = $_POST['of_num'];
  $pack = $_POST['pack_num'];
  $opcode = $_POST['operation_code'];
  $designation = $_POST['designation'];
  $time = $_POST['unit_time'];
  $qte = $_POST['qte_h'];
  $machine = $_POST['machine_ref'];
  $digitex = $_POST['digitex'];
  $opstat = $_POST['op_state'];

  $sql3 = "INSERT INTO `p3_gamme`( `model`, `of_num`, `pack_num`, `operation_code`, `designation`, `unit_time`, `qte_h`, `machine_ref`, `digitex`, `op_state`, `import_dt`)
  VALUES ('$model','$of','$pack','$opcode','$designation','$time','$qte','$machine','$digitex','$opstat', 'current_timestamp()')";
  $rslt3 = $con->query($sql3);

  header('Location: allgamme.php');
}

if (isset($_POST['Bouton3'])) {
  // Récupère les données du formulaire
  $id = $_POST['id'];
  $of = $_POST['of_num'];
  $client = $_POST['client'];
  $atelier = $_POST['asm_shop'];
  $ofstat = $_POST['of_state'];
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  $import = $_POST['import_dt'];

  $sql4 = "UPDATE `prod__of` SET `of_num`='$of', `client`='$client', `asm_shop`='$atelier' ,`of_state`='$ofstat', `start_date`='$start', `end_date`='$end',`import_dt`='$import' WHERE `of_num` LIKE '$of'";
  $rslt = $con->query($sql4);
  // Redirige l'utilisateur vers la page précédente
  header('Location: ordreDeFab.php');
}

if (isset($_POST['Bouton4'])) {
  // Récupère les données du formulaire
  $id = $_POST['id'];
  $of = $_POST['of_num'];
  $pack = $_POST['pack_num'];
  $model = $_POST['model'];
  $prodline = $_POST['prod_line'];
  $tag = $_POST['tag_rfid'];
  $size = $_POST['size'];
  $color = $_POST['color'];
  $qte = $_POST['quantity'];

  $sql5 = "UPDATE `prod__packet` SET `of_num`='$of',`prod_line`='$prodline', `tag_rfid`='$tag', `size`='$size',`color`='$color',`quantity`='$qte' WHERE `pack_num` LIKE '$pack' AND `id`='$id'";
  $rslt = $con->query($sql5);
  $sql5 = "UPDATE `prod__pack_operation` SET `of_num`='$of', `model`='$model', `pack_qty`='$qte' WHERE `pack_num`='$pack'";
  $rslt = $con->query($sql5);

  // Redirige l'utilisateur vers la page précédente
  header('Location: allpacks.php');
}
if (isset($_POST['Bouton5'])) {
  // Récupère les données du formulaire
  $of = $_POST['of_num'];
  $pack = $_POST['pack_num'];
  $model = $_POST['model'];
  $prodline = $_POST['prod_line'];
  $tag = $_POST['tag_id'];
  $size = $_POST['size'];
  $color = $_POST['color'];
  $qte = $_POST['qte'];

  $sql = "INSERT INTO `prod__packet`( `tag_rfid`, `model`, `of_num`, `pack_num`, `prod_line`, `color`, `size`, `quantity`, `import_dt`)
        VALUES ('$tag','$model','$of','$pack','$prodline','$color','$size','$qte','current_timestamp()')";
  $rslt3 = $con->query($sql);

  header('Location: allpacks.php');
}
if (isset($_POST['Bouton6'])) {
  // Récupère les données du formulaire
  $matricule = $_POST['matricule'];
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $qualification = $_POST['qualification'];
  $card_rfid = $_POST['card_rfid'];

  // Vérifie si la carte RFID est déjà utilisée
  $sql_check = "SELECT * FROM `init__employee` WHERE `card_rfid` = '$card_rfid' AND `matricule` != $matricule";
  $result_check = $con->query($sql_check);
  if ($result_check->num_rows > 0) {
    $sql = "UPDATE `prod__affectation_card` SET `card_rfid` = '' WHERE `id` = 1";
    $rslt = $con->query($sql);
    // Affiche le message d'erreur dans un popup
    echo "<script>alert('Carte déjà utilisée par une autre opératrice'); window.location.href = 'edit.php?operatrice='+'$matricule';</script>";
  } else {
    if ($card_rfid != '') {
      $sql = "UPDATE `init__employee` SET `card_rfid` = '$card_rfid', `first_name` ='$first_name',  `last_name` ='$last_name',  `qualification` = '$qualification' WHERE `matricule` = $matricule";
      $rslt = $con->query($sql);
      $sql = "UPDATE `prod__affectation_card` SET `card_rfid` = '' WHERE `id` = 1";
      $rslt = $con->query($sql);
    } else {
      $sql = "UPDATE `init__employee` SET `card_rfid` = NULL, `first_name` ='$first_name',  `last_name` ='$last_name',  `qualification` = '$qualification' WHERE `matricule` = $matricule";
      $rslt = $con->query($sql);
      $sql = "UPDATE `prod__affectation_card` SET `card_rfid` = '' WHERE `id` = 1";
      $rslt = $con->query($sql);
      $sql = "UPDATE `init__employee` SET `card_rfid` = NULL WHERE `card_rfid` = ''";
      $rslt = $con->query($sql);
    }

    header('Location: rh.php');
  }
}

if (isset($_POST['Bouton7'])) {
  // Récupère les données du formulaire
  $id = $_POST['id'];
  $model = $_POST['model'];
  $opcode = $_POST['operation_num'];
  $designation = $_POST['designation'];
  $time = $_POST['unit_time'];
  $qte = $_POST['qte_h'];
  $machine = $_POST['reference'];
  $Digitex = $_POST['smartbox'];

  $sqlm = "SELECT `id` FROM `init__model` WHERE `model`='$model'";
  $rsltm = $con->query($sqlm);
  while ($rowm = mysqli_fetch_assoc($rsltm)) {
    $model_id = $rowm["id"];
  };

  $sql1 = "UPDATE `prod__gamme` SET `designation`='$designation', `unit_time`='$time' ,`qte_h`='$qte', `machine_id`='$machine', `smartbox`='$Digitex' WHERE `id` = '$id' AND `operation_num`=$opcode";
  $rslt = $con->query($sql1);

  $sql2 = "UPDATE `prod__pack_operation` SET `designation`='$designation', `unit_time`='$time', `machine_id`='$machine', `smartbox`='$Digitex'  WHERE `model`='$model' AND `operation_num` = '$opcode'";
  $rslt2 = $con->query($sql2);

  // $sql3 = "UPDATE `prod__gamme` SET `designation`='$designation', `unit_time`='$time', `machine_id`='$machine', `smartbox`='$Digitex'  WHERE `model_id`='$model_id' AND `operation_num` = '$opcode'";
  // $rslt3= $con->query($sql3);

  // Redirige l'utilisateur vers la page précédente
  header('Location: gammeequilibrage.php?model_id=' . $model_id);
}

if (isset($_POST['Bouton8'])) {
  // Récupère les données du formulaire
  $matricule = $_POST['matricule'];
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $qualification = $_POST['qualification'];
  $card_rfid = $_POST['card_rfid'];

  // Vérifie si la carte RFID est déjà utilisée
  $sql_check = "SELECT * FROM `init__employee` WHERE `card_rfid` = '$card_rfid' OR `matricule` = $matricule";
  $result_check = $con->query($sql_check);
  if ($result_check->num_rows > 0) {
    $sql = "UPDATE `prod__affectation_card` SET `card_rfid` = NULL WHERE `id` = 1";
    $rslt = $con->query($sql);
    // Affiche le message d'erreur dans un popup
    $sql_mat = "SELECT * FROM `init__employee` WHERE `matricule` = $matricule";
    $rslt_mat = $con->query($sql_mat);
    if ($rslt_mat->num_rows > 0) {
      // echo "<script>alert('Matricule déjà utilisée par une autre opératrice'); window.location.href = 'edit.php?newop=init__employee';</script>";
      $msg = '&&status=errMatr';
    }
    $sql_card = "SELECT * FROM `init__employee` WHERE `card_rfid` = '$card_rfid'";
    $rslt_card = $con->query($sql_card);
    if ($rslt_card->num_rows > 0) {
      // echo "<script>alert('Carte RFID déjà utilisée par une autre opératrice'); window.location.href = 'edit.php?newop=init__employee';</script>";
      $msg = '&&status=errCart';
    }
  } else {
    if ($card_rfid != "") {
      $sql1 = "INSERT INTO `init__employee`(`card_rfid`, `matricule`, `first_name`, `last_name`, `qualification`) 
              VALUES ('$card_rfid', $matricule, '$first_name', '$last_name', '$qualification')";
      $rslt = $con->query($sql1);
      $sql = "UPDATE `prod__affectation_card` SET `card_rfid` = '' WHERE `id` = 1";
      $rslt = $con->query($sql);
    } else {
      $sql1 = "INSERT INTO `init__employee`(`card_rfid`, `matricule`, `first_name`, `last_name`, `qualification`) 
              VALUES (NULL, $matricule, '$first_name', '$last_name',  '$qualification')";
      $rslt = $con->query($sql1);
      $sql = "UPDATE `prod__affectation_card` SET `card_rfid` = '' WHERE `id` = 1";
      $rslt = $con->query($sql);
      $sql = "UPDATE `init__employee` SET `card_rfid` = NULL WHERE `card_rfid` = ''";
      $rslt = $con->query($sql);
    }
    header('Location: rh.php');
  }

  header('Location: edit.php?newop=init__employee' . $msg);
}

if (isset($_POST['Bouton9'])) {
  // Récupère les données du formulaire
  $model = $_POST['model'];
  $opcode = $_POST['operation_num'];
  $designation = $_POST['designation'];
  $time = $_POST['unit_time'];
  $qte = $_POST['qte_h'];
  $machine = $_POST['reference'];
  $digitex = $_POST['smartbox'];

  if ($opcode == 1) {

    $sql2 = "UPDATE `prod__gamme` SET `operation_num`=`operation_num`+1 WHERE `model_id`=$model";
    $rslt2 = $con->query($sql2);

    $sql3 = "INSERT INTO `prod__gamme`(`model_id`, `operation_num`, `designation`, `unit_time`, `qte_h`, `machine_id`, `smartbox`, `import_dt`)
    VALUES ($model ,$opcode,'$designation','$time','$qte','$machine','$digitex', current_timestamp())";
    $rslt3 = $con->query($sql3);

    $sql4 = "SELECT id FROM `prod__gamme` WHERE `model_id`=$model AND  `operation_num`=$opcode AND `designation`='$designation' LIMIT 1";
    $rslt4 = $con->query($sql4);
    while ($row = mysqli_fetch_assoc($rslt4)) {
      $id = $row["id"];
    }

    $sql5 = "INSERT INTO `prod__pack_gamme`(`pack_num`, `gamme_id`)
      SELECT
          `prod__packet`.`pack_num`,
          `prod__gamme`.`id`
      FROM
          `prod__packet`
      JOIN `prod__of` ON `prod__packet`.`of_num` = `prod__of`.`of_num`
      JOIN `init__model` ON `prod__of`.`model_id` = `init__model`.`id`
      JOIN `prod__gamme` ON `prod__of`.`model_id` = `prod__gamme`.`model_id`
      WHERE
          `prod__of`.`model_id` = '$model' AND `prod__gamme`.`id` = '$id'";
    $rslt5 = $con->query($sql5);
  } else {
    $sql2 = "UPDATE `prod__gamme` SET `operation_num`=`operation_num`+1 WHERE `model_id`=$model AND `operation_num`>=$opcode";
    $rslt2 = $con->query($sql2);

    $sql3 = "INSERT INTO `prod__gamme`(`model_id`, `operation_num`, `designation`, `unit_time`, `qte_h`, `machine_id`, `smartbox`, `import_dt`)
    VALUES ($model ,$opcode,'$designation','$time','$qte','$machine','$digitex', current_timestamp())";
    $rslt3 = $con->query($sql3);

    $sql4 = "SELECT id FROM `prod__gamme` WHERE `model_id`=$model AND `operation_num`=$opcode AND `designation`='$designation' LIMIT 1";
    $rslt4 = $con->query($sql4);
    while ($row = mysqli_fetch_assoc($rslt4)) {
      $id = $row["id"];
    }

    $sql5 = "INSERT INTO `prod__pack_gamme`(`pack_num`, `gamme_id`)
      SELECT
          `prod__packet`.`pack_num`,
          `prod__gamme`.`id`
      FROM
          `prod__packet`
      JOIN `prod__of` ON `prod__packet`.`of_num` = `prod__of`.`of_num`
      JOIN `init__model` ON `prod__of`.`model_id` = `init__model`.`id`
      JOIN `prod__gamme` ON `prod__of`.`model_id` = `prod__gamme`.`model_id`
      WHERE
          `prod__of`.`model_id` = '$model' AND `prod__gamme`.`id` = '$id'";
    $rslt5 = $con->query($sql5);
  }


  header('Location: equilibrage.php');
}
