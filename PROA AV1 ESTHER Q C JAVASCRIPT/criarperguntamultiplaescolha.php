<?php
session_start();
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado']['tipo'] != 'admin') {
    header('Location: login.php');
    exit();
}

$msg = "";
$success = false;
$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';


if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['ID'])) {
    
    // Usa $_GET para ler os dados enviados pelo JavaScript
    $pergunta = $_GET["pergunta"];
    $ID = $_GET["ID"];
    $altA = $_GET["alternativaA"];
    $altB = $_GET["alternativaB"];
    $altC = $_GET["alternativaC"];
    $altD = $_GET["alternativaD"];
    $altE = $_GET["alternativaE"];
    $alt_correta_id = $_GET["alternativaCorreta"];

    if (empty($pergunta) || empty($ID) || empty($alt_correta_id)) {
        $msg = "Preencha todos os campos obrigatórios.";
        $success = false;
    } else {
        // Formata os dados para salvar
        $linha = "$ID;$pergunta;$altA;$altB;$altC;$altD;$altE;$alt_correta_id\n";
        
        // Verifica se o arquivo existe e o cria se necessário, adicionando o conteúdo
        if (file_put_contents("perguntas.txt", $linha, FILE_APPEND) !== false) {
            $msg = "Pergunta cadastrada com sucesso! (ID: $ID)";
            $success = true;
        } else {
            $msg = "Erro ao salvar a pergunta no arquivo.";
            $success = false;
        }
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
<html>
<head>
    <meta charset="UTF-8">
    <title>Criar Pergunta Múltipla Escolha</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Criar Pergunta Múltipla Escolha</h1>
        
        <div id="status-message">
            <?php if (isset($msg) && !$is_ajax): // Mostra a mensagem apenas se não for AJAX ?>
                <div class="alert <?php echo $success ? 'success' : 'error'; ?>"><?php echo $msg; ?></div>
            <?php endif; ?>
        </div>
        
        <form id="create-question-form"> 
            <input type="text" name="ID" placeholder="ID da pergunta" required>
            <textarea name="pergunta" placeholder="Digite a pergunta" required></textarea>
            
            <input type="text" name="alternativaA" placeholder="Alternativa A">
            <input type="text" name="alternativaB" placeholder="Alternativa B">
            <input type="text" name="alternativaC" placeholder="Alternativa C">
            <input type="text" name="alternativaD" placeholder="Alternativa D">
            <input type="text" name="alternativaE" placeholder="Alternativa E">
            
            <input type="text" name="alternativaCorreta" placeholder="Letra da alternativa correta (A, B, C, D ou E)" required>
            
            <input type="submit" value="Salvar Pergunta" id="submit-button">
        </form>
        
        <div class="menu">
            <a href="menucriarpergunta.php">Voltar</a>
            <a href="menu.php">Menu Principal</a>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('create-question-form');
        const statusMessage = document.getElementById('status-message');
        const submitButton = document.getElementById('submit-button');

        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Impede o envio do formulário padrão
            
            // Limpa e exibe mensagem de processamento
            statusMessage.innerHTML = '<div class="alert" style="background: #f1c40f; color: #333;">Processando...</div>';
            submitButton.value = 'Salvando...';
            submitButton.disabled = true;

            const formData = new FormData(form);
            
            // Converte os dados do formulário em uma query string
            const params = new URLSearchParams(formData).toString();
            
            // Adiciona um cabeçalho para o PHP identificar a chamada AJAX
            const headers = new Headers();
            headers.append('X-Requested-With', 'XMLHttpRequest');

            
            fetch(`criarperguntamultiplaescolha.php?${params}`, {
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
                statusMessage.innerHTML = `<div class="alert ${alertClass}">${data.msg}</div>`;
                
                if (data.success) {
                    // Se for sucesso, limpa os campos para o próximo cadastro
                    form.reset(); 
                    // Foca no primeiro campo para agilizar
                    form.querySelector('input[name="ID"]').focus(); 
                }
            })
            .catch(error => {
                console.error('Erro de rede:', error);
                statusMessage.innerHTML = '<div class="alert error">Erro de comunicação com o servidor.</div>';
            })
            .finally(() => {
                submitButton.value = 'Salvar Pergunta';
                submitButton.disabled = false;
            });
        });
    });
</script>
</body>
</html>