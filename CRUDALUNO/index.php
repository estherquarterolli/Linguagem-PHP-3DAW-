<?php

include 'conexao.php'; // Inclui a conexão com o banco

// Função para listar todos os alunos
$sql = "SELECT id, matricula, nome, email FROM alunos ORDER BY nome ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>CRUD Alunos - Listagem</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Sistema CRUD de Alunos</h1>

        <div class="menu-crud">
            <a href="create.php">Incluir Novo Aluno </a>
        </div>

        <h2>Lista de Alunos Cadastrados</h2>
        
        <?php if (isset($_GET['msg'])): ?>
            <p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($_GET['msg']); ?></p>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Matrícula</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['matricula']); ?></td>
                            <td><?php echo htmlspecialchars($row['nome']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td>
                                <a href="view.php?id=<?php echo $row['id']; ?>" class="action-btn btn-view">Ver</a>
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="action-btn btn-edit">Alterar</a>
                                <a href="aluno_actions.php?action=delete&id=<?php echo $row['id']; ?>" class="action-btn btn-delete" onclick="return confirm('Tem certeza que deseja EXCLUIR o aluno <?php echo addslashes($row['nome']); ?>?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum aluno cadastrado ainda.</p>
        <?php endif; ?>

        <?php $conn->close(); // Fecha a conexão ?>
    </div>
    <script src="js/scripts.js"></script>
</body>
</html>