<?php

$servidor = "localhost";   
$usuario = "root";          
$senha = "";                
$banco = "bdd_prova_3daw";     

// Criar a conexão
$conexao = new mysqli($servidor, $usuario, $senha, $banco);

// Checar a conexão
if ($conexao->connect_error) {

    
    die("Falha na conexão: " . $conexao->connect_error);
}

// Define o charset para UTF-8 para evitar problemas com acentuação
$conexao->set_charset("utf8");

?>