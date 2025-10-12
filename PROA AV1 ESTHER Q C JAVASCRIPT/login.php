<?php
session_start();
if (isset($_SESSION['usuario_logado'])) {
    header('Location: menu.php');
    exit();
}

$erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    $fileName = "usuarios.txt";
    
    if (!file_exists($fileName)) {
        $erro = "Sistema não inicializado. <a href='criar_adm.php'>Clique aqui para inicializar</a>";
    } else {
        $file = fopen($fileName, "r");
        $loginValido = false;
        $usuarioDados = null;
        
        while (!feof($file)) {
            $linha = fgets($file);
            if (trim($linha) == "") continue;
            
            $dados = explode(";", $linha);
            if (count($dados) >= 5 && trim($dados[2]) == $email) {
                if (password_verify($senha, trim($dados[3]))) {
                    $usuarioDados = [
                        'id' => $dados[0],
                        'nome' => $dados[1],
                        'email' => $dados[2],
                        'tipo' => trim($dados[4])
                    ];
                    $loginValido = true;
                    break;
                }
            }
        }
        fclose($file);
        
        if ($loginValido) {
            $_SESSION['usuario_logado'] = $usuarioDados;
            header('Location: menu.php');
            exit();
        } else {
            $erro = "Email ou senha incorretos!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-bg">
    <div class="login-box">
        <h1>Login</h1>
        
        <?php if (!empty($erro)): ?>
            <div class="alert error"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <input type="submit" value="Entrar">
        </form>
        
        <p style="text-align: center; margin-top: 15px;">
            <a href="criar_adm.php">Primeiro acesso? Inicializar sistema</a>
        </p>
    </div>
</body>
</html>