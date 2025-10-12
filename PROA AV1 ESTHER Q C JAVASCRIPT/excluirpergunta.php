<?php
// excluirpergunta.php
session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

$msg = "";
$ID_excluir = null;

// Verifica se a ação de exclusão foi enviada via GET (para compatibilidade e a lógica de exclusão)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['ID'])) {
    $ID = $_GET['ID'];
    $fileName = "perguntas.txt";
    $tempFile = "perguntastemp.txt";
    $ID_excluir = $ID;
    $success = false;
    
    if (!file_exists($fileName)) {
        $msg = "Arquivo não encontrado.";
    } else {
        $file = fopen($fileName, "r");
        $temp = fopen($tempFile, "w");
        $encontrou = false;
        
        while (!feof($file)) {
            $linha = fgets($file);
            
            if (trim($linha) == "") continue;
            
            $colunaDados = explode(";", $linha);
            
            if (count($colunaDados) >= 8) {
                if (trim($colunaDados[0]) == $ID) {
                    $encontrou = true;
                } else {
                    fwrite($temp, $linha);
                }
            }
        }
        
        fclose($file);
        fclose($temp);
        
        if ($encontrou) {
            if (rename($tempFile, $fileName)) {
                $msg = "Pergunta excluída com sucesso!";
                $success = true;
            } else {
                $msg = "Erro ao excluir a pergunta.";
            }
        } else {
            unlink($tempFile);
            $msg = "Pergunta não encontrada.";
        }
    }
    
    // Resposta para a chamada AJAX
    $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
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
    <title>Excluir Pergunta</title>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>
<body>
    <section class="menu-container">
        <h1>Excluir Pergunta</h1>
        
        <div id="mensagem-status">
            <?php if (!empty($msg)): ?>
                <p><?php echo $msg; ?></p>
            <?php endif; ?>
        </div>
        
        <h2>Lista de Perguntas</h2>
        <table border="1" style="width: 100%;" id="tabela-perguntas">
            <tr>
                <th>ID</th>
                <th>Pergunta</th>
                <th>Ação</th>
            </tr>
            <?php
            $fileName = "perguntas.txt";
            if (file_exists($fileName)) {
                $file = fopen($fileName, "r");
                
                while (!feof($file)) {
                    $linha = fgets($file);
                    if (trim($linha) == "") continue;
                    
                    $colunaDados = explode(";", $linha);
                    if (count($colunaDados) >= 8) {
                        echo "<tr id='linha-{$colunaDados[0]}'>
                            <td>" . $colunaDados[0] . "</td>
                            <td>" . $colunaDados[1] . "</td>
                            <td><a href='#' data-id='{$colunaDados[0]}' class='link-excluir'>Excluir</a></td>
                        </tr>";
                    }
                }
                fclose($file);
            }
            ?>
        </table>
        
        <br>
        <a href="menu.php" class="menu">Voltar ao Menu</a>
    </section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabela = document.getElementById('tabela-perguntas');
        const mensagemStatus = document.getElementById('mensagem-status');

        tabela.addEventListener('click', (event) => {
            if (event.target.classList.contains('link-excluir')) {
                event.preventDefault();
                const id = event.target.getAttribute('data-id');
                
                if (confirm(`Tem certeza que deseja excluir a pergunta ID ${id}?`)) {
                    excluirPergunta(id, event.target.closest('tr'));
                }
            }
        });

        function excluirPergunta(id, linhaTabela) {
            // Limpa mensagens anteriores
            mensagemStatus.innerHTML = '<p style="color: #02121dff;">Excluindo...</p>';
            
            // Usando Fetch para enviar a requisição de exclusão via GET
            // Adiciona um cabeçalho para o PHP identificar a chamada AJAX
            const headers = new Headers();
            headers.append('X-Requested-With', 'XMLHttpRequest');

            fetch(`excluirpergunta.php?ID=${id}`, {
                method: 'GET',
                headers: headers
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove a linha da tabela e exibe o status
                    linhaTabela.remove(); 
                    mensagemStatus.innerHTML = `<p style="color: green;">${data.msg}</p>`;
                } else {
                    mensagemStatus.innerHTML = `<p style="color: red;">${data.msg}</p>`;
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                mensagemStatus.innerHTML = '<p style="color: red;">Erro de comunicação com o servidor.</p>';
            });
        }
    });
</script>

</body>
</html>