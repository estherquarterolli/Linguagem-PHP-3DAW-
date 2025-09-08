<?php
$arquivo = 'disciplina.txt';
$id = $_GET['id'];

// Carrega todas as linhas do arquivo em um array
$linhas = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Verifica se o ID (linha) existe no arquivo
if (!isset($linhas[$id])) {
    echo "Disciplina não encontrada!";
    exit;
}

// Pega os dados da linha específica para preencher o formulário
list($nome, $sigla, $carga) = explode(';', $linhas[$id]);

// Se o formulário foi enviado (método POST), atualiza os dados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novo_nome = trim($_POST['nome']);
    $nova_sigla = trim($_POST['sigla']);
    $nova_carga = trim($_POST['carga']);
    
    // Recria a linha com os novos dados
    $nova_linha = $novo_nome . ";" . $nova_sigla . ";" . $nova_carga;
    
    // Substitui a linha antiga pela nova no array
    $linhas[$id] = $nova_linha;
    
    // Junta todas as linhas do array em uma única string, separadas por quebra de linha
    $conteudo_final = implode("\n", $linhas) . "\n";
    
    // Escreve o conteúdo final de volta no arquivo, sobrescrevendo o antigo
    file_put_contents($arquivo, $conteudo_final, LOCK_EX);
    
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Disciplina</title>
    <style> body { font-family: sans-serif; } input, button { padding: 10px; margin-top: 5px; width: 300px; } </style>
</head>
<body>
    <h1>Editar Disciplina</h1>
    <form action="editar.php?id=<?php echo $id; ?>" method="POST">
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required><br><br>
        
        <label for="sigla">Sigla:</label><br>
        <input type="text" id="sigla" name="sigla" value="<?php echo htmlspecialchars($sigla); ?>" required><br><br>
        
        <label for="carga">Carga Horária:</label><br>
        <input type="number" id="carga" name="carga" value="<?php echo htmlspecialchars($carga); ?>" required><br><br>
        
        <button type="submit">Atualizar Disciplina</button>
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>