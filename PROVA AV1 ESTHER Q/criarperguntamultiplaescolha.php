<?php
session_start();
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado']['tipo'] != 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pergunta = $_POST["pergunta"];
    $ID = $_POST["ID"];
    $altA = $_POST["alternativaA"];
    $altB = $_POST["alternativaB"];
    $altC = $_POST["alternativaC"];
    $altD = $_POST["alternativaD"];
    $altE = $_POST["alternativaE"];
    $alt_correta_id = $_POST["alternativaCorreta"];

    if (empty($pergunta) || empty($ID) || empty($alt_correta_id)) {
        $msg = "Preencha todos os campos obrigatórios.";
    } else {
        $linha = "$ID;$pergunta;$altA;$altB;$altC;$altD;$altE;$alt_correta_id\n";
        file_put_contents("perguntas.txt", $linha, FILE_APPEND);
        $msg = "Pergunta cadastrada com sucesso!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Criar Pergunta Múltipla Escolha</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Criar Pergunta Múltipla Escolha</h1>
        
        <?php if (isset($msg)): ?>
            <div class="alert success"><?php echo $msg; ?></div>
        <?php endif; ?>
        
        <form method="post">
            <input type="text" name="ID" placeholder="ID da pergunta" required>
            <textarea name="pergunta" placeholder="Digite a pergunta" required></textarea>
            
            <input type="text" name="alternativaA" placeholder="Alternativa A">
            <input type="text" name="alternativaB" placeholder="Alternativa B">
            <input type="text" name="alternativaC" placeholder="Alternativa C">
            <input type="text" name="alternativaD" placeholder="Alternativa D">
            <input type="text" name="alternativaE" placeholder="Alternativa E">
            
            <input type="text" name="alternativaCorreta" placeholder="Letra da alternativa correta (A, B, C, D ou E)" required>
            
            <input type="submit" value="Salvar Pergunta">
        </form>
        
        <div class="menu">
            <a href="menucriarpergunta.php">Voltar</a>
            <a href="menu.php">Menu Principal</a>
        </div>
    </div>
</body>
</html>