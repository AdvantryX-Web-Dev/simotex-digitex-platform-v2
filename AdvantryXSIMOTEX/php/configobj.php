<?php
// $conn = mysqli_connect("127.0.0.1", "root", "#R3DR&uE3k0RuMk38", "db_inter");
$conn = mysqli_connect("db", "root", "#R3DR&uE3k0RuMk38", "db_inter");
if ($con) {
    // echo "DB connected";
} else {
    echo "DB connection is failed";
    exit();
}
