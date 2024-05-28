<?php
include 'conexao.php'; // Inclui o arquivo de conexão com o banco de dados

// Obter lista de veículos para o dropdown
$sql = "SELECT id, placa FROM veiculos";
$result = $conn->query($sql);
$veiculos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Mensalistas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Cadastro de Mensalistas</h1>

        <div id="message-container"></div>

        <form id="cadastro-form" action="salvar_mensalista.php" method="post">
            <label for="veiculo_id">Veículo:</label>
            <select id="veiculo_id" name="veiculo_id" required>
                <option value="">Selecione um veículo</option>
                <?php foreach ($veiculos as $veiculo): ?>
                    <option value="<?php echo $veiculo['id']; ?>"><?php echo $veiculo['placa']; ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required><br><br>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone"><br><br>

            <label for="valor_mensal">Valor Mensal:</label>
            <input type="number" id="valor_mensal" name="valor_mensal" required><br><br>

            <label for="data_inicio">Data de Início:</label>
            <input type="date" id="data_inicio" name="data_inicio" required><br><br>

            <label for="data_fim">Data de Fim:</label>
            <input type="date" id="data_fim" name="data_fim"><br><br>

            <input type="submit" value="Cadastrar">
        </form>

        <br>
        <a href="index.php">Voltar para a página inicial</a>
    </div>

    <script src="script_mensalista.js"></script>
</body>
</html>
