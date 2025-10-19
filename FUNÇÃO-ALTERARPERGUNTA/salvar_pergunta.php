<?php
header('Content-Type: application/json');

$arquivo_txt = 'perguntas.txt';

// Verifica se todos os dados necessários foram enviados
if (!isset($_POST['codigo']) || !isset($_POST['texto'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incompletos para salvar.']);
    exit;
}

$codigo = strtoupper(trim($_POST['codigo']));
$novo_texto = trim($_POST['texto']);
$sucesso_salvar = false;

if (empty($novo_texto)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'O texto da pergunta não pode ser vazio.']);
    exit;
}

if (!file_exists($arquivo_txt)) {
    echo json_encode(['sucesso' => false, 'mensagem' => "Arquivo de dados {$arquivo_txt} não encontrado."]);
    exit;
}

// 1. Lógica para ler, alterar e salvar
$linhas = file($arquivo_txt, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$novo_conteudo = [];
$linha_modificada = "{$codigo};{$novo_texto}";

foreach ($linhas as $linha) {
    // Pega o código no início da linha (antes do primeiro ';')
    $codigo_atual = substr($linha, 0, strpos($linha, ';')); 

    // Verifica se a linha atual corresponde ao código a ser alterado
    if ($codigo_atual === $codigo) {
        // Adiciona a nova linha modificada
        $novo_conteudo[] = $linha_modificada;
        $sucesso_salvar = true;
    } else {
        // Mantém a linha original
        $novo_conteudo[] = $linha;
    }
}

if ($sucesso_salvar) {
    // Junta o array de linhas de volta em uma string, separadas por quebras de linha
    $texto_final_salvar = implode(PHP_EOL, $novo_conteudo);
    
    // Escreve o novo conteúdo de volta no arquivo, sobrescrevendo o antigo
    // Você precisa garantir que o PHP tem permissão de escrita neste arquivo/diretório
    if (file_put_contents($arquivo_txt, $texto_final_salvar) !== false) {
        echo json_encode([
            'sucesso' => true,
            'mensagem' => "Pergunta '{$codigo}' alterada com sucesso no TXT para: \"{$novo_texto}\""
        ]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => "Falha na escrita do arquivo. Verifique as permissões de escrita de '{$arquivo_txt}'."]);
    }
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => "Falha ao salvar. Pergunta com código '{$codigo}' não existe no TXT."]);
}
?>