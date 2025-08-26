<?php

$arquivo = 'disciplinas.txt';
$msg = ''; // Para mostrar mensagens 


if (!file_exists($arquivo)) {
    $arq = fopen($arquivo, 'w'); 
    fwrite($arq, "nome;sigla;carga\n");
    fclose($arq);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pega os dados do formulário
    $nome = $_POST['nome'];
    $sigla = $_POST['sigla'];
    $carga = $_POST['carga'];

    // Se os campos não estiverem vazios
    if (!empty($nome) && !empty($sigla) && !empty($carga)) {
        $linha = "$nome;$sigla;$carga\n"; // Monta a linha para salvar

        $arq = fopen($arquivo, 'a'); // 'a' = modo de adição (escreve no final do arquivo)
        fwrite($arq, $linha);
        fclose($arq);

        $msg = 'Disciplina cadastrada com sucesso!';
    } else {
        $msg = 'Por favor, preencha todos os campos.';
    }
}


if (isset($_GET['excluir'])) {
    $id_para_excluir = $_GET['excluir'];

    $linhas = file($arquivo); // Lê todas as linhas do arquivo para um array

    // Remove a linha desejada
    unset($linhas[$id_para_excluir + 1]);

    // Reescreve o arquivo com as linhas restantes
    file_put_contents($arquivo, implode('', $linhas));

    $msg = 'Disciplina excluída com sucesso!';
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro Simples</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: auto; padding: 20px; }
        form { display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        .msg { font-weight: bold; color: green; }
    </style>
</head>
<body>

    <h1>Cadastro de Disciplinas</h1>

    <form action="disciplinas.php" method="POST">
        <input type="text" name="nome" placeholder="Nome da Disciplina" required>
        <input type="text" name="sigla" placeholder="Sigla" required>
        <input type="number" name="carga" placeholder="Carga Horária" required>
        <button type="submit">CADASTRAR</button>
    </form>

    <?php if ($msg): ?>
        <p class="msg"><?php echo $msg; ?></p>
    <?php endif; ?>

    <hr>

    <h2>Disciplinas Listadas</h2>
    <table>
        <tr>
            <th>Nome</th>
            <th>Sigla</th>
            <th>Carga</th>
            <th>Ação</th>
        </tr>
        <?php
        // 4. LÊ O ARQUIVO E LISTA OS DADOS NA TABELA
        $arq = fopen($arquivo, 'r'); 
        fgets($arq); 
        $id = 0;

        while (!feof($arq)) { // Enquanto não chegar ao fim do arquivo
            $linha = fgets($arq); // Lê uma linha
            if (trim($linha) != "") { // Ignora linhas em branco
                $dados = explode(';', $linha);
                list($nome, $sigla, $carga) = $dados;

                echo "<tr>";
                echo "<td>$nome</td>";
                echo "<td>$sigla</td>";
                echo "<td>$carga</td>";
                // Link para excluir, passando o ID da linha via URL (?excluir=ID)
                echo "<td><a href='?excluir=$id' onclick='return confirm(\"Tem certeza?\")'>Excluir</a></td>";
                echo "</tr>";
                $id++;
            }
        }
        fclose($arq);
        ?>
    </table>

</body>
</html>