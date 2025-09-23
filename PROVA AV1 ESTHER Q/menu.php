<?php
// menu.php
session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Menu Principal</title>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>
<body>
    <section class="menu-container">
        <h1>Sistema de perguntas e respostas</h1>
        <p>Bem-vindo, <?php echo $_SESSION['usuario_logado']['nome']; ?>!</p>
        
        <section class="menu">
            <a href="listarpergunta.php">Listar Todas as Perguntas</a>
            <a href="buscarumapergunta.php">Buscar uma Pergunta</a>
            <a href="buscaperguntaparaalterar.html">Buscar Pergunta para Alterar</a>
            <a href="menucriarpergunta.php">Criar Perguntas</a>
            <a href="excluirpergunta.php">Excluir Perguntas</a>
            <a href="usuarios.php">Gerenciar Usuários</a>
            <a href="login.php?logout=1">Encerrar Sessão</a>
        </section>
    </section>
</body>
</html>