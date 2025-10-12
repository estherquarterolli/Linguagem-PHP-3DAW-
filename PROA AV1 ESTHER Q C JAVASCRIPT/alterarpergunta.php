<?php

$msg = "";
$pergunta = "";
$ID = "";
$altA = "";
$altB = "";
$altC = "";
$altD = "";
$altE = ""; 
$altCorretaID = "";
$nome_arquivo = "perguntas.txt";

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['ID'])) {
    
    $ID = $_GET["ID"];

    if (!file_exists($nome_arquivo)) {
        $msg = "Arquivo não encontrado!";
    } else {
        $file = fopen($nome_arquivo, "r") or die("Erro ao abrir o arquivo.");
        $achou = false;

        while (!feof($file)) {
            $linha = fgets($file);

            // Pula linhas vazias
            if(trim($linha) == "") continue;

            $colunaDados = explode(";", $linha);
            
           
            if (count($colunaDados) >= 8 && trim($colunaDados[0]) == $ID) {
                $pergunta = $colunaDados[1];
                $altA = $colunaDados[2];
                $altB = $colunaDados[3];
                $altC = $colunaDados[4];
                $altD = $colunaDados[5];
                $altE = $colunaDados[6]; 
                $altCorretaID = trim($colunaDados[7]);
                $achou = true;
                break;
            }
        }
        fclose($file);
        
        $msg = $achou ? "Pergunta encontrada." : "ID não encontrado."; 
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Pergunta</title>
</head>
<body>

    <p><?php echo $msg; ?></p>
    
    <form action="alterardadospergunta.php" method="post">
    
    <br><br>

        ID: <input type="text" name="ID" value='<?php echo $ID; ?>' readonly style="background-color: #f0f0f0; cursor: not-allowed;">
        <br><br>
        Nova Pergunta: <input type="text" name="pergunta" value='<?php echo $pergunta; ?>'>
        <br><br>
        A) <input type="text" name="alternativaA" value='<?php echo $altA; ?>'><br><br>
        B) <input type="text" name="alternativaB" value='<?php echo $altB; ?>'>
        <br><br>
        C) <input type="text" name="alternativaC" value='<?php echo $altC; ?>'>
        <br><br>
        D) <input type="text" name="alternativaD" value='<?php echo $altD; ?>'>
        <br><br>
        E) <input type="text" name="alternativaE" value='<?php echo $altE; ?>'> <br><br>
        Alternativa correta:<input type="text" name="alternativaCorreta" value='<?php echo $altCorretaID; ?>'>
        <br><br>

        <input type="submit" value="Salvar Alterações">

    </form>

</body>
</html>


