<?php
// $con = mysqli_connect("127.0.0.1", "root", "#R3DR&uE3k0RuMk38", "digitex_maint_mahdco");
$con = mysqli_connect("db", "root", "#R3DR&uE3k0RuMk38", "digitex_maint_mahdco");
if ($con) {
    // echo "DB connected";
} else {
    echo "DB connection is failed";
    exit();
}
