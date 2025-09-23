<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Pergunta Dissertativa</title>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>
<body>

<section class="menu-container">
    <form action="" method="post">
        PERGUNTA: <input type="text" name="pergunta"><br>
        ID: <input type="text" name="ID"><br>
        Alternativa correta: 
        <textarea name="alternativaCorreta">Insira aqui sua resposta</textarea><br>

        <input type="submit" value="Enviar">
    </form>
</section>

<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nome_arquivo = "perguntas.txt";
    $pergunta = $_POST["pergunta"];
    $ID = $_POST["ID"];
    $alt_correta_id = $_POST["alternativaCorreta"];

    // Para perguntas dissertativas, as alternativas A-E ficam vazias
    $altA = "";
    $altB = "";
    $altC = "";
    $altD = "";
    $altE = "";

    if (empty($pergunta) || empty($ID) || empty($alt_correta_id)) {
        $texto= "Por favor, preencha todos os campos.";
        echo "<p class='texto'>$texto</p>";
        exit; 
    }

    $file = fopen($nome_arquivo, 'a') or die("Não foi possível abrir/criar arquivo");

    $linha = $ID . ";" . $pergunta . ";" . $altA . ";" . $altB . ";" . $altC . ";" . $altD . ";" . $altE . ";". $alt_correta_id . "\n";

    fwrite($file, $linha);
    fclose($file);
    echo "<p>Pergunta Dissertativa Cadastrada</p>";
}
?>
</body>
</html>