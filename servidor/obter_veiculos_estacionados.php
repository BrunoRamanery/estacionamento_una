<?php
include 'conexao.php';

// Consulta para obter veículos estacionados com data e hora formatadas
$sql = "SELECT placa, tipo, DATE_FORMAT(data_entrada, '%d/%m/%y %H:%i') AS data_entrada FROM veiculos WHERE data_saida IS NULL";
$result = $conn->query($sql);

$veiculos = [];
while ($row = $result->fetch_assoc()) {
    $veiculos[] = $row;
}

// Retorna os dados em formato JSON
echo json_encode($veiculos);
?>
