<?php
include 'conexao.php';

// Consulta para obter o número de vagas disponíveis por tipo
$sql = "SELECT 
            capacidade_carros - 
            (SELECT COUNT(*) FROM veiculos WHERE data_saida IS NULL AND tipo = 'Carro') AS vagas_carros_disponiveis,
            capacidade_motos - 
            (SELECT COUNT(*) FROM veiculos WHERE data_saida IS NULL AND tipo = 'Moto') AS vagas_motos_disponiveis
        FROM configuracoes"; 

$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Retorna as informações em formato JSON
echo json_encode($row);
?>
