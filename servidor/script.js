document.addEventListener("DOMContentLoaded", function () {
    const placaInput = document.getElementById("placa");
    const motoBtn = document.getElementById("moto-btn");
    const carroBtn = document.getElementById("carro-btn");
    const mensagemContainer = document.getElementById("mensagem-container");
    const vagasContainer = document.getElementById("vagas-container");

    // Função para registrar entrada
    function registrarEntrada(tipoVeiculo) {
        const placa = placaInput.value;

        fetch("registrar_entrada.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `placa=${encodeURIComponent(placa)}&tipo=${encodeURIComponent(tipoVeiculo)}`,
        })
            .then((response) => response.json())
            .then((data) => {
                mensagemContainer.textContent = data.message;
                carregarVagas();
            })
            .catch((error) => {
                console.error("Erro ao registrar entrada:", error);
            });
    }

    // Carregar vagas
    function carregarVagas() {
        fetch("consultar_vagas.php")
            .then((response) => response.json())
            .then((data) => {
                vagasContainer.innerHTML = ''; // Limpar vagas anteriores
                data.forEach(vaga => {
                    const vagaDiv = document.createElement("div");
                    vagaDiv.classList.add("vaga");
                    vagaDiv.classList.add(vaga.disponivel ? "disponivel" : "ocupada");
                    vagaDiv.textContent = vaga.id;
                    vagasContainer.appendChild(vagaDiv);
                });
            })
            .catch((error) => {
                console.error("Erro ao consultar as vagas:", error);
            });
    }

    // Adicionar eventos aos botões
    motoBtn.addEventListener("click", function () {
        registrarEntrada("moto");
    });

    carroBtn.addEventListener("click", function () {
        registrarEntrada("carro");
   
