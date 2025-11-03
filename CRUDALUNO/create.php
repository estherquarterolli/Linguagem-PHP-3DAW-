<?php

include 'conexao.php'; 


?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>CRUD Alunos - Incluir</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Incluir Novo Aluno</h1>
        <p><a href="index.php">Voltar para a Lista</a></p>

        <form action="aluno_actions.php" method="POST">
            <input type="hidden" name="action" value="create">
            
            <label for="nome">Nome:</label><br>
            <input type="text" id="nome" name="nome" required><br><br>
            
            <label for="matricula">Matrícula:</label><br>
            <input type="text" id="matricula" name="matricula" required><br><br>
            
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>
            
            <input type="submit" value="Salvar Aluno">
        </form>

    </div>
</body>
</html>