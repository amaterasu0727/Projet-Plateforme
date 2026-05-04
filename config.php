<?php
$host = "sql200.infinityfree.com";
$dbname = "if0_41822709_magasin";
$user = "if0_41822709";
$pass = "Vladimir0929";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>