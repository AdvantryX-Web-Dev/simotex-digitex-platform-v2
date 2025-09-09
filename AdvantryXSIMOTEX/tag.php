<?php
// Connexion à la base de données
// $pdo = new PDO('mysql:host=127.0.0.1; dbname=db_mahdco', 'root', '5pYK@pwUprAI65)S');
$pdo = new PDO('mysql:127.0.0.1; dbname=db_simotex', 'root', '5pYK@pwUprAI65)S');
// $pdo = new PDO('mysql:host=127.0.0.1;dbname=db_mahdco', 'root', '');
$tag = null;

$query = $pdo->query("SELECT `tag_rfid` FROM `prod__affectation` WHERE `id`=1");
$tag = $query->fetchColumn();
// Renvoi de la rfid
echo $tag;
