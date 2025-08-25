<?php

$num1 = (float)$_POST['valor1'] ?? '';
$num2 = (float)$_POST['valor2'] ?? '';
$operacao = $_POST['operacao'];
$resultado = null; 

// O código só roda se o formulário for enviado (método POST).
if ($_SERVER["REQUEST_METHOD"] == "POST") {


        // Lógica da calculadora.
        switch ($operacao) {
            case 'soma':
                $resultado = $num1 + $num2;
                break;
            case 'subtracao':
                $resultado = $num1 - $num2;
                break;
            case 'multiplicacao':
                $resultado = $num1 * $num2;
                break;
            case 'divisao':
                if ($num2 != 0) {
                    $resultado = $num1 / $num2;
                } else {
                    $resultado = "Erro: Divisão por zero!";
                }
                break;
        }
    } else {
        
        $resultado = "Preencha ambos os valores com números.";
    }

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora DAW</title>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap');

        :root {
            --cor-fundo: #222831;
            --cor-calculadora: #393E46;
            --cor-input: #4a515c;
            --cor-botao: #FFD369;
            --cor-texto-principal: #EEEEEE;
            --cor-texto-botao: #222831;
            --cor-resultado: #222831;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--cor-fundo);
            color: var(--cor-texto-principal);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .calculadora {
            width: 340px;
            padding: 25px;
            border-radius: 15px;
            background-color: var(--cor-calculadora);
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.5);
        }
        
        h1 {
            text-align: center;
            margin-top: 0;
            color: var(--cor-botao);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px; /* Espaçamento entre os elementos do formulário */
        }

        label {
            font-size: 1rem;
            margin-bottom: -10px;
        }

        input[type="number"], select {
            width: 100%;
            padding: 12px;

            border: none;
            border-radius: 8px;
            background-color: var(--cor-input);
            color: var(--cor-texto-principal);
            font-size: 1.2rem;
            box-sizing: border-box;
            box-shadow: inset 0px 2px 5px rgba(0, 0, 0, 0.2);
        }

        button {
            padding: 15px;
            border: none;
            border-radius: 8px;
            background-color: var(--cor-botao);
            color: var(--cor-texto-botao);
            font-size: 1.2rem;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.1s, box-shadow 0.2s;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        }
        
        button:hover {
            filter: brightness(1.1);
        }

        button:active {
            transform: translateY(2px);
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        /* Caixa para exibir o resultado */
        .resultado-box {
            margin-top: 20px;
            padding: 20px;
            border-radius: 8px;
            background-color: var(--cor-resultado);
            text-align: center;
            box-shadow: inset 0px 4px 10px rgba(0, 0, 0, 0.5);
        }
        
        .resultado-box span {
            font-size: 2.5rem;
            font-weight: 500;
            color: var(--cor-texto-principal);
        }
        
        .resultado-box p {
             margin: 0;
             color: #aaa;
        }

    </style>
</head>
<body>

   <div class="calculadora">
        <h1>Calculadora PHP</h1>
        
        <form method="post">
            <label for="valor1">Primeiro valor:</label>
            <input type="number" step="any" name="valor1" id="valor1" required value="<?= htmlspecialchars($valor1) ?>">
            
            <label for="operacao">Operação:</label>
            <select name="operacao" id="operacao">
                <option value="soma" <?php if ($operacao == 'soma')  ?>>Somar (+)</option>
                <option value="subtracao" <?php if ($operacao == 'subtracao')  ?>>Subtrair (-)</option>
                <option value="multiplicacao" <?php if ($operacao == 'multiplicacao') ?>>Multiplicar (×)</option>
                <option value="divisao" <?php if ($operacao == 'divisao')  ?>>Dividir (÷)</option>
            </select>
            
            <label for="valor2">Segundo valor:</label>
            <input type="number" step="any" name="valor2" id="valor2" required value="<?= htmlspecialchars($valor2) ?>">
            
            <button type="submit">Calcular</button>
        </form>

        <?php
      
        if ($resultado !== null):
        ?>
            <div class="resultado-box">
                <p>Resultado</p>
                <span><?= htmlspecialchars($resultado) ?></span>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>