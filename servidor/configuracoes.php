<?php
include 'conexao.php';

// Obter as configurações atuais
$sql = "SELECT valor_hora, capacidade_carros, capacidade_motos FROM configuracoes";
$result = $conn->query($sql);

// Verificar se há configurações
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $valor_hora = $row['valor_hora'];
    $capacidade_carros = $row['capacidade_carros'];
    $capacidade_motos = $row['capacidade_motos'];
} else {
    // Tratar caso não existam configurações (pode ser um erro ou primeira vez)
    $valor_hora = 0;
    $capacidade_carros = 0;
    $capacidade_motos = 0;
    $errorMessage = "Configurações não encontradas. Insira os valores iniciais.";
}

// Processar o formulário de atualização
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novoValorHora = $_POST['valor_hora'];
    $novaCapacidadeCarros = $_POST['capacidade_carros'];
    $novaCapacidadeMotos = $_POST['capacidade_motos'];

    $errors = [];

    if (empty($novoValorHora) || !is_numeric($novoValorHora) || $novoValorHora <= 0) {
        $errors[] = "Valor por hora inválido.";
    }

    if (empty($novaCapacidadeCarros) || !is_numeric($novaCapacidadeCarros) || $novaCapacidadeCarros < 0) {
        $errors[] = "Capacidade de carros inválida.";
    }

    if (empty($novaCapacidadeMotos) || !is_numeric($novaCapacidadeMotos) || $novaCapacidadeMotos < 0) {
        $errors[] = "Capacidade de motos inválida.";
    }

    if (empty($errors)) {
        $sql = "UPDATE configuracoes SET valor_hora = ?, capacidade_carros = ?, capacidade_motos = ? WHERE id = 1"; // Assumindo que só há um registro na tabela
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dii", $novoValorHora, $novaCapacidadeCarros, $novaCapacidadeMotos);

        if ($stmt->execute()) {
            $successMessage = "Configurações atualizadas com sucesso!";
            // Atualiza as variáveis com os novos valores
            $valor_hora = $novoValorHora;
            $capacidade_carros = $novaCapacidadeCarros;
            $capacidade_motos = $novaCapacidadeMotos;
        } else {
            $errorMessage = "Erro ao atualizar configurações: " . $stmt->error;
        }
    } else {
        $errorMessage = implode("<br>", $errors); // Junta os erros em uma única mensagem
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Configurações</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Configurações</h1>

        <?php if (isset($successMessage)): ?>
            <p class="success-message" style="color: green;"><?php echo $successMessage; ?></p>
        <?php endif; ?>

        <?php if (isset($errorMessage)): ?>
            <p class="error-message" style="color: red;"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <form action="configuracoes.php" method="post">
            <label for="valor_hora">Valor por Hora (R$):</label>
            <input type="number" id="valor_hora" name="valor_hora" step="0.01" value="<?php echo $valor_hora; ?>" required><br><br>

            <label for="capacidade_carros">Capacidade de Carros:</label>
            <input type="number" id="capacidade_carros" name="capacidade_carros" value="<?php echo $capacidade_carros; ?>" required><br><br>

            <label for="capacidade_motos">Capacidade de Motos:</label>
            <input type="number" id="capacidade_motos" name="capacidade_motos" value="<?php echo $capacidade_motos; ?>" required><br><br>

            <input type="submit" value="Atualizar">
        </form>

        <br>
        <a href="index.php">Voltar para a página inicial</a>
    </div>
</body>
</html>
