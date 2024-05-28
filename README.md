# Sistema de Estacionamento - Versão do Estudante

Este é um sistema simples para gerenciar um estacionamento, feito para aprender PHP e banco de dados. Ele faz o básico:

*   **Entrada e Saída de Carros:** Você pode registrar quando um carro entra e sai do estacionamento.
*   **Mensalistas:** Tem uma parte para cadastrar quem paga mensalidade e avisa quando o pagamento está acabando.
*   **Vagas:** Mostra quantas vagas ainda tem para carros e motos, e também mostra quais carros estão parados no momento.
*   **Configurações:** Dá para mudar o preço por hora e quantas vagas tem no total.

## Como usar:

1.  **Banco de Dados:** Crie um banco de dados chamado "estacionamento" no seu MySQL.
2.  **Tabelas:** Use as queries SQL que estão no arquivo "criar_tabelas.sql" para criar as tabelas no banco.
3.  **Configurações:** Mude as informações de conexão com o banco de dados no arquivo "conexao.php" (usuário, senha, etc.).
4.  **Rodar o Sistema:** Abra o arquivo "index.php" no seu navegador.

## Arquivos:

*   **`index.php`:** A página principal, onde você vê tudo.
*   **`entrada.php`:** Para registrar a entrada de um carro.
*   **`saida.php`:** Para registrar a saída de um carro e calcular o preço.
*   **`mensalistas.php`:** Para mexer nos cadastros dos mensalistas.
*   **`configuracoes.php`:** Para mudar as configurações do estacionamento.
*   **`script.js`:** Um arquivo com código JavaScript para atualizar as coisas na tela automaticamente.
*   **`obter_vagas_disponiveis.php`:** Manda para o `script.js` quantas vagas ainda tem.
*   **`obter_veiculos_estacionados.php`:** Manda para o `script.js` quais carros estão no estacionamento.
*   **`obter_mensalidades_vencendo.php`:** Manda para o `script.js` quais mensalidades estão para vencer.
*   **`obter_valor_hora.php`:** Manda para o `script.js` o valor cobrado por hora.
*   **`atualizar_valor_hora.php`:** Muda o valor da hora no banco de dados.
*   **`style.css`:** Deixa o sistema mais bonitinho.

## Coisas que ainda não fiz:

*   **Login:** Não tem login ainda, qualquer um pode mexer nas configurações.
*   **Relatórios:** Não faz relatórios de quantos carros entraram, quanto dinheiro ganhou, etc.
*   **Pagamentos Online:** Não tem como pagar pelo site, só na saída do estacionamento mesmo.
*   **Cancela:** Não tem cancela automática, é tudo manual.

## Para quem quiser melhorar:

*   Adiciona um login para só quem trabalha no estacionamento poder mexer nas coisas.
*   Faz uns relatórios legais para o dono ver como estão as coisas.
*   Coloca um jeito de pagar pelo site, tipo com cartão de crédito.
*   Integra com uma cancela que abre sozinha quando o carro sai.
