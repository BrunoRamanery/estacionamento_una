<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $placa = $_POST['placa'];

    // Obter dados do veículo (incluindo data_entrada)
    $sql = "SELECT * FROM veiculos WHERE placa = ? AND data_saida IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $placa);
    $stmt->execute();
    $resultVeiculo = $stmt->get_result(); // Renomeado para evitar conflito

    if ($resultVeiculo->num_rows > 0) {
        $row = $resultVeiculo->fetch_assoc();
        $id = $row['id'];
        $data_entrada = $row['data_entrada'];
        $mensalista = $row['mensalista'];

        // Obter valor por hora da tabela de configurações
        $sql = "SELECT valor_hora FROM configuracoes";
        $resultConfig = $conn->query($sql); // Nova variável para o resultado da consulta
        $row = $resultConfig->fetch_assoc();
        $valor_hora = (float) $row['valor_hora'];

        // Registrar saída e calcular valor
        $data_saida = date('Y-m-d H:i:s');
        $valor = 0;

        if (!$mensalista) {
            // Calcula a diferença em horas usando TIMESTAMPDIFF diretamente no SQL
            $sql = "UPDATE veiculos 
                    SET data_saida = ?, 
                        valor = TIMESTAMPDIFF(HOUR, data_entrada, ?) * ? 
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdii", $data_saida, $data_saida, $valor_hora, $id); // Passa a data de saída duas vezes e o valor da hora

            if ($stmt->execute()) {
                // Recalcula o valor após a atualização
                $sql = "SELECT valor FROM veiculos WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $resultValor = $stmt->get_result();
                $row = $resultValor->fetch_assoc();
                $valor = $row['valor'];

                echo "<p class='success-message' style='color: green;'>Saída registrada com sucesso! Valor a pagar: 32,00 R$ ";
            } else {
                echo "<p class='error-message' style='color: red;'>Erro: " . $stmt->error . "</p>";
            }
        } else {
            // Se for mensalista, atualiza apenas a data de saída
            $sql = "UPDATE veiculos SET data_saida = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $data_saida, $id);

            if ($stmt->execute()) {
                echo "<p class='success-message' style='color: green;'>Saída registrada com sucesso! (Mensalista)</p>";
            } else {
                echo "<p class='error-message' style='color: red;'>Erro: " . $stmt->error . "</p>";
            }
        }
    } else {
        echo "<p class='error-message' style='color: red;'>Veículo não encontrado no estacionamento!</p>";
    }
}

$conn->close();
?>
