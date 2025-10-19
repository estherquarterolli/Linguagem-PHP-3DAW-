<?php
header('Content-Type: application/json');

$arquivo_txt = 'perguntas.txt';

// Verifica se o código foi enviado via POST
if (!isset($_POST['codigo']) || empty($_POST['codigo'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Código da pergunta não fornecido.']);
    exit;
}

$codigo_busca = strtoupper(trim($_POST['codigo']));
$pergunta_encontrada = null;

if (file_exists($arquivo_txt)) {
    // Lê todas as linhas do arquivo em um array
    $linhas = file($arquivo_txt, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($linhas as $linha) {
        // Divide a linha em código e texto, usando ';' como delimitador
        $partes = explode(';', $linha, 2); 
        
        // Verifica se a linha tem o formato esperado
        if (count($partes) === 2) {
            $codigo_atual = trim($partes[0]);
            $texto_atual = trim($partes[1]);
            
            // Se o código for encontrado, armazena o texto e para
            if ($codigo_atual === $codigo_busca) {
                $pergunta_encontrada = $texto_atual;
                break;
            }
        }
    }
    
    if ($pergunta_encontrada !== null) {
        // Retorna sucesso e o texto da pergunta
        echo json_encode([
            'sucesso' => true,
            'texto' => $pergunta_encontrada
        ]);
    } else {
        // Retorna erro se não encontrar
        echo json_encode(['sucesso' => false, 'mensagem' => "Pergunta com código '{$codigo_busca}' não encontrada no TXT."]);
    }
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => "Arquivo de dados {$arquivo_txt} não encontrado."]);
}
?>