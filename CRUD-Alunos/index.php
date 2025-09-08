<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>CRUD de Alunos</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        thead { background-color: #f2f2f2; }
        .acoes a { margin-right: 10px; text-decoration: none; }
        .btn-novo { display: inline-block; margin-top: 20px; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Gerenciador de Alunos</h1>
    <a href="inserir.php" class="btn-novo">Cadastrar Novo Aluno</a>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Email</th>
                <th>Matéria</th>
                <th>Data de Ingresso</th>
                <th>Turno</th>
                <th class="acoes">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $arquivo = 'alunos.txt';
            if (file_exists($arquivo) && filesize($arquivo) > 0) {
                $linhas = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                
                foreach ($linhas as $id => $linha) {
                    // Separa a linha em 6 partes
                    list($nome, $cpf, $email, $materia, $data_ingresso, $turno) = explode(';', $linha);
                    
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($nome) . "</td>";
                    echo "<td>" . htmlspecialchars($cpf) . "</td>";
                    echo "<td>" . htmlspecialchars($email) . "</td>";
                    echo "<td>" . htmlspecialchars($materia) . "</td>";
                    echo "<td>" . htmlspecialchars($data_ingresso) . "</td>";
                    echo "<td>" . htmlspecialchars($turno) . "</td>";
                    echo "<td class='acoes'>
                            <a href='editar.php?id=$id'>Editar</a>
                            <a href='excluir.php?id=$id' onclick='return confirm(\"Tem certeza que deseja excluir este aluno?\");'>Excluir</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Nenhum aluno cadastrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>