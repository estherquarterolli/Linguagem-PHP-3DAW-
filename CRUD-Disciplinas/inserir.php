<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $sigla = trim($_POST['sigla']);
    $carga = trim($_POST['carga']);

    if (!empty($nome) && !empty($sigla) && !empty($carga)) {
        $arquivo = 'disciplina.txt';
        $linha = $nome . ";" . $sigla . ";" . $carga . "\n";
        
        file_put_contents($arquivo, $linha, FILE_APPEND | LOCK_EX);

        // Redireciona para a página principal após a inserção
        header("Location: index.php");
        exit(); // Encerra o script para garantir que o redirecionamento ocorra
    } else {
        $msg_erro = "Todos os campos são obrigatórios!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Disciplina</title>
    <style> body { font-family: sans-serif; } input, button { padding: 10px; margin-top: 5px; width: 300px; } </style>
</head>
<body>
    <h1>Cadastrar Nova Disciplina</h1>
    <?php if (isset($msg_erro)) echo "<p style='color:red;'>$msg_erro</p>"; ?>
    <form action="inserir.php" method="POST">
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" required><br><br>
        
        <label for="sigla">Sigla:</label><br>
        <input type="text" id="sigla" name="sigla" required><br><br>
        
        <label for="carga">Carga Horária:</label><br>
        <input type="number" id="carga" name="carga" required><br><br>
        
        <button type="submit">Salvar Disciplina</button>
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>