<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta e limpa os dados do formulário
    $nome = trim($_POST['nome']);
    $cpf = trim($_POST['cpf']);
    $email = trim($_POST['email']);
    $materia = trim($_POST['materia']);
    $data_ingresso = trim($_POST['data_ingresso']);
    $turno = trim($_POST['turno']);

    // Validação simples para garantir que campos essenciais não estão vazios
    if (!empty($nome) && !empty($cpf) && !empty($email)) {
        $arquivo = 'alunos.txt';
        // Monta a linha com os dados separados por ';'
        $linha = $nome . ";" . $cpf . ";" . $email . ";" . $materia . ";" . $data_ingresso . ";" . $turno . "\n";
        
        // Salva a linha no final do arquivo
        file_put_contents($arquivo, $linha, FILE_APPEND | LOCK_EX);

        // Redireciona para a página principal
        header("Location: index.php");
        exit();
    } else {
        $msg_erro = "Os campos Nome, CPF e Email são obrigatórios!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Aluno</title>
    <style> body { font-family: sans-serif; } input, select, button, a { display: block; padding: 10px; margin-top: 5px; width: 300px; box-sizing: border-box; } a { text-align: center; background-color: #6c757d; color: white; text-decoration: none; } </style>
</head>
<body>
    <h1>Cadastrar Novo Aluno</h1>
    <?php if (isset($msg_erro)) echo "<p style='color:red;'>$msg_erro</p>"; ?>
    <form action="inserir.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="materia">Matéria:</label>
        <input type="text" id="materia" name="materia">

        <label for="data_ingresso">Data de Ingresso:</label>
        <input type="date" id="data_ingresso" name="data_ingresso">
        
        <label for="turno">Turno:</label>
        <select id="turno" name="turno">
            <option value="Manha">Manhã</option>
            <option value="Tarde">Tarde</option>
            <option value="Noite">Noite</option>
        </select>
        
        <br>
        <button type="submit">Salvar Aluno</button>
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>