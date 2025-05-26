<?php
session_start();
require '../config/db.php';
require '../models/CupomModel.php';

if (isset($_GET['remover']) && is_numeric($_GET['remover'])) {
    $index = (int) $_GET['remover'];
    if (isset($_SESSION['carrinho'][$index])) {
        unset($_SESSION['carrinho'][$index]);
        $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
    }
    header('Location: carrinho.php');
    exit;
}

$itens = $_SESSION['carrinho'] ?? [];
$subtotal = 0;
$frete_total = 0;
foreach ($itens as $item) {
    $subtotal += $item['preco'] * $item['quantidade'];
    $frete_total += $item['frete'];
}

$cupom_aplicado = null;
$desconto = 0;
if (!empty($_POST['cupom'])) {
    $codigo = strtoupper(trim($_POST['cupom']));
    $cupons = CupomModel::listar();
    $hoje = date('Y-m-d');
    foreach ($cupons as $cupom) {
        $valor_minimo = floatval(str_replace(',', '.', $cupom['valor_minimo']));
        $desconto_convertido = floatval(str_replace(',', '.', $cupom['desconto']));
        $codigo_cupom = strtoupper(trim($cupom['codigo']));

        if (
            $codigo_cupom === $codigo &&
            $subtotal >= $valor_minimo &&
            $cupom['validade'] >= $hoje
        ) {
            $cupom_aplicado = $cupom;
            $desconto = $desconto_convertido;
            $_SESSION['cupom'] = $cupom;
            break;
        }
    }
}

$total = $subtotal + $frete_total - $desconto;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Seu Carrinho</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Resumo do Carrinho</h2>

    <?php if (empty($itens)): ?>
        <div class="alert alert-warning">Seu carrinho está vazio.</div>
        <a href="produtos.php" class="btn btn-primary">Voltar</a>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID Produto</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th>Frete</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itens as $i => $item): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= $item['produto_id'] ?></td>
                        <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                        <td><?= $item['quantidade'] ?></td>
                        <td>R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></td>
                        <td>R$ <?= number_format($item['frete'], 2, ',', '.') ?></td>
                        <td>
                            <a href="?remover=<?= $i ?>" class="btn btn-sm btn-danger" onclick="return confirm('Remover este item do carrinho?')">Remover</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <form method="POST" class="mb-4">
            <div class="input-group">
                <input type="text" name="cupom" class="form-control" placeholder="Código do cupom" required>
                <button class="btn btn-success" type="submit">Aplicar Cupom</button>
            </div>
        </form>

        <?php if ($cupom_aplicado): ?>
            <div class="alert alert-success">
                Cupom <strong><?= $cupom_aplicado['codigo'] ?></strong> aplicado com sucesso. Desconto de R$ <?= number_format($desconto, 2, ',', '.') ?>
            </div>
        <?php elseif (!empty($_POST['cupom'])): ?>
            <div class="alert alert-danger">
                Cupom inválido ou subtotal insuficiente para este cupom.
            </div>
        <?php endif; ?>

        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between">
                <strong>Subtotal:</strong> R$ <?= number_format($subtotal, 2, ',', '.') ?>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <strong>Frete:</strong> R$ <?= number_format($frete_total, 2, ',', '.') ?>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <strong>Desconto:</strong> R$ <?= number_format($desconto, 2, ',', '.') ?>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <strong>Total:</strong> <span class="text-success">R$ <?= number_format($total, 2, ',', '.') ?></span>
            </li>
        </ul>

        <a href="produtos.php" class="btn btn-secondary mt-4">Continuar Comprando</a>
    <?php endif; ?>
</body>
</html>
