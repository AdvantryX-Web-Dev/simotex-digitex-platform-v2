<?php

// Connexion à la base de données
// $con = mysqli_connect("127.0.0.1", "root", "#R3DR&uE3k0RuMk38", "db_simotex");
$con = mysqli_connect("db", "root", "#R3DR&uE3k0RuMk38", "db_simotex");

$rfid = null;
// Récupération de la valeur du champ "valeur"
$query = "SELECT `card_rfid` FROM `prod__affectation_card` WHERE `id`=1";
$result = $con->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $rfid = $row['card_rfid'];
}
// Renvoi de la rfid
echo $rfid;
