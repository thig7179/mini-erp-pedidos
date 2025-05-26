<?php
session_start();
require '../models/ProdutoModel.php';
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'comprar') {
        $produto_id = intval($_POST['produto_id']);
        $preco = floatval($_POST['preco']);
        $quantidade = intval($_POST['quantidade']);

        $subtotal = $preco * $quantidade;
        if ($subtotal > 200) {
            $frete = 0;
        } elseif ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15;
        } else {
            $frete = 20;
        }

        $_SESSION['carrinho'][] = [
            'produto_id' => $produto_id,
            'preco' => $preco,
            'quantidade' => $quantidade,
            'frete' => $frete
        ];

        header('Location: ../views/carrinho.php');
        exit;
    }

    if ($action === 'atualizar') {
        $id = intval($_POST['produto_id']);
        $nome = $_POST['nome'];
        $preco = floatval(str_replace(["R$", ","], ["", "."], $_POST['preco']));

        ProdutoModel::atualizar($id, $nome, $preco);

        header('Location: ../views/produtos.php');
        exit;
    }

    if ($action === 'cadastrar') {
        $nome = $_POST['nome'];
        $preco = floatval(str_replace(["R$", ","], ["", "."], $_POST['preco']));
        $variacoes = $_POST['variacoes'] ?? [];
        $estoques = $_POST['estoques'] ?? [];
        $variacoes_estoque = array_combine($variacoes, $estoques);
        ProdutoModel::criar($nome, $preco, $variacoes_estoque);
        header('Location: ../views/produtos.php');
        exit;
    }

    if ($action === 'excluir') {
        $id = intval($_POST['produto_id']);
        $stmt = $mysqli->prepare("DELETE FROM produtos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header('Location: ../views/produtos.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'comprar_rapido') {
    $produto_id = intval($_GET['id']);
    $preco = floatval($_GET['preco']);
    $quantidade = 1;

    $subtotal = $preco * $quantidade;
    if ($subtotal > 200) {
        $frete = 0;
    } elseif ($subtotal >= 52 && $subtotal <= 166.59) {
        $frete = 15;
    } else {
        $frete = 20;
    }

    $_SESSION['carrinho'][] = [
        'produto_id' => $produto_id,
        'preco' => $preco,
        'quantidade' => $quantidade,
        'frete' => $frete
    ];

    header('Location: ../views/carrinho.php');
    exit;
}
?>