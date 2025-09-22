

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Pergunta Múltipla Escolha</title>
        <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>
<body>

<section class="menu-container">
    <form action="" method="post">

        PERGUNTA:<input type="text" name="pergunta"><br>
        ID:<input type="text" name="ID">
        A)<input type="text" name="alternativaA"><br>
        B)<input type="text" name="alternativaB"><br>
        C)<input type="text" name="alternativaC"><br>
        D)<input type="text" name="alternativaD"><br>
        E)<input type="text" name="alternativaE"><br>
        Alternativa correta:<input type="text" name="alternativaCorreta">

        <input type="submit" value="Enviar">
    </form>
</section>
</body>
</html>
<?php

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nome_arquivo = "perguntas.txt";
    $pergunta = $_POST["pergunta"];
    $ID = $_POST["ID"];
    $altA = $_POST["alternativaA"];
    $altB = $_POST["alternativaB"];
    $altC = $_POST["alternativaC"];
    $altD = $_POST["alternativaD"];
    $altE = $_POST["alternativaE"];
    $alt_correta_id = $_POST["alternativaCorreta"];

    // Garante que os campos não estão vazios
    if (empty($pergunta) || empty($ID) || empty($altA) || empty($altB) || empty($altC) || empty($altD)|| empty($altE)  || empty($alt_correta_id)) {
        echo "Por favor, preencha todos os campos.";
        exit; 
    }
    


    $file = fopen($nome_arquivo, 'a') or die("Não foi possível abrir/criar arquivo");

    $linha = $ID . ";" . $pergunta . ";" . $altA . ";" . $altB . ";" . $altC . ";" . $altD . ";" . $altE . ";". $alt_correta_id . "\n";

    fwrite($file, $linha);
    fclose($file);
    echo "Pergunta Cadastrada";
}

?>