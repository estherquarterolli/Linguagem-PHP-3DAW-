<?php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Listar Perguntas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Listar Perguntas</h1>
        
        <?php
        $fileName = "perguntas.txt";
        if (!file_exists($fileName)) {
            echo "<p>Nenhuma pergunta cadastrada.</p>";
        } else {
            echo '<table>
                <tr><th>ID</th><th>Pergunta</th><th>A</th><th>B</th><th>C</th><th>D</th><th>E</th><th>Resposta</th></tr>';
            
            $file = fopen($fileName, 'r');
            while(!feof($file)){
                $linha = fgets($file);
                if(!empty(trim($linha))){
                    $dados = explode(";", $linha);
                    $dados = array_pad($dados, 8, '');
                    
                    echo "<tr>
                        <td>{$dados[0]}</td>
                        <td>{$dados[1]}</td>
                        <td>{$dados[2]}</td>
                        <td>{$dados[3]}</td>
                        <td>{$dados[4]}</td>
                        <td>{$dados[5]}</td>
                        <td>{$dados[6]}</td>
                        <td>" . trim($dados[7]) . "</td>
                    </tr>";
                }
            }
            fclose($file);
            echo '</table>';
        }
        ?>
        
        <div class="menu">
            <a href="menu.php">Voltar ao Menu</a>
        </div>
    </div>
</body>
</html>