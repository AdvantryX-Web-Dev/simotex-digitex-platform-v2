<?php
require_once './php/config.php';

if (isset($_POST['Bouton'])) {
  // Récupère les données du formulaire
  $id = $_POST['id'];
  $model_id = $_POST['model_id'];
  $model = $_POST['model'];
  $opcode = $_POST['operation_code'];
  $designation = $_POST['designation'];

  $sql = "SELECT * FROM `prod__gamme`
  INNER JOIN `init__model` ON `prod__gamme`.`model_id`= `init__model`.`id`
  WHERE `prod__gamme`.`model_id`= '$model_id'
  ORDER BY `operation_num` DESC LIMIT 1";
  $rslt = $con->query($sql);

  $p3 = [];
  while ($item = $rslt->fetch_assoc()) {
    $p3[] = $item;
  }

  if ($p3[0]['operation_num'] < $opcode) {

    $sql = "DELETE FROM `prod__gamme` WHERE `operation_num`=$opcode AND `id`=$id AND `model_id`=$model_id";
    $result = $con->query($sql);

    $sql = "UPDATE `prod__gamme` SET `operation_num`=`operation_num`-1 WHERE `operation_num`>=$opcode AND `model_id`=$model_id";
    $rslt = $con->query($sql);
  }
  // else {
  //   $sql = "DELETE FROM `prod__gamme` WHERE `operation_num`=$opcode AND `id`=$id AND `model_id`=$model_id";
  //   $result = $con->query($sql);
  // }
  // Redirige l'utilisateur vers la page précédente
  header('Location: gameequilibrage.php?model_id=' . $model_id);
}

if (isset($_POST['BoutonOf'])) {
  // Récupère les données du formulaire
  $id = $_POST['id'];
  $of = $_POST['of_num'];
  $client = $_POST['client'];
  $atelier = $_POST['asm_shop'];
  $ofstat = $_POST['of_state'];

  $sql1 = "DELETE FROM `prod__of` WHERE `id`='$id' AND `of_num`='$of' AND `client`='$client' AND `asm_shop`='$atelier' AND `of_state`='$ofstat'";
  $result1 = $con->query($sql1);
  $sql3 = "DELETE FROM `prod__pack_operation` WHERE `of_num`='$of'";
  $result3 = $con->query($sql3);
  // Redirige l'utilisateur vers la page précédente
  header('Location: ordreDeFab.php');
}

if (isset($_POST['Boutonpack'])) {
  // Récupère les données du formulaire
  $id = $_POST['id'];
  $pack = $_POST['pack_num'];
  $of = $_POST['of_num'];
  $tag = $_POST['tag_rfid'];
  $prod_line = $_POST['prod_line'];
  $size = $_POST['size'];
  $color = $_POST['color'];
  $qte = $_POST['quantity'];

  $sql1 = "DELETE FROM `prod__packet` WHERE `id`='$id' AND `pack_num`='$pack'";
  $result1 = $con->query($sql1);

  // Redirige l'utilisateur vers la page précédente
  header('Location: allpacks.php');
}
if (isset($_POST['Boutonoperat'])) {
  // Récupère les données du formulaire
  $id = $_POST['id'];
  $reg = $_POST['reg_num'];
  $name = $_POST['full_name'];
  $ctag = $_POST['card_id'];


  $sql1 = "DELETE FROM `init__employee`  WHERE `matricule`='$reg'";
  $result1 = $con->query($sql1);

  // Redirige l'utilisateur vers la page précédente
  header('Location: rh.php');
}
if (isset($_POST['Supoperation'])) {
  // Récupère les données du formulaire
  $id = $_POST['id'];
  $model = $_POST['model'];
  $opcode = $_POST['operation_num'];
  $designation = $_POST['designation'];
  $time = $_POST['unit_time'];
  $qte = $_POST['qte_h'];
  $Digitex = $_POST['smartbox'];

  $sqll = "SELECT id FROM init__model WHERE model='$model'";
  $rslt = $con->query($sqll);
  $modelid = [];
  while ($item3 = $rslt->fetch_assoc()) {
    $modelid[] = $item3;
  }

  $idmodel = $modelid[0]['id'];

  $sql1 = "DELETE FROM `prod__gamme`  WHERE `id`=$id";
  $result1 = $con->query($sql1);

  $sql = "UPDATE `prod__gamme` SET `operation_num`=`operation_num`-1 WHERE `operation_num`>=$opcode";
  $rslt = $con->query($sql);

  // Redirige l'utilisateur vers la page précédente
  header('Location: gammeequilibrage.php?model_id=' . $idmodel);
}
