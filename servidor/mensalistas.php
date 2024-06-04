<?php
include 'conexao.php';

// Processar ações (adicionar, editar, excluir)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'add' || $action == 'edit') {
            $placa = $_POST['placa'];
            $nome = $_POST['nome'];
            $telefone = $_POST['telefone'];
            $valor_mensal = $_POST['valor_mensal'];
            $data_inicio = $_POST['data_inicio'];

            $errors = [];

            if (empty($placa) || !preg_match("/^[A-Za-z0-9]{7}$/", $placa)) {
                $errors[] = "Formato de placa inválido. Use 7 caracteres alfanuméricos.";
            }
            if (empty($nome)) {
                $errors[] = "O nome é obrigatório.";
            }
            if (empty($valor_mensal) || !is_numeric($valor_mensal) || $valor_mensal <= 0) {
                $errors[] = "Valor mensal inválido.";
            }
            if (empty($data_inicio)) {
                $errors[] = "A data de início é obrigatória.";
            }

            if (empty($errors)) {
                // Verifica se o veículo existe no banco de dados
                $sql = "SELECT id FROM veiculos WHERE placa = '$placa'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $veiculo_id = $result->fetch_assoc()['id'];
                } else {
                    // Se o veículo não existe, insere um novo registro
                    $sql = "INSERT INTO veiculos (placa) VALUES ('$placa')";
                    if ($conn->query($sql) === TRUE) {
                        $veiculo_id = $conn->insert_id;
                    } else {
                        $errors[] = "Erro ao inserir veículo: " . $conn->error;
                    }
                }

                // Insere ou atualiza o mensalista (se não houver erros na inserção do veículo)
                if (empty($errors)) {
                    if ($action == 'add') {
                        $sql = "INSERT INTO mensalistas (veiculo_id, nome, telefone, valor_mensal, data_inicio) 
                                VALUES ('$veiculo_id', '$nome', '$telefone', '$valor_mensal', '$data_inicio')";
                    } else {
                        $id = $_POST['id'];
                        $sql = "UPDATE mensalistas SET veiculo_id = '$veiculo_id', nome = '$nome', telefone = '$telefone', 
                                valor_mensal = '$valor_mensal', data_inicio = '$data_inicio'
                                WHERE id = $id";
                    }

                    if ($conn->query($sql) === TRUE) {
                        // Mensagem de sucesso (sem redirecionamento)
                        echo "<p class='success-message' style='color: green;'>Operação realizada com sucesso!</p>";
                    } else {
                        echo "<p class='error-message' style='color: red;'>Erro: " . $sql . "<br>" . $conn->error . "</p>";
                    }
                }
            } else {
                foreach ($errors as $error) {
                    echo "<p class='error-message' style='color: red;'>$error</p>";
                }
            }
        } elseif ($action == 'delete') {
            $id = $_POST['id'];
            $sql = "DELETE FROM mensalistas WHERE id = $id";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='success-message' style='color: green;'>Mensalista excluído com sucesso!</p>";
            } else {
                echo "<p class='error-message' style='color: red;'>Erro ao excluir mensalista: " . $conn->error . "</p>";
            }
        }
    }
}

// Obter dados do mensalista para edição (se houver)
$mensalista = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT m.*, v.placa FROM mensalistas m INNER JOIN veiculos v ON m.veiculo_id = v.id WHERE m.id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $mensalista = $result->fetch_assoc();
    }
}

// Obter mensalidades próximas do vencimento (próximos 7 dias)
$sql = "SELECT m.*, v.placa, DATE_FORMAT(DATE_ADD(m.data_inicio, INTERVAL 1 MONTH), '%d-%m-%Y') AS proxima_cobranca
        FROM mensalistas m 
        INNER JOIN veiculos v ON m.veiculo_id = v.id 
        WHERE DATEDIFF(DATE_ADD(m.data_inicio, INTERVAL 1 MONTH), CURDATE()) BETWEEN 0 AND 7";
$result = $conn->query($sql);
$mensalidadesVencendo = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gerenciar Mensalistas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Gerenciar Mensalistas</h1>

        <?php if (isset($_GET['success'])): ?>
            <p class="success-message" style="color: green;"><?php echo $_GET['success']; ?></p>
        <?php endif; ?>

        <h2>Mensalidades Próximas do Vencimento:</h2>
        <?php if (!empty($mensalidadesVencendo)): ?>
            <ul>
                <?php foreach ($mensalidadesVencendo as $mensalidade): ?>
                    <li><?php echo $mensalidade['placa'] . " - " . $mensalidade['nome'] . " (Próxima cobrança: " . $mensalidade['proxima_cobranca'] . ")"; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhuma mensalidade próxima do vencimento.</p>
        <?php endif; ?>

        <h2><?php echo $mensalista ? 'Editar Mensalista' : 'Adicionar Mensalista'; ?></h2>
        <form action="mensalistas.php" method="post">
            <input type="hidden" name="action" value="<?php echo $mensalista ? 'edit' : 'add'; ?>">
            <?php if ($mensalista): ?>
                <input type="hidden" name="id" value="<?php echo $mensalista['id']; ?>">
            <?php endif; ?>

            <label for="placa">Placa do Veículo:</label>
            <input type="text" id="placa" name="placa" value="<?php echo $mensalista ? $mensalista['placa'] : ''; ?>" required><br><br>

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo $mensalista ? $mensalista['nome'] : ''; ?>" required><br><br>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?php echo $mensalista ? $mensalista['telefone'] : ''; ?>"><br><br>

            <label for="valor_mensal">Valor Mensal:</label>
            <input type="number" id="valor_mensal" name="valor_mensal" value="<?php echo $mensalista ? $mensalista['valor_mensal'] : ''; ?>" required><br><br>

            <label for="data_inicio">Data de Início:</label>
            <input type="date" id="data_inicio" name="data_inicio" value="<?php echo $mensalista ? $mensalista['data_inicio'] : ''; ?>" required><br><br>

            <input type="submit" value="<?php echo $mensalista ? 'Salvar' : 'Adicionar'; ?>">
        </form>

        <h2>Lista de Mensalistas</h2>
        <table border="1">
            <tr>
                <th>Veículo</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Valor Mensal</th>
                <th>Data de Início</th>
                <th>Próxima Cobrança</th>
                <th>Ações</th>
            </tr>
            <?php
            $sql = "SELECT m.*, v.placa, DATE_FORMAT(DATE_ADD(m.data_inicio, INTERVAL 1 MONTH), '%d-%m-%Y') AS proxima_cobranca 
                    FROM mensalistas m 
                    INNER JOIN veiculos v ON m.veiculo_id = v.id";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['placa']; ?></td>
                    <td><?php echo $row['nome']; ?></td>
                    <td><?php echo $row['telefone']; ?></td>
                    <td>R$ <?php echo number_format($row['valor_mensal'], 2); ?></td>
                    <td><?php echo $row['data_inicio']; ?></td>
                    <td><?php echo $row['proxima_cobranca']; ?></td>
                    <td>
                        <a href="?edit=<?php echo $row['id']; ?>">Editar</a> |
                        <form action="mensalistas.php" method="post" style="display: inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <input type="submit" value="Excluir" onclick="return confirm('Tem certeza que deseja excluir este mensalista?');">
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <br>
        <a href="index.php">Voltar para a página inicial</a>
    </div>

    <script src="script_mensalista.js"></script>
</body>
</html>
