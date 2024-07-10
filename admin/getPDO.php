<?php

function getPDO() {
    // Define your database connection parameters here
    $servername = "localhost";
    $database = "votingadvance";
    $username = "root";
    $password = "";

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("SET NAMES utf8");
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    return $pdo;
}
?>
