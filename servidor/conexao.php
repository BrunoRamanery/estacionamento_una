<?php
$servername = "localhost"; // Ou o endereço do seu servidor MySQL
$username = "root";
$password = "";
$dbname = "1";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
