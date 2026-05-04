<?php
$host = "sql200.infinityfree.com";
$dbname = "ifo_41822709_magasin";
$user = "ifo_41822709";
$pass = "Vladimir0929";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log($e->getMessage());
    die("Une erreur de connexion est survenue. Veuillez réessayer plus tard.");
}
?>