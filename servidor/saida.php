<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $placa = $_POST['placa_saida'];

    // Obter dados do veículo
    $sql = "SELECT * FROM veiculos WHERE placa = '$placa' AND data_saida IS NULL";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id'];
        $data_entrada = $row['data_entrada'];
        $mensalista = $row['mensalista'];

        // Registrar saída e calcular valor (se não mensalista)
        $data_saida = date('Y-m-d H:i:s');
        $valor = 0; // Lógica de cálculo de valor aqui

        $sql = "UPDATE veiculos SET data_saida = '$data_saida', valor = $valor WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo "Saída registrada com sucesso! Valor a pagar: R$ $valor";
        } else {
            echo "Erro: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Veículo não encontrado no estacionamento!";
    }
}

$conn->close();
?>
