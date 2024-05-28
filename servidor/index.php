<?php
include 'conexao.php';

// Consulta para obter informações sobre as vagas
$sql = "SELECT capacidade_carros, capacidade_motos,
               (SELECT COUNT(*) FROM veiculos WHERE data_saida IS NULL AND tipo = 'Carro') AS carros_ocupados,
               (SELECT COUNT(*) FROM veiculos WHERE data_saida IS NULL AND tipo = 'Moto') AS motos_ocupadas
        FROM configuracoes";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$vagas_carros_disponiveis = $row['capacidade_carros'] - $row['carros_ocupados'];
$vagas_motos_disponiveis = $row['capacidade_motos'] - $row['motos_ocupadas'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Estacionamento</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>
    
    <div class="container">
        <h1>Estacionamento</h1>

        <h2>Veículos Estacionados:</h2>
        <table id="tabela-veiculos"></table>

        <h2>Vagas Disponíveis:</h2>
        <div id="vagas-carros">
            <?php
            for ($i = 1; $i <= $row['capacidade_carros']; $i++) {
                echo "<div class='vaga vaga-carro'></div>";
            }
            ?>
        </div>

        <div id="vagas-motos">
            <?php
            for ($i = 1; $i <= $row['capacidade_motos']; $i++) {
                echo "<div class='vaga vaga-moto'></div>";
            }
            ?>
        </div>

        <div id="message-container"></div>

        <h2>Entrada/Saída</h2>
        <form id="entrada-form" action="entrada.php" method="post">
            <label for="placa">Placa:</label>
            <input type="text" id="placa" name="placa" required><br><br>

            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo">
                <option value="Carro">Carro</option>
                <option value="Moto">Moto</option>
                <option value="Caminhão">Caminhão</option>
            </select><br><br>

            <input type="submit" value="Registrar Entrada">
        </form>

        <form action="saida.php" method="post">
            <label for="placa_saida">Placa:</label>
            <input type="text" id="placa_saida" name="placa_saida" required><br><br>
            <input type="submit" value="Registrar Saída">
        </form>

        <h2>Mensalistas</h2>
        <a href="mensalistas.php">Gerenciar Mensalistas</a>
        
        <button onclick="window.location.href='configuracoes.php';">Configurações</button>

    </div>
    <script src="script.js"></script>
</body>
</html>
