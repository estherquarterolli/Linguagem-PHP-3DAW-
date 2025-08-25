<?php
$arquivo = 'disciplina.txt';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $sigla = $_POST['sigla'];
    $carga = $_POST['carga'];

    if (!empty($nome) && !empty($sigla) && !empty($carga)) {
        // CORREÇÃO 1: Faltavam os pontos (.) para concatenar (juntar) as strings.
        $linha = $nome . ";" . $sigla . ";" . $carga . "\n";

        if (!file_exists($arquivo)) {
            $arqDisc = fopen($arquivo, "w");
            fwrite($arqDisc, $linha);
            fclose($arqDisc);
            $msg = "Arquivo criado e disciplina inserida";
        } else {
            $arqDisc = fopen($arquivo, "a");
            fwrite($arqDisc, $linha);
            fclose($arqDisc);
            $msg = "Disciplina inserida";
        }
    } else {
        // CORREÇÃO 2: A atribuição de valor é com o sinal de igual (=) e não com dois pontos (:).
        $msg = "TODOS OS CAMPOS SÃO OBRIGATÓRIOS!!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1> Inserir no Arquivo Disciplina</h1>
    <form action="" method="POST">
        <label>Nome:</label>
        <input type="text" name="nome">
        <label>Sigla:</label>
        <input type="text" name="sigla">
        <label>Carga:</label>
        <input type="text" name="carga">
        <button type="submit">ENVIAR</button>
    </form>
    
    <?php 
    // CORREÇÃO 5: Adicionado o código para mostrar a mensagem de sucesso ou erro na tela.
    if (!empty($msg)) {
        echo "<p>$msg</p>";
    } 
    ?>
</body>
</html>