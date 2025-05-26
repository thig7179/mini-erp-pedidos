<?php
require '../config/db.php';

$query = "
    SELECT e.id, p.nome AS produto, e.variacao, e.quantidade, e.atualizado_em
    FROM estoque e
    JOIN produtos p ON e.produto_id = p.id
";
$result = $mysqli->query($query);
$estoques = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gerenciamento de Estoque</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
</head>
<body class="container mt-5">
    <h2 class="mb-4">Estoque de Produtos</h2>
    <table id="tabela-estoque" class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Produto</th>
                <th>Variação</th>
                <th>Quantidade</th>
                <th>Atualizado Em</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($estoques as $e): ?>
                <tr>
                    <td><?= $e['id'] ?></td>
                    <td><?= $e['produto'] ?></td>
                    <td><?= $e['variacao'] ?></td>
                    <td><?= $e['quantidade'] ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($e['atualizado_em'])) ?></td>
                    <td>
                        <form action="atualizar_estoque.php" method="POST" class="d-flex">
                            <input type="hidden" name="id" value="<?= $e['id'] ?>">
                            <input type="number" name="quantidade" class="form-control form-control-sm me-2" value="<?= $e['quantidade'] ?>">
                            <button type="submit" class="btn btn-sm btn-primary">Atualizar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#tabela-estoque').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
                }
            });
        });
    </script>
</body>
</html>
