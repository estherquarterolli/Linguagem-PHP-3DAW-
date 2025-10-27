<?php

session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}


require 'conexao.php';

$msg = "";
$success = false;

// Verifica se a ação de exclusão foi enviada (lógica AJAX)
$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['ID'])) {
    $ID = $_GET['ID'];
    
  
    $sql = "DELETE FROM perguntas WHERE id_pergunta = ?";
    $stmt = $conexao->prepare($sql);
    
    if ($stmt) {
    
        $stmt->bind_param("s", $ID);
        

        if ($stmt->execute()) {
         
            if ($stmt->affected_rows > 0) {
                $msg = "Pergunta excluída com sucesso!";
                $success = true;
            } else {
                $msg = "Pergunta não encontrada.";
                $success = false;
            }
        } else {
            $msg = "Erro ao excluir a pergunta: " . $stmt->error;
            $success = false;
        }
       
        $stmt->close();
        
    } else {
        $msg = "Erro ao preparar a query: " . $conexao->error;
        $success = false;
    }

   
    if ($is_ajax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => $success, 'msg' => $msg]);
        $conexao->close(); // Fecha a conexão e termina o script
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
            <?php if (!empty($msg) && !$is_ajax): // Mostra msg se não for AJAX ?>
                <p class="<?php echo $success ? 'success' : 'error'; ?>"><?php echo $msg; ?></p>
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
            
            $sql_list = "SELECT id_pergunta, pergunta FROM perguntas ORDER BY id_pergunta";
            $result = $conexao->query($sql_list);
            
            if ($result && $result->num_rows > 0) {
           
                while ($row = $result->fetch_assoc()) {
                    $id_pergunta = htmlspecialchars($row['id_pergunta']);
                    $pergunta_texto = htmlspecialchars($row['pergunta']);
                    
                
                    echo "<tr id='linha-{$id_pergunta}'>
                            <td>{$id_pergunta}</td>
                            <td>{$pergunta_texto}</td>
                            <td><a href='#' data-id='{$id_pergunta}' class='link-excluir'>Excluir</a></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Nenhuma pergunta cadastrada.</td></tr>";
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
            mensagemStatus.innerHTML = '<p style="color: #02121dff;">Excluindo...</p>';
            
            const headers = new Headers();
            headers.append('X-Requested-With', 'XMLHttpRequest');

            fetch(`excluirpergunta.php?ID=${id}`, {
                method: 'GET',
                headers: headers
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
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
<?php
// Fecha a conexão que foi aberta no início do arquivo
$conexao->close();
?>