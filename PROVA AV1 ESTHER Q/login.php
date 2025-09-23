<?php
// login.php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    $fileName = "usuarios.txt";
    
    if (file_exists($fileName)) {
        $file = fopen($fileName, "r");
        $loginValido = false;
        
        while (!feof($file)) {
            $linha = fgets($file);
            if (trim($linha) == "") continue;
            
            $dados = explode(";", $linha);
            if (count($dados) >= 5 && trim($dados[2]) == $email) {
                if (password_verify($senha, trim($dados[3]))) {
                    $_SESSION['usuario_logado'] = [
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
            header('Location: menu.php');
            exit();
        } else {
            $erro = "Email ou senha incorretos!";
        }
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>
<body>
    <section class="menu-container">
        <h1>Login</h1>
        
        <?php if (isset($erro)): ?>
            <p style="color: red;"><?php echo $erro; ?></p>
        <?php endif; ?>
        
        <form method="post">
            Email: <input type="email" name="email" required><br><br>
            Senha: <input type="password" name="senha" required><br><br>
            
            <input type="submit" value="Entrar">
        </form>
        
        <br>
        <a href="usuarios.php?acao=criar">Criar nova conta</a>
    </section>
</body>
</html>