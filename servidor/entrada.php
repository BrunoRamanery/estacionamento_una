<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validação dos dados do formulário (sem alterações)
    $placa = trim($_POST['placa']);
    $tipo = $_POST['tipo'];

    $errors = [];

    if (empty($placa) || !preg_match("/^[A-Za-z0-9]{7}$/", $placa)) {
        $errors[] = "Formato de placa inválido. Use 7 caracteres alfanuméricos.";
    }

    if (!in_array($tipo, ['Carro', 'Moto', 'Caminhão'])) {
        $errors[] = "Tipo de veículo inválido.";
    }

    // Verifica se o veículo está atualmente no estacionamento
    if (empty($errors)) {
        $sql = "SELECT * FROM veiculos WHERE placa = ? AND data_saida IS NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $placa);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "Veículo já está no estacionamento!";
        }
    }

    // Insere um novo registro para o veículo (mesmo que já tenha entrado antes)
    if (empty($errors)) {
        $sql = "INSERT INTO veiculos (placa, tipo, data_entrada) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $placa, $tipo);
        if ($stmt->execute()) {
            echo "<p class='success-message' style='color: green;'>Entrada registrada com sucesso!</p>";
        } else {
            $errors[] = "Erro ao registrar a entrada: " . $stmt->error;
        }
    }

    // Exibe as mensagens de erro ou sucesso
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p class='error-message' style='color: red;'>$error</p>";
        }
    }
}

$conn->close();
?>
