<?php
$arquivo = 'alunos.txt';
$id = $_GET['id'];

// Carrega todas as linhas do arquivo em um array
$linhas = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Verifica se a linha (ID) a ser editada existe
if (!isset($linhas[$id])) {
    echo "Aluno não encontrado!";
    exit;
}

// Pega os dados da linha específica para preencher o formulário
list($nome, $cpf, $email, $materia, $data_ingresso, $turno) = explode(';', $linhas[$id]);

// Se o formulário foi enviado (método POST), atualiza os dados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recria a linha com os novos dados
    $nova_linha = trim($_POST['nome']) . ";" . 
                  trim($_POST['cpf']) . ";" . 
                  trim($_POST['email']) . ";" . 
                  trim($_POST['materia']) . ";" . 
                  trim($_POST['data_ingresso']) . ";" . 
                  trim($_POST['turno']);
    
    // Substitui a linha antiga pela nova no array
    $linhas[$id] = $nova_linha;
    
    // Junta as linhas em uma string e salva no arquivo
    $conteudo_final = implode("\n", $linhas) . "\n";
    file_put_contents($arquivo, $conteudo_final, LOCK_EX);
    
    // Redireciona para a página principal
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Aluno</title>
    <style> body { font-family: sans-serif; } input, select, button, a { display: block; padding: 10px; margin-top: 5px; width: 300px; box-sizing: border-box; } a { text-align: center; background-color: #6c757d; color: white; text-decoration: none; } </style>
</head>
<body>
    <h1>Editar Aluno</h1>
    <form action="editar.php?id=<?php echo $id; ?>" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
        
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($cpf); ?>" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

        <label for="materia">Matéria:</label>
        <input type="text" id="materia" name="materia" value="<?php echo htmlspecialchars($materia); ?>">

        <label for="data_ingresso">Data de Ingresso:</label>
        <input type="date" id="data_ingresso" name="data_ingresso" value="<?php echo htmlspecialchars($data_ingresso); ?>">
        
        <label for="turno">Turno:</label>
        <select id="turno" name="turno">
            <option value="Manha" <?php echo ($turno == 'Manha') ? 'selected' : ''; ?>>Manhã</option>
            <option value="Tarde" <?php echo ($turno == 'Tarde') ? 'selected' : ''; ?>>Tarde</option>
            <option value="Noite" <?php echo ($turno == 'Noite') ? 'selected' : ''; ?>>Noite</option>
        </select>
        
        <br>
        <button type="submit">Atualizar Aluno</button>
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>