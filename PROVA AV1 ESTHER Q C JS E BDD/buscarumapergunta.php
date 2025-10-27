<?php
// buscarumapergunta.php
session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

$msg = "";
$perguntaEncontrada = null;

// Verifica se é uma chamada AJAX
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['ID']) && isset($_GET['ajax'])) {
    
    $ID = $_GET['ID'];
    $fileName = "perguntas.txt";
    $htmlResultado = "";
    
    if (!file_exists($fileName)) {
        $htmlResultado = "<p>Arquivo não encontrado.</p>";
    } else {
        $file = fopen($fileName, "r");
        $perguntaEncontrada = null;
        $encontrou = false;
        
        while (!feof($file)) {
            $linha = fgets($file);
            if (trim($linha) == "") continue;
            
            $colunaDados = explode(";", $linha);
            if (count($colunaDados) >= 8 && trim($colunaDados[0]) == $ID) {
                $perguntaEncontrada = [
                    'ID' => $colunaDados[0],
                    'pergunta' => $colunaDados[1],
                    'altA' => $colunaDados[2],
                    'altB' => $colunaDados[3],
                    'altC' => $colunaDados[4],
                    'altD' => $colunaDados[5],
                    'altE' => $colunaDados[6],
                    'altCorreta' => trim($colunaDados[7])
                ];
                $encontrou = true;
                break;
            }
        }
        fclose($file);
        
        if ($encontrou) {
            $p = $perguntaEncontrada;
            $htmlResultado = '
                <h2>Pergunta Encontrada</h2>
                <table border="1" style="width: 100%;">
                    <tr>
                        <th>ID</th>
                        <th>Pergunta</th>
                        <th>Alternativa A</th>
                        <th>Alternativa B</th>
                        <th>Alternativa C</th>
                        <th>Alternativa D</th>
                        <th>Alternativa E</th>
                        <th>Resposta Correta</th>
                    </tr>
                    <tr>
                        <td>' . htmlspecialchars($p['ID']) . '</td>
                        <td>' . htmlspecialchars($p['pergunta']) . '</td>
                        <td>' . htmlspecialchars($p['altA']) . '</td>
                        <td>' . htmlspecialchars($p['altB']) . '</td>
                        <td>' . htmlspecialchars($p['altC']) . '</td>
                        <td>' . htmlspecialchars($p['altD']) . '</td>
                        <td>' . htmlspecialchars($p['altE']) . '</td>
                        <td>' . htmlspecialchars($p['altCorreta']) . '</td>
                    </tr>
                </table>';
        } else {
            $htmlResultado = "<p>Pergunta não encontrada.</p>";
        }
    }
    
    echo $htmlResultado;
    exit; // Termina o script após a requisição AJAX
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
            <?php if (!empty($msg)): ?>
                <p><?php echo $msg; ?></p>
            <?php endif; ?>
            
            <?php if ($perguntaEncontrada): ?>
            <h2>Pergunta Encontrada</h2>
            <table border="1" style="width: 100%;">
                <tr>
                    <th>ID</th>
                    <th>Pergunta</th>
                    <th>Alternativa A</th>
                    <th>Alternativa B</th>
                    <th>Alternativa C</th>
                    <th>Alternativa D</th>
                    <th>Alternativa E</th>
                    <th>Resposta Correta</th>
                </tr>
                <tr>
                    <td><?php echo $perguntaEncontrada['ID']; ?></td>
                    <td><?php echo $perguntaEncontrada['pergunta']; ?></td>
                    <td><?php echo $perguntaEncontrada['altA']; ?></td>
                    <td><?php echo $perguntaEncontrada['altB']; ?></td>
                    <td><?php echo $perguntaEncontrada['altC']; ?></td>
                    <td><?php echo $perguntaEncontrada['altD']; ?></td>
                    <td><?php echo $perguntaEncontrada['altE']; ?></td>
                    <td><?php echo $perguntaEncontrada['altCorreta']; ?></td>
                </tr>
            </table>
            <?php endif; ?>
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
            event.preventDefault(); // Impede o envio do formulário padrão (recarregar a página)
            const id = inputID.value.trim();

            if (id) {
                buscarPergunta(id);
            }
        });

        function buscarPergunta(id) {
            resultadoBusca.innerHTML = '<p>Buscando...</p>'; // Mensagem de carregamento

            // O parâmetro 'ajax=true' indica ao PHP que é uma requisição assíncrona
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
                // Injeta o HTML retornado pelo PHP na div de resultados
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