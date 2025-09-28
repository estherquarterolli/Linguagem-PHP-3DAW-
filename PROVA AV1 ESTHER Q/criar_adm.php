<?php
$fileName = "usuarios.txt";

if (!file_exists($fileName)) {
    $ID = "1";
    $nome = "Administrador";
    $email = "admin@sisgame.com";
    $senha = "admin123";
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    $tipo = "admin";
    
    $linha = "$ID;$nome;$email;$senhaHash;$tipo\n";
    
    $file = fopen($fileName, "w") or die("Não foi possível criar o arquivo.");
    fwrite($file, $linha);
    fclose($file);
    
    echo "<!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Admin Criado</title>
        <link rel='stylesheet' href='style.css'>
    </head>
    <body>
        <div class='login-container'>
            <div class='login-box text-center'>
                <h1>Usuário Admin Criado!</h1>
                <div class='alert success'>
                    <p><strong>Email:</strong> admin@sisgame.com</p>
                    <p><strong>Senha:</strong> admin123</p>
                </div>
                <a href='login.php' class='btn'>Fazer Login</a>
            </div>
        </div>
    </body>
    </html>";
} else {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Sistema Inicializado</title>
        <link rel='stylesheet' href='style.css'>
    </head>
    <body>
        <div class='login-container'>
            <div class='login-box text-center'>
                <h1>Sistema Já Inicializado</h1>
                <div class='alert info'>
                    <p>O sistema já foi inicializado anteriormente.</p>
                </div>
                <a href='login.php' class='btn'>Fazer Login</a>
            </div>
        </div>
    </body>
    </html>";
}
?>