<?php
require_once __DIR__ . '/../config/db.php';
global $mysqli;

class CupomModel {
    public static function criar($codigo, $desconto, $valor_minimo, $validade) {
        global $mysqli;

        // Converter valores para float
        $desconto = floatval(str_replace(',', '.', $desconto));
        $valor_minimo = floatval(str_replace(',', '.', $valor_minimo));

        $stmt = $mysqli->prepare("INSERT INTO cupons (codigo, desconto, valor_minimo, validade, ativo) VALUES (?, ?, ?, ?, 1)");
        $stmt->bind_param("sdds", $codigo, $desconto, $valor_minimo, $validade);
        $stmt->execute();
    }

    public static function listar() {
        global $mysqli;
        return $mysqli->query("SELECT * FROM cupons WHERE ativo = 1")->fetch_all(MYSQLI_ASSOC);
    }
}
