<?php
include 'db_conexao.php'; // Incluir o arquivo de conexão ao banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = $_POST['placa'];
    $tipo = $_POST['tipo'];

    if (empty($placa) || empty($tipo)) {
        echo json_encode(["success" => false, "message" => "Todos os campos são obrigatórios."]);
        exit;
    }

    // Verificar se há vagas disponíveis do tipo especificado
    $query = "SELECT id FROM vagas WHERE tipo = ? AND disponivel = 1 LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $tipo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $vaga = $result->fetch_assoc();
        $vaga_id = $vaga['id'];

        // Registrar a entrada do veículo e atualizar a vaga como ocupada
        $conn->begin_transaction();
        try {
            $query = "INSERT INTO veiculos (placa, tipo, vaga_id) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssi", $placa, $tipo, $vaga_id);
            $stmt->execute();

            $query = "UPDATE vagas SET disponivel = 0 WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $vaga_id);
            $stmt->execute();

            $conn->commit();

            echo json_encode(["success" => true, "message" => "Entrada registrada com sucesso!"]);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(["success" => false, "message" => "Erro ao registrar entrada: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Não há vagas disponíveis para " . ($tipo == 'moto' ? 'motos' : 'carros') . "."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Método não suportado."]);
}
?>
