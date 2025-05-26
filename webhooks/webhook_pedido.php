<?php

require '../config/db.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id']) || !isset($input['status'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados insuficientes. Enviar id e status.']);
    exit;
}

$id = intval($input['id']);
$status = strtoupper(trim($input['status']));

if ($status === 'CANCELADO') {
    $stmt = $mysqli->prepare("DELETE FROM pedidos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Pedido cancelado e removido com sucesso.']);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Pedido não encontrado para cancelamento.']);
    }
} else {
    $stmt = $mysqli->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Status do pedido atualizado com sucesso.']);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Pedido não encontrado para atualização.']);
    }
}
?>
