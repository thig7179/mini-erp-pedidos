<?php
session_start();
require '../models/ProdutoModel.php';
require '../models/CupomModel.php';

$cupons_disponiveis = CupomModel::listar();
$produtos = ProdutoModel::listar();

$total_carrinho = 0;
$itens = $_SESSION['carrinho'] ?? [];
foreach ($itens as $item) {
    $total_carrinho += $item['preco'] * $item['quantidade'] + $item['frete'];
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Cadastro de Produto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Produtos</h2>
        <div>
            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#modalCadastrar">
                <i class="fas fa-plus"></i> Cadastrar Produto
            </button>
            <a href="cupons.php" class="btn btn-success me-2">
                <i class="fas fa-ticket-alt"></i> Cadastrar Cupom
            </a>
            <a href="atualizar_estoque.php" class="btn btn-secondary me-2">
                <i class="fas fa-boxes"></i> Gerenciar Estoque
            </a>
            <a href="carrinho.php" class="btn btn-outline-primary">
                <i class="fas fa-shopping-cart"></i> Carrinho (<?= count($itens) ?>)
            </a>
        </div>
    </div>

    <div class="mt-5">
        <h4>Lista de Produtos Cadastrados</h4>
        <table id="tabela-produtos" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= $p['nome'] ?></td>
                        <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                        <td>
                            <button class="btn btn-sm btn-success" onclick="abrirModalCompra(<?= $p['id'] ?>, '<?= $p['nome'] ?>', <?= $p['preco'] ?>)">Comprar</button>
                            <button class="btn btn-sm btn-warning" onclick="abrirModalEdicao(<?= $p['id'] ?>, '<?= $p['nome'] ?>', <?= $p['preco'] ?>)">Editar</button>
                            <form method="POST" action="../controllers/ProdutoController.php" class="d-inline">
                                <input type="hidden" name="action" value="excluir">
                                <input type="hidden" name="produto_id" value="<?= $p['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir este produto?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Cadastrar Produto -->
    <div class="modal fade" id="modalCadastrar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="../controllers/ProdutoController.php" method="POST">
                    <input type="hidden" name="action" value="cadastrar">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Produto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nome</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Preço</label>
                            <input type="text" id="preco" name="preco" class="form-control" required>
                        </div>
                        <div id="variacoes-container" class="mb-3">
                            <label>Variações e Estoques</label>
                            <div class="d-flex mb-1">
                                <input type="text" name="variacoes[]" placeholder="Tamanho/Cor" class="form-control me-2">
                                <input type="number" name="estoques[]" placeholder="Qtd" class="form-control">
                            </div>
                        </div>
                        <button type="button" onclick="addVariacao()" class="btn btn-secondary btn-sm">Adicionar Variação</button>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Salvar Produto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Comprar Produto -->
    <div class="modal fade" id="modalCompra" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="../controllers/ProdutoController.php" method="POST">
                    <input type="hidden" name="action" value="comprar">
                    <input type="hidden" name="produto_id" id="compraProdutoId">
                    <input type="hidden" name="preco" id="compraProdutoPreco">
                    <div class="modal-header">
                        <h5 class="modal-title">Comprar Produto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nome</label>
                            <input type="text" id="compraProdutoNome" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label>Quantidade</label>
                            <input type="number" name="quantidade" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>CEP</label>
                            <input type="text" name="cep" id="cep-modal" class="form-control" required>
                        </div>
                        <div id="cep-modal-info" class="text-muted small"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Confirmar Compra</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cepInput = document.getElementById('cep-modal');
            if (cepInput) {
                cepInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 5) value = value.slice(0, 5) + '-' + value.slice(5, 8);
                    e.target.value = value;
                });

                cepInput.addEventListener('blur', function() {
                    const cep = cepInput.value.replace(/\D/g, '');
                    if (cep.length === 8) {
                        fetch(`https://viacep.com.br/ws/${cep}/json/`)
                            .then(res => res.json())
                            .then(data => {
                                const infoDiv = document.getElementById("cep-modal-info");
                                if (data.erro) {
                                    infoDiv.innerHTML = '<span class="text-danger">CEP não encontrado.</span>';
                                } else {
                                    infoDiv.innerHTML = `<b>Endereço:</b> ${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`;
                                }
                            });
                    }
                });
            }
        });
    </script>

    <!-- Modal Editar Produto -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="../controllers/ProdutoController.php" method="POST">
                    <input type="hidden" name="action" value="atualizar">
                    <input type="hidden" name="produto_id" id="editarProdutoId">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Produto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nome</label>
                            <input type="text" name="nome" id="editarProdutoNome" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Preço</label>
                            <input type="text" name="preco" id="editarProdutoPreco" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabela-produtos').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
                }
            });
        });

        function addVariacao() {
            const container = document.getElementById('variacoes-container');
            const div = document.createElement('div');
            div.className = 'd-flex mb-1';
            div.innerHTML = `
                <input type="text" name="variacoes[]" placeholder="Tamanho/Cor" class="form-control me-2">
                <input type="number" name="estoques[]" placeholder="Qtd" class="form-control">
            `;
            container.appendChild(div);
        }

        function abrirModalCompra(id, nome, preco) {
            document.getElementById('compraProdutoId').value = id;
            document.getElementById('compraProdutoNome').value = nome;
            document.getElementById('compraProdutoPreco').value = preco;
            new bootstrap.Modal(document.getElementById('modalCompra')).show();
        }

        function abrirModalEdicao(id, nome, preco) {
            document.getElementById('editarProdutoId').value = id;
            document.getElementById('editarProdutoNome').value = nome;
            document.getElementById('editarProdutoPreco').value = preco.toFixed(2);
            new bootstrap.Modal(document.getElementById('modalEditar')).show();
        }
    </script>
    <script src="../js/mascaras.js"></script>
    <script src="../js/cep.js"></script>
</body>

</html>