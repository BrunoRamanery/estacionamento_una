<?php
include 'db_conexao.php'; // Incluir o arquivo de conexão ao banco de dados

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['username'];
    $senha = $_POST['password'];

    // Consulta para verificar as credenciais do usuário
    $query = "SELECT * FROM usuarios WHERE usuario='$usuario' AND senha='$senha'";
    $resultado = mysqli_query($conn, $query);

    // Verificar se o usuário foi encontrado
    if (mysqli_num_rows($resultado) == 1) {
        $row = mysqli_fetch_assoc($resultado);
        $tipo_usuario = $row['tipo']; // Obter o tipo de usuário do banco de dados

        // Enviar resposta em formato JSON com sucesso e tipo de usuário
        echo json_encode(["success" => true, "tipo_usuario" => $tipo_usuario]);
    } else {
        echo json_encode(["success" => false]); // Enviar resposta de erro em formato JSON
    }
}
?>