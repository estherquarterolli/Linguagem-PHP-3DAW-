<?php
// Verifica se o ID foi passado pela URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $arquivo = 'disciplina.txt';

    // Lê todas as linhas para um array
    $linhas = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Verifica se a linha (ID) a ser excluída realmente existe
    if (isset($linhas[$id])) {
        // Remove o item do array na posição do ID
        unset($linhas[$id]);
        
        // Junta o array de volta em uma string
        $conteudo_final = implode("\n", $linhas) . "\n";
        
        // Salva o conteúdo de volta no arquivo
        file_put_contents($arquivo, $conteudo_final, LOCK_EX);
    }
}

// Redireciona de volta para a página principal
header("Location: index.php");
exit();
?>