<?php

define('DB_SERVER', 'localhost'); 
define('DB_USERNAME', 'root');    
define('DB_PASSWORD', '');        
define('DB_NAME', 'crudaluno');  


$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


if ($conn->connect_error) {
    die("ERRO de Conexão com o Banco de Dados: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>