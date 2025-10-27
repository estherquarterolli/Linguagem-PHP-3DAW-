<?php
session_start();

// Inclui o arquivo de conexão com o banco de dados
require 'conexao.php';

$msg = "";
$success = false;
$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';


if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['ID'])){


    $pergunta = $_GET["pergunta"];
    $ID = $_GET["ID"];
    $alt_correta_id = $_GET["alternativaCorreta"]; // Esta é a resposta esperada
    $tipo = "dissertativa"; 
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
        
    
        $sql = "INSERT INTO perguntas 
                    (id_pergunta, tipo, pergunta, alternativa_a, alternativa_b, alternativa_c, alternativa_d, alternativa_e, resposta_correta) 
                VALUES 
                    (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conexao->prepare($sql);

        if ($stmt) {
           
            $stmt->bind_param("sssssssss", 
                $ID, 
                $tipo, 
                $pergunta, 
                $altA, 
                $altB, 
                $altC, 
                $altD, 
                $altE, 
                $alt_correta_id
            );

            
            if ($stmt->execute()) {
                $msg = "Pergunta Dissertativa Cadastrada (ID: $ID)";
                $success = true;
            } else {
               
                if ($conexao->errno == 1062) {
                     $msg = "Erro: O ID da pergunta '$ID' já existe. Tente outro.";
                } else {
                     $msg = "Erro ao salvar no banco: " . $stmt->error;
                }
                $success = false;
            }
            
            $stmt->close();
        } else {
            $msg = "Erro ao preparar a query: " . $conexao->error;
            $success = false;
        }
        
        
        $conexao->close();

       
    }
    
    
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
            <label for="alt_correta">Resposta esperada:</label> 
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
            e.preventDefault(); 
            
            statusMessage.innerHTML = '<div class="alert info text-center">Processando...</div>';
            submitButton.value = 'Salvando...';
            submitButton.disabled = true;

            const formData = new FormData(form);
            const params = new URLSearchParams(formData).toString();
            
            const headers = new Headers();
            headers.append('X-Requested-With', 'XMLHttpRequest');

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
                    form.reset();
                    textarea.value = 'Insira aqui sua resposta'; 
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