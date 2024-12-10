<?php

// Set the default timezone first
date_default_timezone_set('Africa/Tunis');

// Include the configuration file
require_once './php/config.php';

// The rest of your code follows...
try {
    // Récupérer la première chaîne de production
    $sql = "SELECT prod_line FROM init__prod_line
            WHERE prod_line NOT LIKE 'CH_Q'
            ORDER BY id ASC LIMIT 1";
    $result = $con->query($sql);

    if ($result === false) {
        throw new Exception("Database query failed: " . $con->error);
    }

    // Fetch the first production line
    $firstProdline = $result->fetch_assoc()['prod_line'] ?? null;

    if ($firstProdline !== null) {
        // Redirection vers `indexProdline.php` avec la première chaîne de production
        header("Location: indexprodline.php?prod_line=" . urlencode($firstProdline));
        exit();
    } else {
        // Handle case where no production line is found
        error_log("No production lines found in the database.");
        // Optionally, redirect to an error page or display a message
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    // Handle error gracefully, e.g., show a user-friendly message or redirect to an error page
    // echo "An error occurred. Please try again later.";
}
