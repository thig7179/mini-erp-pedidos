<?php
$mysqli = new mysqli("localhost", "root", "Th717916@", "mini_erp", 3308);
if ($mysqli->connect_error) {
    die("Erro na conexão: " . $mysqli->connect_error);
}
?>
