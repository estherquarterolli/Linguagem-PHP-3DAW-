<?php
// excluirpergunta.php
session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['ID'])) {
    $ID = $_GET['ID'];
    $fileName = "perguntas.txt";
    $tempFile = "perguntastemp.txt";
    
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
            } else {
                $msg = "Erro ao excluir a pergunta.";
            }
        } else {
            unlink($tempFile);
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
    <title>Excluir Pergunta</title>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>
<body>
    <section class="menu-container">
        <h1>Excluir Pergunta</h1>
        
        <?php if (!empty($msg)): ?>
            <p><?php echo $msg; ?></p>
        <?php endif; ?>
        
        <h2>Lista de Perguntas</h2>
        <table border="1" style="width: 100%;">
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
                        echo "<tr>
                            <td>" . $colunaDados[0] . "</td>
                            <td>" . $colunaDados[1] . "</td>
                            <td><a href='excluirpergunta.php?ID=" . $colunaDados[0] . "' onclick='return confirm(\"Tem certeza que deseja excluir?\")'>Excluir</a></td>
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
</body>
</html>