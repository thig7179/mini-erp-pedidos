<?php
$mysqli = new mysqli("localhost", "root", "", "mini_erp");
if ($mysqli->connect_error) {
    die("Erro na conexão: " . $mysqli->connect_error);
}
?>
