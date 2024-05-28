const form = document.getElementById('entrada-form');
const messageContainer = document.getElementById('message-container');
const vagasCarros = document.querySelectorAll('.vaga-carro');
const vagasMotos = document.querySelectorAll('.vaga-moto');

function atualizarVagas() {
    fetch('obter_vagas_disponiveis.php')
        .then(response => response.json())
        .then(vagas => {
            vagasCarros.forEach((vaga, index) => {
                vaga.classList.toggle('ocupada', index >= vagas.vagas_carros_disponiveis);
            });

            vagasMotos.forEach((vaga, index) => {
                vaga.classList.toggle('ocupada', index >= vagas.vagas_motos_disponiveis);
            });
        });
}

function atualizarTabelaVeiculos() {
    fetch('obter_veiculos_estacionados.php')
        .then(response => response.json())
        .then(veiculos => {
            const tabela = document.getElementById('tabela-veiculos');
            tabela.innerHTML = ''; // Limpa a tabela

            // Adiciona o cabeçalho da tabela
            const headerRow = tabela.insertRow();
            headerRow.insertCell().textContent = 'Placa';
            headerRow.insertCell().textContent = 'Tipo';
            headerRow.insertCell().textContent = 'Entrada (Data/Hora)'; // Cabeçalho modificado
            headerRow.insertCell().textContent = 'Ações';

            // Adiciona as linhas com os dados dos veículos e botões
            veiculos.forEach(veiculo => {
                const row = tabela.insertRow();
                row.insertCell().textContent = veiculo.placa;
                row.insertCell().textContent = veiculo.tipo;
                row.insertCell().textContent = veiculo.data_entrada; // Exibe data e hora

                const botaoSaida = document.createElement('button');
                botaoSaida.textContent = 'Registrar Saída';
                botaoSaida.addEventListener('click', () => {
                    registrarSaida(veiculo.placa);
                });
                row.insertCell().appendChild(botaoSaida);
            });
        });
}


function registrarSaida(placa) {
    fetch('saida_veiculo.php', {
        method: 'POST',
        body: new URLSearchParams('placa=' + placa)
    })
    .then(response => response.text())
    .then(data => {
        // Exibe a mensagem retornada pelo PHP (agora com o valor a pagar)
        messageContainer.innerHTML = data; 
        atualizarVagas();
        atualizarTabelaVeiculos();

        setTimeout(() => {
            messageContainer.innerHTML = '';
        }, 5000);
    });
}

form.addEventListener('submit', (event) => {
    event.preventDefault();

    const formData = new FormData(form);

    fetch('entrada.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        messageContainer.innerHTML = data;
        atualizarVagas();

        setTimeout(() => {
            messageContainer.innerHTML = '';
        }, 5000);
    });
});

// Atualiza as vagas e a tabela a cada 5 segundos
setInterval(() => {
    atualizarVagas();
    atualizarTabelaVeiculos();
}, 5000);

// Chama as funções uma vez no início para carregar os dados iniciais
atualizarVagas();
atualizarTabelaVeiculos();
