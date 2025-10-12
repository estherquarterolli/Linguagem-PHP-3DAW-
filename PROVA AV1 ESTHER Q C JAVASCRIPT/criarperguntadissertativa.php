<?php

session_start();



$msg = "";
$success = false;
$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';


if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['ID'])){

    $nome_arquivo = "perguntas.txt";
    // Usa $_GET para ler os dados enviados pelo JavaScript
    $pergunta = $_GET["pergunta"];
    $ID = $_GET["ID"];
    $alt_correta_id = $_GET["alternativaCorreta"];

    // Para perguntas dissertativas, as alternativas A-E ficam vazias
    $altA = "";
    $altB = "";
    $altC = "";
    $altD = "";
    $altE = "";

    if (empty($pergunta) || empty($ID) || empty($alt_correta_id)) {
        $msg = "Por favor, preencha todos os campos.";
        $success = false;
    } else {
        $file = fopen($nome_arquivo, 'a') or die("Não foi possível abrir/criar arquivo");

        $linha = $ID . ";" . $pergunta . ";" . $altA . ";" . $altB . ";" . $altC . ";" . $altD . ";" . $altE . ";". $alt_correta_id . "\n";

        if (fwrite($file, $linha) !== false) {
             $msg = "Pergunta Dissertativa Cadastrada (ID: $ID)";
             $success = true;
        } else {
             $msg = "Erro ao salvar a pergunta no arquivo.";
             $success = false;
        }
        fclose($file);
    }
    
    // Resposta para a chamada AJAX
    if ($is_ajax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => $success, 'msg' => $msg]);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Pergunta Dissertativa</title>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>
<body>

<div class="container"> 
    <h1>Cadastrar Pergunta Dissertativa</h1>
    
    <div id="status-message">
        <?php if (isset($msg) && !$is_ajax): ?>
            <div class="alert <?php echo $success ? 'success' : 'error'; ?> text-center"><?php echo $msg; ?></div>
        <?php endif; ?>
    </div>
    
    <form id="create-dissertativa-form">
        
        <div>
            <label for="pergunta">PERGUNTA:</label> 
            <input type="text" id="pergunta" name="pergunta" required>
        </div>

        <div>
            <label for="id_pergunta">ID:</label>
            <input type="text" id="id_pergunta" name="ID" required>
        </div>

        <div>
            <label for="alt_correta">Alternativa correta:</label> 
            <textarea id="alt_correta" name="alternativaCorreta" required>Insira aqui sua resposta</textarea>
        </div>

        <input type="submit" value="Enviar" id="submit-button">
    </form>
    
    <div class="menu"> 
        <a href="menucriarpergunta.php">Voltar</a>
        <a href="menu.php">Menu Principal</a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('create-dissertativa-form');
        const statusMessage = document.getElementById('status-message');
        const submitButton = document.getElementById('submit-button');
        const textarea = form.querySelector('textarea[name="alternativaCorreta"]');


        textarea.addEventListener('focus', function() {
            if (this.value === 'Insira aqui sua resposta') {
                this.value = '';
            }
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Impede o envio do formulário padrão
            
            // Limpa e exibe mensagem de processamento
            // Usando a classe 'alert info' para processamento, alinhado ao CSS existente
            statusMessage.innerHTML = '<div class="alert info text-center">Processando...</div>';
            submitButton.value = 'Salvando...';
            submitButton.disabled = true;

            const formData = new FormData(form);
            
            // Converte os dados do formulário em uma query string
            const params = new URLSearchParams(formData).toString();
            
            // Adiciona um cabeçalho para o PHP identificar a chamada AJAX
            const headers = new Headers();
            headers.append('X-Requested-With', 'XMLHttpRequest');

            // Faz a requisição usando o método GET
            fetch(`criarperguntadissertativa.php?${params}`, {
                method: 'GET',
                headers: headers
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const alertClass = data.success ? 'success' : 'error';
                statusMessage.innerHTML = `<div class="alert ${alertClass} text-center">${data.msg}</div>`;
                
                if (data.success) {
                    // Limpa os campos para o próximo cadastro
                    form.reset();
                    // Restaura o valor padrão no textarea, se for o caso
                    textarea.value = 'Insira aqui sua resposta'; 
                    // Foca no primeiro campo para agilizar
                    form.querySelector('input[name="pergunta"]').focus(); 
                }
            })
            .catch(error => {
                console.error('Erro de rede:', error);
                statusMessage.innerHTML = '<div class="alert error text-center">Erro de comunicação com o servidor.</div>';
            })
            .finally(() => {
                submitButton.value = 'Enviar';
                submitButton.disabled = false;
            });
        });
    });
</script>
</body>
</html>