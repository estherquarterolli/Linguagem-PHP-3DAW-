<?php

include 'conexao.php';

$aluno = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    // Consulta para buscar os dados do aluno
    $sql = "SELECT id, matricula, nome, email FROM alunos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $aluno = $result->fetch_assoc();
    } else {
        // Redireciona se o ID for inválido
        header("Location: index.php?msg=Aluno não encontrado.");
        exit();
    }
    $stmt->close();
} else {
    // Redireciona se não houver ID
    header("Location: index.php?msg=ID de aluno inválido.");
    exit();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>CRUD Alunos - Alterar</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Alterar Dados do Aluno</h1>
        <p><a href="index.php">Voltar para a Lista</a></p>

        <form action="aluno_actions.php" method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($aluno['id']); ?>">
            
            <label for="nome">Nome:</label><br>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($aluno['nome']); ?>" required><br><br>
            
            <label for="matricula">Matrícula:</label><br>
            <input type="text" id="matricula" name="matricula" value="<?php echo htmlspecialchars($aluno['matricula']); ?>" required><br><br>
            
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($aluno['email']); ?>" required><br><br>
            
            <input type="submit" value="Salvar Alterações">
        </form>

    </div>
</body>
</html>