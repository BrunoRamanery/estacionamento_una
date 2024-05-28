<?php
include 'conexao.php';

// Consulta para obter informações sobre as vagas
$sql = "SELECT capacidade_vagas, 
               (SELECT COUNT(*) FROM veiculos WHERE data_saida IS NULL) AS vagas_ocupadas
        FROM configuracoes";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$vagas_disponiveis = $row['capacidade_vagas'] - $row['vagas_ocupadas'];

echo $vagas_disponiveis;
?>
