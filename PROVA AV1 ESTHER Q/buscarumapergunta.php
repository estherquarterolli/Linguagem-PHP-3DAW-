<?php
// buscarumapergunta.php
session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

$msg = "";
$perguntaEncontrada = null;

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['ID'])) {
    $ID = $_GET['ID'];
    $fileName = "perguntas.txt";
    
    if (!file_exists($fileName)) {
        $msg = "Arquivo não encontrado.";
    } else {
        $file = fopen($fileName, "r");
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
        
        if (!$encontrou) {
            $msg = "Pergunta não encontrada.";
        }
    }
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
        
        <form method="get">
            ID da Pergunta: <input type="text" name="ID" required>
            <input type="submit" value="Buscar">
        </form>
        
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
        
        <br>
        <a href="menu.php" class="menu">Voltar ao Menu</a>
    </section>
</body>
</html>