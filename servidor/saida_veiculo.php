<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $placa = $_POST['placa'];

    // Obter dados do veículo (incluindo data_entrada)
    $sql = "SELECT * FROM veiculos WHERE placa = ? AND data_saida IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $placa);
    $stmt->execute();
    $resultVeiculo = $stmt->get_result();

    if ($resultVeiculo->num_rows > 0) {
        $row = $resultVeiculo->fetch_assoc();
        $id = $row['id'];
        $data_entrada = $row['data_entrada'];
        $mensalista = $row['mensalista'];

        // Obter valor por hora da tabela de configurações (com verificação de erros)
        $sql = "SELECT valor_hora FROM configuracoes";
        $resultConfig = $conn->query($sql);

        if ($resultConfig->num_rows > 0) {
            $row = $resultConfig->fetch_assoc();
            $valor_hora = (float) $row['valor_hora'];
        } else {
            echo "<p class='error-message' style='color: red;'>Erro: Valor por hora não encontrado.</p>";
            $conn->close();
            exit(); // Encerra o script para evitar erros posteriores
        }

        // Registrar saída e calcular valor
        $data_saida = date('Y-m-d H:i:s');
        $valor = 0;

        if (!$mensalista) {
            // Calcula a diferença em minutos usando TIMESTAMPDIFF
            $sql = "SELECT TIMESTAMPDIFF(MINUTE, data_entrada, '$data_saida') AS minutos_estacionado FROM veiculos WHERE id = $id";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $minutos_estacionado = $row['minutos_estacionado'];

            // Calcula as horas estacionado, com minutos excedentes convertidos em fração de hora
            $horas_estacionado = floor($minutos_estacionado / 60) + ($minutos_estacionado % 60) / 60;

           
        }

        $sql = "UPDATE veiculos SET data_saida = ?, valor = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdi", $data_saida, $valor, $id);

        if ($stmt->execute()) {
            echo "<p class='success-message' style='color: green;'>Saída registrada com sucesso! Valor a pagar: R$ " . number_format($valor, 2) . "</p>";
        } else {
            echo "<p class='error-message' style='color: red;'>Erro: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p class='error-message' style='color: red;'>Veículo não encontrado no estacionamento!</p>";
    }
}

$conn->close();
?>
