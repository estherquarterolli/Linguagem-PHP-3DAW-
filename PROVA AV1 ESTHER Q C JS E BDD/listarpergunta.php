<?php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

require 'conexao.php';
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
       
        $sql = "SELECT * FROM perguntas ORDER BY id_pergunta";
        
   
        $result = $conexao->query($sql);
        
        if (!$result) {
            echo "<p>Erro ao consultar o banco de dados: " . $conexao->error . "</p>";
        } elseif ($result->num_rows == 0) {
            echo "<p>Nenhuma pergunta cadastrada.</p>";
        } else {
           
            echo '<table>
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Pergunta</th>
                        <th>A</th>
                        <th>B</th>
                        <th>C</th>
                        <th>D</th>
                        <th>E</th>
                        <th>Resposta</th>
                    </tr>';
            
       
            while($row = $result->fetch_assoc()){
                echo "<tr>
                        <td>" . htmlspecialchars($row['id_pergunta']) . "</td>
                        <td>" . htmlspecialchars($row['tipo']) . "</td>
                        <td>" . htmlspecialchars($row['pergunta']) . "</td>
                        <td>" . htmlspecialchars($row['alternativa_a']) . "</td>
                        <td>" . htmlspecialchars($row['alternativa_b']) . "</td>
                        <td>" . htmlspecialchars($row['alternativa_c']) . "</td>
                        <td>" . htmlspecialchars($row['alternativa_d']) . "</td>
                        <td>" . htmlspecialchars($row['alternativa_e']) . "</td>
                        <td>" . htmlspecialchars($row['resposta_correta']) . "</td>
                      </tr>";
            }
            
            echo '</table>';
        }
        
 
        $conexao->close();
        ?>
        
        <div class="menu">
            <a href="menu.php">Voltar ao Menu</a>
        </div>
    </div>
</body>
</html>