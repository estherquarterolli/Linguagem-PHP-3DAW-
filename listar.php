<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Disciplinas</title>
    <style>
        body {
            font-family: sans-serif;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        thead {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Disciplinas Cadastradas</h1>

    <?php
   
    $arquivo = 'disciplina.txt';

   
    if (file_exists($arquivo)) {
        
        // 2. Lê todas as linhas do arquivo para um array (cada linha vira um item no array)
        // A opção FILE_IGNORE_NEW_LINES já remove o "\n" do final de cada linha
        $linhas = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

       
        if (!empty($linhas)) {
            
            echo '<table>';
            echo '<thead>
                    <tr>
                        <th>Nome da Disciplina</th>
                        <th>Sigla</th>
                        <th>Carga Horária</th>
                    </tr>
                  </thead>';
            echo '<tbody>';

           
            foreach ($linhas as $linha) {
               
                list($nome, $sigla, $carga) = explode(';', $linha);

                
                echo '<tr>';
                // htmlspecialchars é uma boa prática para evitar problemas de segurança ao exibir dados
                echo '<td>' . htmlspecialchars($nome) . '</td>';
                echo '<td>' . htmlspecialchars($sigla) . '</td>';
                echo '<td>' . htmlspecialchars($carga) . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';

        } else {
            echo '<p>Nenhuma disciplina cadastrada no arquivo.</p>';
        }

    } else {
        // Mensagem de erro se o arquivo 'disciplina.txt' não for encontrado
        echo '<p>O arquivo de disciplinas ainda não foi criado. Adicione a primeira disciplina para criá-lo.</p>';
    }
    ?>

</body>
</html>