<?php
include 'db_conexao.php'; // Incluir o arquivo de conexão ao banco de dados

// Consultar todas as vagas no banco de dados
$query = "SELECT id, tipo, disponivel FROM vagas";
$resultado = mysqli_query($conn, $query);

if ($resultado) {
    $vagas = [];
    while ($row = mysqli_fetch_assoc($resultado)) {
        $vagas[] = $row;
    }
    echo json_encode($vagas);
} else {
    echo "Erro ao consultar as vagas: " . mysqli_error($conn);
    die();
}
?>
