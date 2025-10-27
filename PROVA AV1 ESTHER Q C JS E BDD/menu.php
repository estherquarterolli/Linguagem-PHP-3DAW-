<?php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

$isAdmin = ($_SESSION['usuario_logado']['tipo'] == 'admin');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Menu Principal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Menu Principal</h1>
        <p>Bem-vindo, <?php echo $_SESSION['usuario_logado']['nome']; ?>! 
           (<?php echo $isAdmin ? 'Administrador' : 'Usuário'; ?>)</p>
        
        <div class="menu">
            <a href="responder_perguntas.php">Responder Perguntas</a>
            <a href="listarpergunta.php">Listar Perguntas</a>
            <a href="buscarumapergunta.php">Buscar Pergunta</a>
            
            <?php if ($isAdmin): ?>
                <a href="menucriarpergunta.php" class="admin">Criar Perguntas</a>
                <a href="buscaperguntaparaalterar.html" class="admin">Alterar Perguntas</a>
                <a href="excluirpergunta.php" class="admin">Excluir Perguntas</a>
                <a href="usuarios.php" class="admin">Gerenciar Usuários</a>
            <?php endif; ?>
            
            <a href="logout.php">Sair</a>
        </div>
    </div>
</body>
</html>