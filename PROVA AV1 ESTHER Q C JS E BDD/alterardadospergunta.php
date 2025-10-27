<?php
session_start();
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado']['tipo'] != 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ID = $_POST['ID'];
    $pergunta = $_POST['pergunta'];
    $tipo = $_POST['tipo'];
    
    // Dependendo do tipo, processar diferentes campos
    if ($tipo == 'multipla') {
        $altA = $_POST['alternativaA'];
        $altB = $_POST['alternativaB'];
        $altC = $_POST['alternativaC'];
        $altD = $_POST['alternativaD'];
        $altE = $_POST['alternativaE'];
        $altCorretaID = $_POST['alternativaCorreta'];
    } else {
        $altA = $altB = $altC = $altD = $altE = "";
        $altCorretaID = $_POST['respostaDissertativa'];
    }
    
    $fileName = "perguntas.txt";
    $tempFile = "perguntastemp.txt";
    
    if (!file_exists($fileName)) {
        echo "<div class='alert alert-error'>Arquivo não encontrado.</div>";
    } else {
        $file = fopen($fileName, "r") or die("Não foi possível abrir o arquivo.");
        $temp = fopen($tempFile, "w") or die("Não foi possível criar o arquivo temporário.");
        
        while (!feof($file)) {
            $linha = fgets($file);
            
            if(trim($linha) == "") continue;           
            
            $colunaDados = explode(";", $linha);
            
            if(count($colunaDados) >= 8){
                if(trim($colunaDados[0]) == $ID){
                    $novaLinha = "$ID;$pergunta;$altA;$altB;$altC;$altD;$altE;$altCorretaID\n";
                    fwrite($temp, $novaLinha);
                } else {
                    fwrite($temp, $linha);
                }
            }
        }
       
        fclose($file);
        fclose($temp);
        
        if (rename($tempFile, $fileName)) {
            $msg = "<div class='alert alert-success'>Pergunta atualizada com sucesso!</div>";
        } else {
            $msg = "<div class='alert alert-error'>Erro ao atualizar a pergunta.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da Alteração</title>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>
<body>
    <section class="menu-container">
        <h1>Resultado da Alteração</h1>
        
        <?php if (isset($msg)) echo $msg; ?>
        
        <section class="menu">
            <a href="menu.php">🏠 Voltar ao Menu Inicial</a>
            <a href="buscaperguntaparaalterar.html">🔍 Alterar Outra Pergunta</a>
        </section>
    </section>
</body>
</html>