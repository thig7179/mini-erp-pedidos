<?php
require '../models/CupomModel.php';
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['desativar'])) {
    $id = intval($_POST['id']);
    $stmt = $mysqli->prepare("UPDATE cupons SET ativo = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header('Location: cupons.php?sucesso=1');
    exit;
}

$cupons = CupomModel::listar();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gerenciar Cupons</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Novo Cupom</h2>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success">Operação realizada com sucesso!</div>
    <?php endif; ?>

    <form action="../controllers/CupomController.php" method="POST">
        <div class="mb-3">
            <label>Código do Cupom</label>
            <input type="text" name="codigo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Desconto (R$)</label>
            <input type="text" name="desconto" id="desconto" class="form-control money" required>
        </div>
        <div class="mb-3">
            <label>Valor Mínimo do Carrinho (R$)</label>
            <input type="text" name="valor_minimo" id="valor_minimo" class="form-control money" required>
        </div>
        <div class="mb-3">
            <label>Validade</label>
            <input type="date" name="validade" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Salvar Cupom</button>
        <a href="produtos.php" class="btn btn-secondary mt-2">Voltar</a>
    </form>

    <hr class="my-5">
    <h4>Cupons Cadastrados</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Desconto</th>
                <th>Valor Mínimo</th>
                <th>Validade</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cupons as $cupom): ?>
                <tr>
                    <td><?= $cupom['codigo'] ?></td>
                    <td>R$ <?= number_format($cupom['desconto'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($cupom['valor_minimo'], 2, ',', '.') ?></td>
                    <td><?= date('d/m/Y', strtotime($cupom['validade'])) ?></td>
                    <td>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?= $cupom['id'] ?>">
                            <button type="submit" name="desativar" class="btn btn-sm btn-danger" onclick="return confirm('Deseja desativar este cupom?')">Desativar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.money').on('input', function () {
                let val = this.value.replace(/\D/g, '');
                val = (parseInt(val, 10) / 100).toFixed(2);
                this.value = val.replace('.', ',');
            });
        });
    </script>
</body>
</html>