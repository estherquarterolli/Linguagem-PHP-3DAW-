<?php

$arquivo = 'disciplina.txt';


if (!file_exists($arquivo)) {
    
    $arqDisc = fopen($arquivo, 'w'); 
    fclose($arqDisc);
}

// Pega a ação da URL, o padrão é 'listar'
$acao = $_GET['acao'] ?? 'listar';
$id = $_GET['id'] ?? null;

// AÇÃO: Salvar (seja criando um novo ou atualizando um existente)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $sigla = $_POST['sigla'];
    $carga = $_POST['carga'];
    $id_post = $_POST['id'] ?? null; // Pega o ID se estiver editando

    if (!empty($nome) && !empty($sigla) && !empty($carga)) {
        $nova_linha = $nome . ";" . $sigla . ";" . $carga . "\n";
        
        $linhas = file($arquivo, FILE_IGNORE_NEW_LINES);

        if ($id_post !== null && isset($linhas[$id_post])) {
            // Modo de Atualização
            $linhas[$id_post] = trim($nova_linha);
        } else {
            // Modo de Criação
            $linhas[] = trim($nova_linha);
        }

        $arqDisc = fopen($arquivo, "w");
        foreach ($linhas as $linha) {
            fwrite($arqDisc, $linha . "\n");
        }
        fclose($arqDisc);
    }
    
    header("Location: " . basename(__FILE__));
    exit();
}

// AÇÃO: Excluir
if ($acao === 'excluir' && $id !== null) {
    $linhas = file($arquivo, FILE_IGNORE_NEW_LINES);
    
    if (isset($linhas[$id])) {
        unset($linhas[$id]); 
        
        $arqDisc = fopen($arquivo, "w");
        foreach ($linhas as $linha) {
            fwrite($arqDisc, $linha . "\n");
        }
        fclose($arqDisc);
    }
    
    header("Location: " . basename(__FILE__));
    exit();
}


?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>CRUD de Disciplinas - Página Única</title>
</head>
<body>

    <h1>Gerenciar Disciplinas</h1>

    <?php
    // Se a ação for 'editar', mostra o formulário de edição
    if ($acao === 'editar' && $id !== null):
        $linhas = file($arquivo, FILE_IGNORE_NEW_LINES);
        if (isset($linhas[$id])):
            list($nome_atual, $sigla_atual, $carga_atual) = explode(";", $linhas[$id]);
    ?>
    
    <hr>
    <h3>Editando Disciplina</h3>
    <form action="<?php echo basename(__FILE__); ?>" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        
        <label>Nome:</label>
        <input type="text" name="nome" value="<?php echo $nome_atual; ?>" required>
        <label>Sigla:</label>
        <input type="text" name="sigla" value="<?php echo $sigla_atual; ?>" required>
        <label>Carga:</label>
        <input type="text" name="carga" value="<?php echo $carga_atual; ?>" required>
        <button type="submit">Atualizar</button>
        <a href="<?php echo basename( __FILE__); ?>">Cancelar Edição</a>
    </form>
    <hr>

    <?php 
        endif;
    endif; 
    ?>

    <h3>Adicionar Nova Disciplina</h3>
    <form action="<?php echo basename(__FILE__); ?>" method="POST">
        <label>Nome:</label>
        <input type="text" name="nome" required>
        <label>Sigla:</label>
        <input type="text" name="sigla" required>
        <label>Carga:</label>
        <input type="text" name="carga" required>
        <button type="submit">Adicionar</button>
    </form>

    <br>

    <h2>Disciplinas Cadastradas</h2>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Sigla</th>
                <th>Carga Horária</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (filesize($arquivo) > 0) {
                $arqDisc = fopen($arquivo, "r");
                $id_linha = 0;
                
                while (($linha = fgets($arqDisc)) !== false) {
                    $dados = explode(";", trim($linha));
                    if (count($dados) >= 3) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($dados[0]) . "</td>";
                        echo "<td>" . htmlspecialchars($dados[1]) . "</td>";
                        echo "<td>" . htmlspecialchars($dados[2]) . "</td>";
                        echo "<td>
                                <a href='?acao=editar&id=$id_linha'>Editar</a> |
                                <a href='?acao=excluir&id=$id_linha' onclick='return confirm(\"Tem certeza?\")'>Excluir</a>
                              </td>";
                        echo "</tr>";
                    }
                    $id_linha++;
                }
                fclose($arqDisc);
            } else {
                echo "<tr><td colspan='4'>Nenhuma disciplina cadastrada.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>