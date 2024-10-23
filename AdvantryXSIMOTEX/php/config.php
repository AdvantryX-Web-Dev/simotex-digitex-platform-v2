<?php
$con = mysqli_connect("db", "root", "#R3DR&uE3k0RuMk38", "db_simotex");
if ($con) {
    // echo "DB connected";
} else {
    echo "DB connection is failed";
    exit();
}
