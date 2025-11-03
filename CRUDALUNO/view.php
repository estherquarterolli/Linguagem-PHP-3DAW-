<?php

include 'conexao.php';

$aluno = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT id, matricula, nome, email, data_criacao FROM alunos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $aluno = $result->fetch_assoc();
    } else {
        header("Location: index.php?msg=Aluno não encontrado.");
        exit();
    }
    $stmt->close();
} else {
    header("Location: index.php?msg=ID de aluno inválido.");
    exit();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>CRUD Alunos - Detalhes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Detalhes do Aluno</h1>
        <p><a href="index.php">Voltar para a Lista</a></p>

        <?php if ($aluno): ?>
            <ul>
                <li><strong>ID:</strong> <?php echo htmlspecialchars($aluno['id']); ?></li>
                <li><strong>Matrícula:</strong> <?php echo htmlspecialchars($aluno['matricula']); ?></li>
                <li><strong>Nome:</strong> <?php echo htmlspecialchars($aluno['nome']); ?></li>
                <li><strong>Email:</strong> <?php echo htmlspecialchars($aluno['email']); ?></li>
                <li><strong>Data de Criação:</strong> <?php echo htmlspecialchars($aluno['data_criacao']); ?></li>
            </ul>
            <hr>
            <p>
                <a href="edit.php?id=<?php echo $aluno['id']; ?>" class="action-btn btn-edit">Alterar Aluno</a>
                <a href="aluno_actions.php?action=delete&id=<?php echo $aluno['id']; ?>" class="action-btn btn-delete" onclick="return confirm('Tem certeza que deseja EXCLUIR este aluno?');">Excluir Aluno</a>
            </p>
        <?php endif; ?>

    </div>
</body>
</html>