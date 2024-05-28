const form = document.getElementById('cadastro-form');
const messageContainer = document.getElementById('message-container');

form.addEventListener('submit', (event) => {
    event.preventDefault();

    const formData = new FormData(form);

    fetch('salvar_mensalista.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        messageContainer.innerHTML = data;

        // Limpa a mensagem após 5 segundos (opcional)
        setTimeout(() => {
            messageContainer.innerHTML = '';
        }, 5000);
    });
});
