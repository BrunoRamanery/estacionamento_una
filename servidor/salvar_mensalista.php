<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validação dos dados do formulário (adicione validações conforme necessário)
    $veiculo_id = $_POST['veiculo_id'];
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $valor_mensal = $_POST['valor_mensal'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];

    // Insere os dados no banco de dados
    $sql = "INSERT INTO mensalistas (veiculo_id, nome, telefone, valor_mensal, data_inicio, data_fim) 
            VALUES ('$veiculo_id', '$nome', '$telefone', '$valor_mensal', '$data_inicio', '$data_fim')";

    if ($conn->query($sql) === TRUE) {
        echo "<p class='success-message' style='color: green;'>Mensalista cadastrado com sucesso!</p>";
    } else {
        echo "<p class='error-message' style='color: red;'>Erro ao cadastrar mensalista: " . $conn->error . "</p>";
    }
}

$conn->close();
?>
