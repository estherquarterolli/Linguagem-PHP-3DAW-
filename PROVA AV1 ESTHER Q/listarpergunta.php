<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar perguntas</title>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>
<body>
     <div class="menu-container">
                
        <h1>Listar perguntas</h1>
        <table>

            <tr><th>ID</th><th>Pergunta</th><th>alternativa A</th><th>Alternativa B</th><th>Alternativa C</th><th>alternativa D</th><th>alternativa E</th><th>Gabarito</th></tr>

            <?php
         
            $file = fopen("perguntas.txt", 'r') or die("Não foi possível abrir o arquivo");
            
       
            while(!feof($file)){

                $linha = fgets($file); 
              
                if(!empty(trim($linha))){
                    $colunaDados = explode(";", $linha); 
                   
                    if(count($colunaDados) >= 7){

                        echo "<tr><td>" . $colunaDados[0] . "</td>" . 
                        "<td>" . $colunaDados[1] . "</td>" . 
                        "<td>" . $colunaDados[2] . "</td>" . 
                        "<td>" . $colunaDados[3] . "</td>" . 
                        "<td>" . $colunaDados[4] . "</td>" . 
                        "<td>" . $colunaDados[5] . "</td>"  .  
                        "<td>" . $colunaDados[6] . "</td>"  .                        
                        "<td>" . $colunaDados[7] . "</td><tr>"; 
                    }
                }                
            }
            fclose($file);
            ?>
            
        </table>
    </div>
</body>
</html>
     </div>
</body>
</html>