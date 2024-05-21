<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Estacionamento</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
	<div class="logoinicio"><img src ="imagens\logo.fw.png"></div>
        <h1>LOGIN</h1>
        <form id="loginForm">
            <input type="text" id="username" placeholder="Usuário" required><br>
            <input type="password" id="password" placeholder="Senha" required><br>
            <button type="submit">Entrar</button>
        </form>
        <p id="loginError" style="color: red; display: none;">Credenciais inválidas. Tente novamente.</p>
    </div>
    <script src="script.js"></script>
</body>
</html>