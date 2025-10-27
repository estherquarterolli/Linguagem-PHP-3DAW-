<?php
session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

// Inclui a conexão com o banco
require 'conexao.php';

$htmlResultado = "";
$perguntaEncontrada = null;
$is_ajax = isset($_GET['ajax']) && $_GET['ajax'] == 'true';

// Processa a busca se um ID for fornecido (seja AJAX ou não)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['ID'])) {
    
    $ID = $_GET['ID'];
    
    // 1. Preparar a SQL para buscar a pergunta
    $sql = "SELECT * FROM perguntas WHERE id_pergunta = ?";
    $stmt = $conexao->prepare($sql);
    
    if ($stmt) {
        // 2. Vincular o ID
        $stmt->bind_param("s", $ID);
        
        // 3. Executar a busca
        $stmt->execute();
        
        // 4. Obter o resultado
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // 5. Pergunta encontrada, buscar dados
            $perguntaEncontrada = $result->fetch_assoc();
            
            $p = $perguntaEncontrada;
            
            // 6. Montar o HTML de resposta
            // Usamos htmlspecialchars para evitar XSS
            $htmlResultado = '
                <h2>Pergunta Encontrada</h2>
                <table border="1" style="width: 100%;">
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Pergunta</th>
                        <th>Alternativa A</th>
                        <th>Alternativa B</th>
                        <th>Alternativa C</th>
                        <th>Alternativa D</th>
                        <th>Alternativa E</th>
                        <th>Resposta Correta</th>
                    </tr>
                    <tr>
                        <td>' . htmlspecialchars($p['id_pergunta']) . '</td>
                        <td>' . htmlspecialchars($p['tipo']) . '</td>
                        <td>' . htmlspecialchars($p['pergunta']) . '</td>
                        <td>' . htmlspecialchars($p['alternativa_a']) . '</td>
                        <td>' . htmlspecialchars($p['alternativa_b']) . '</td>
                        <td>' . htmlspecialchars($p['alternativa_c']) . '</td>
                        <td>' . htmlspecialchars($p['alternativa_d']) . '</td>
                        <td>' . htmlspecialchars($p['alternativa_e']) . '</td>
                        <td>' . htmlspecialchars($p['resposta_correta']) . '</td>
                    </tr>
                </table>';
        } else {
            $htmlResultado = "<p>Pergunta não encontrada.</p>";
        }
        
        // 7. Fechar o statement
        $stmt->close();
        
    } else {
        $htmlResultado = "<p>Erro ao preparar a consulta: " . $conexao->error . "</p>";
    }
    
    // Se for AJAX, imprime o resultado e para o script
    if ($is_ajax) {
        echo $htmlResultado;
        $conexao->close();
        exit;
    }
    // Se não for AJAX, $htmlResultado será usado no HTML abaixo
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar uma Pergunta</title>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>
<body>
    <section class="menu-container">
        <h1>Buscar uma Pergunta</h1>
        
        <form id="form-busca"> 
            ID da Pergunta: <input type="text" name="ID" id="input-id" required>
            <input type="submit" value="Buscar">
        </form>
        
        <div id="resultado-busca">
            <?php echo $htmlResultado; // Exibe o resultado se a busca não foi via AJAX ?>
        </div>
        
        <br>
        <a href="menu.php" class="menu">Voltar ao Menu</a>
    </section>

<script>

    document.addEventListener('DOMContentLoaded', () => {
        const formBusca = document.getElementById('form-busca');
        const inputID = document.getElementById('input-id');
        const resultadoBusca = document.getElementById('resultado-busca');

        formBusca.addEventListener('submit', (event) => {
            event.preventDefault(); 
            const id = inputID.value.trim();

            if (id) {
                buscarPergunta(id);
            }
        });

        function buscarPergunta(id) {
            resultadoBusca.innerHTML = '<p>Buscando...</p>'; 

            fetch(`buscarumapergunta.php?ID=${id}&ajax=true`, {
                method: 'GET'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                resultadoBusca.innerHTML = html; 
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                resultadoBusca.innerHTML = '<p style="color: red;">Erro ao buscar a pergunta.</p>';
            });
        }
    });
</script>

</body>
</html>
<?php

if ($conexao) {
    $conexao->close();
}
?>