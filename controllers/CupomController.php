<?php 

require '../models/CupomModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'];
    $desconto = $_POST['desconto'];
    $valor_minimo = $_POST['valor_minimo'];
    $validade = $_POST['validade'];
    CupomModel::criar($codigo, $desconto, $valor_minimo, $validade);
    header('Location: ../views/cupons.php');
    exit;
}
?>
