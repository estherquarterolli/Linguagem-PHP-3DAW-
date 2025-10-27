<?php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

$msg = "";

// Carregar perguntas
$perguntas = [];
$fileName = "perguntas.txt";
if (file_exists($fileName)) {
    $file = fopen($fileName, 'r');
    while(!feof($file)){
        $linha = fgets($file);
        if(!empty(trim($linha))){
            $dados = explode(";", $linha);
            $dados = array_pad($dados, 8, '');
            
            // VERIFICAÇÃO CORRIGIDA para perguntas discursivas
            // Se todas as alternativas A-E estão vazias OU se a resposta correta é longa (discursiva)
            $ehDiscursiva = true;
            $alternativasPreenchidas = 0;
            
            for ($i = 2; $i <= 6; $i++) {
                if (!empty(trim($dados[$i]))) {
                    $alternativasPreenchidas++;
                    $ehDiscursiva = false;
                }
            }
            
            // Se tem menos de 2 alternativas preenchidas, considera como discursiva
            if ($alternativasPreenchidas < 2) {
                $ehDiscursiva = true;
            }
            
            $perguntas[] = [
                'ID' => trim($dados[0]),
                'pergunta' => $dados[1],
                'altA' => trim($dados[2]),
                'altB' => trim($dados[3]),
                'altC' => trim($dados[4]),
                'altD' => trim($dados[5]),
                'altE' => trim($dados[6]),
                'altCorreta' => trim($dados[7]),
                'tipo' => $ehDiscursiva ? 'discursiva' : 'multipla'
            ];
        }
    }
    fclose($file);
}

// DEBUG: Mostrar informações das perguntas
error_log("Total de perguntas: " . count($perguntas));
foreach ($perguntas as $p) {
    error_log("Pergunta ID: " . $p['ID'] . " - Tipo: " . $p['tipo'] . " - A: '" . $p['altA'] . "' B: '" . $p['altB'] . "'");
}

// Inicializar sessão
if (!isset($_SESSION['respostas'])) {
    $_SESSION['respostas'] = [];
    $_SESSION['pontuacao'] = 0;
}

// Processar envio do formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pontuacaoTemp = 0;
    
    foreach ($perguntas as $pergunta) {
        $perguntaId = $pergunta['ID'];
        
        if (isset($_POST['resposta_' . $perguntaId])) {
            $respostaUsuario = $_POST['resposta_' . $perguntaId];
            $_SESSION['respostas'][$perguntaId] = $respostaUsuario;
            
            if ($pergunta['tipo'] == 'multipla') {
                // Verificar resposta múltipla escolha
                $alternativas = ['A', 'B', 'C', 'D', 'E'];
                if (isset($alternativas[$respostaUsuario]) && $alternativas[$respostaUsuario] == $pergunta['altCorreta']) {
                    $pontuacaoTemp += 10;
                }
            } else {
                // Para discursivas, dar pontos por responder (se não estiver vazio)
                if (!empty(trim($respostaUsuario))) {
                    $pontuacaoTemp += 5;
                }
            }
        }
    }
    
    $_SESSION['pontuacao'] = $pontuacaoTemp;
    $msg = "Respostas salvas! Pontuação: " . $pontuacaoTemp . " pontos";
}

// Verificar se todas foram respondidas
$todasRespondidas = (count($_SESSION['respostas']) == count($perguntas) && count($perguntas) > 0);

// Reiniciar
if (isset($_GET['reiniciar'])) {
    $_SESSION['respostas'] = [];
    $_SESSION['pontuacao'] = 0;
    header('Location: responder_perguntas.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Responder Perguntas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Responder Perguntas</h1>
        
        <div class="progress-info">
            <div><strong>Jogador:</strong> <?php echo $_SESSION['usuario_logado']['nome']; ?></div>
            <div class="score">Pontuação: <?php echo $_SESSION['pontuacao']; ?> pontos</div>
            <div><strong>Progresso:</strong> <?php echo count($_SESSION['respostas']); ?>/<?php echo count($perguntas); ?></div>
        </div>
        
        <?php if (!empty($msg)): ?>
            <div class="alert success"><?php echo $msg; ?></div>
        <?php endif; ?>
        
        <?php if (count($perguntas) == 0): ?>
            <div class="alert error">
                <p>Nenhuma pergunta cadastrada.</p>
                <a href="menu.php" class="btn">Voltar ao Menu</a>
            </div>
        
        <?php elseif ($todasRespondidas): ?>
            <div class="alert success">
                <h2>Questionário Concluído!</h2>
                <p>Pontuação final: <?php echo $_SESSION['pontuacao']; ?> pontos</p>
                <a href="responder_perguntas.php?reiniciar=1" class="btn">Refazer Questionário</a>
                <a href="menu.php" class="btn">Voltar ao Menu</a>
            </div>
        
        <?php else: ?>
            <form method="post">
                <?php foreach ($perguntas as $index => $pergunta): ?>
                <div class="question-container">
                    <h3>Pergunta <?php echo ($index + 1); ?> - 
                        <span style="color: <?php echo $pergunta['tipo'] == 'multipla' ? '#3498db' : '#e67e22'; ?>">
                            <?php echo $pergunta['tipo'] == 'multipla' ? 'Múltipla Escolha' : 'Discursiva'; ?>
                        </span>
                    </h3>
                    
                    <p style="font-size: 1.1em; margin-bottom: 20px;"><strong><?php echo $pergunta['pergunta']; ?></strong></p>
                    
                    <?php if ($pergunta['tipo'] == 'multipla'): ?>
                        <div style="margin: 15px 0;">
                            <?php if (!empty($pergunta['altA'])): ?>
                            <label class="option-label">
                                <input type="radio" name="resposta_<?php echo $pergunta['ID']; ?>" value="0" 
                                    <?php echo (isset($_SESSION['respostas'][$pergunta['ID']]) && $_SESSION['respostas'][$pergunta['ID']] == '0') ? 'checked' : ''; ?>>
                                <strong>A)</strong> <?php echo $pergunta['altA']; ?>
                            </label>
                            <?php endif; ?>
                            
                            <?php if (!empty($pergunta['altB'])): ?>
                            <label class="option-label">
                                <input type="radio" name="resposta_<?php echo $pergunta['ID']; ?>" value="1"
                                    <?php echo (isset($_SESSION['respostas'][$pergunta['ID']]) && $_SESSION['respostas'][$pergunta['ID']] == '1') ? 'checked' : ''; ?>>
                                <strong>B)</strong> <?php echo $pergunta['altB']; ?>
                            </label>
                            <?php endif; ?>
                            
                            <?php if (!empty($pergunta['altC'])): ?>
                            <label class="option-label">
                                <input type="radio" name="resposta_<?php echo $pergunta['ID']; ?>" value="2"
                                    <?php echo (isset($_SESSION['respostas'][$pergunta['ID']]) && $_SESSION['respostas'][$pergunta['ID']] == '2') ? 'checked' : ''; ?>>
                                <strong>C)</strong> <?php echo $pergunta['altC']; ?>
                            </label>
                            <?php endif; ?>
                            
                            <?php if (!empty($pergunta['altD'])): ?>
                            <label class="option-label">
                                <input type="radio" name="resposta_<?php echo $pergunta['ID']; ?>" value="3"
                                    <?php echo (isset($_SESSION['respostas'][$pergunta['ID']]) && $_SESSION['respostas'][$pergunta['ID']] == '3') ? 'checked' : ''; ?>>
                                <strong>D)</strong> <?php echo $pergunta['altD']; ?>
                            </label>
                            <?php endif; ?>
                            
                            <?php if (!empty($pergunta['altE'])): ?>
                            <label class="option-label">
                                <input type="radio" name="resposta_<?php echo $pergunta['ID']; ?>" value="4"
                                    <?php echo (isset($_SESSION['respostas'][$pergunta['ID']]) && $_SESSION['respostas'][$pergunta['ID']] == '4') ? 'checked' : ''; ?>>
                                <strong>E)</strong> <?php echo $pergunta['altE']; ?>
                            </label>
                            <?php endif; ?>
                        </div>
                    
                    <?php else: ?>
                        <!-- PERGUNTA DISCURSIVA -->
                        <div style="margin: 15px 0;">
                            <textarea name="resposta_<?php echo $pergunta['ID']; ?>" 
                                      placeholder="Digite sua resposta discursiva aqui..." 
                                      rows="6"
                                      style="width: 100%; padding: 15px; border: 2px solid #ddd; border-radius: 6px; font-size: 1em; font-family: Arial, sans-serif;"><?php 
                                echo isset($_SESSION['respostas'][$pergunta['ID']]) ? $_SESSION['respostas'][$pergunta['ID']] : ''; 
                            ?></textarea>
                            <small style="color: #666; display: block; margin-top: 5px;">
                                Esta é uma pergunta discursiva. Escreva sua resposta detalhadamente.
                            </small>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['respostas'][$pergunta['ID']])): ?>
                        <div style="color: #27ae60; font-size: 0.9em; margin-top: 10px; padding: 5px; background: #f0fff0; border-radius: 3px;">
                            ✓ Respondida
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($index < count($perguntas) - 1): ?>
                    <hr style="margin: 30px 0; border: none; border-top: 2px dashed #ddd;">
                <?php endif; ?>
                
                <?php endforeach; ?>
                
                <div style="text-align: center; margin-top: 40px;">
                    <input type="submit" value="💾 Salvar Todas as Respostas" 
                           style="padding: 15px 40px; font-size: 1.2em; background: #27ae60; color: white; border: none; border-radius: 6px; cursor: pointer;">
                    <p style="color: #666; margin-top: 10px;">
                        Você pode salvar e continuar depois. As respostas ficarão guardadas.
                    </p>
                </div>
            </form>
            
            <?php if (count($_SESSION['respostas']) > 0): ?>
            <div style="text-align: center; margin-top: 20px;">
                <a href="responder_perguntas.php?reiniciar=1" 
                   onclick="return confirm('Deseja reiniciar o questionário? Todas as respostas serão perdidas.')"
                   style="color: #e74c3c; text-decoration: none; font-weight: bold;">🔄 Reiniciar Questionário</a>
            </div>
            <?php endif; ?>
            
        <?php endif; ?>
        
        <div class="menu">
            <a href="menu.php">← Voltar ao Menu</a>
        </div>
        
        <!-- DEBUG: Mostrar informações das perguntas -->
        <div style="margin-top: 40px; padding: 15px; background: #f8f9fa; border-radius: 5px; font-size: 0.9em;">
            <h4>Informações de Debug:</h4>
            <p>Total de perguntas carregadas: <?php echo count($perguntas); ?></p>
            <p>Perguntas respondidas: <?php echo count($_SESSION['respostas']); ?></p>
            <?php foreach ($perguntas as $p): ?>
                <p>ID: <?php echo $p['ID']; ?> - Tipo: <?php echo $p['tipo']; ?> - Alternativas: 
                A[<?php echo strlen($p['altA']); ?>] B[<?php echo strlen($p['altB']); ?>] 
                C[<?php echo strlen($p['altC']); ?>] D[<?php echo strlen($p['altD']); ?>] 
                E[<?php echo strlen($p['altE']); ?>]</p>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>