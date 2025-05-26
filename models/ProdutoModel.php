<?php
require_once __DIR__ . '/../config/db.php';

class ProdutoModel {
    public static function criar($nome, $preco, $variacoes) {
        global $mysqli;
        $stmt = $mysqli->prepare("INSERT INTO produtos (nome, preco) VALUES (?, ?)");
        $stmt->bind_param("sd", $nome, $preco);
        $stmt->execute();
        $produto_id = $stmt->insert_id;
        foreach ($variacoes as $variacao => $qtd) {
            $stmt = $mysqli->prepare("INSERT INTO estoque (produto_id, variacao, quantidade) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $produto_id, $variacao, $qtd);
            $stmt->execute();
        }
        return $produto_id;
    }

    public static function atualizar($id, $nome, $preco) {
        global $mysqli;
        $stmt = $mysqli->prepare("UPDATE produtos SET nome = ?, preco = ? WHERE id = ?");
        $stmt->bind_param("sdi", $nome, $preco, $id);
        return $stmt->execute();
    }

    public static function listar() {
        global $mysqli;
        $result = $mysqli->query("SELECT * FROM produtos");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
