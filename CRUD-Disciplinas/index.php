<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>CRUD de Disciplinas</title>
    <style> /* Estilo básico para a tabela */
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        thead { background-color: #f2f2f2; }
        .acoes a { margin-right: 10px; text-decoration: none; }
        .btn-novo { display: inline-block; margin-top: 20px; padding: 10px 15px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Gerenciador de Disciplinas</h1>
    <a href="inserir.php" class="btn-novo">Cadastrar Nova Disciplina</a>
    <table>
        <thead>
            <tr>
                <th>Nome da Disciplina</th>
                <th>Sigla</th>
                <th>Carga Horária</th>
                <th class="acoes">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $arquivo = 'disciplina.txt';
            if (file_exists($arquivo)) {
                $linhas = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                
                // Usamos o 'foreach' com '$id => $linha' para pegar o número da linha (que servirá como ID)
                foreach ($linhas as $id => $linha) {
                    list($nome, $sigla, $carga) = explode(';', $linha);
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($nome) . "</td>";
                    echo "<td>" . htmlspecialchars($sigla) . "</td>";
                    echo "<td>" . htmlspecialchars($carga) . "</td>";
                    // Links de Ação: Passamos o ID (número da linha) pela URL
                    echo "<td class='acoes'>
                            <a href='editar.php?id=$id'>Editar</a>
                            <a href='excluir.php?id=$id' onclick='return confirm(\"Tem certeza que deseja excluir esta disciplina?\");'>Excluir</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Nenhuma disciplina cadastrada.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>