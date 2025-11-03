<?php

include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'create') {
        //INCLUIR NOVO ALUNO
        $nome = $conn->real_escape_string($_POST['nome']);
        $matricula = $conn->real_escape_string($_POST['matricula']);
        $email = $conn->real_escape_string($_POST['email']);
        
        // Usando Prepared Statements para segurança
        $sql = "INSERT INTO alunos (matricula, nome, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $matricula, $nome, $email);

        if ($stmt->execute()) {
            header("Location: index.php?msg=Aluno cadastrado com sucesso!");
        } else {
            // Tratamento de erro (ex: matrícula/email duplicado)
            header("Location: index.php?msg=ERRO: Não foi possível cadastrar o aluno. " . $stmt->error);
        }
        $stmt->close();
        
    } elseif ($action == 'update' && isset($_POST['id'])) {
        //ALTERAR ALUNO
        $id = $conn->real_escape_string($_POST['id']);
        $nome = $conn->real_escape_string($_POST['nome']);
        $matricula = $conn->real_escape_string($_POST['matricula']);
        $email = $conn->real_escape_string($_POST['email']);

        $sql = "UPDATE alunos SET matricula = ?, nome = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $matricula, $nome, $email, $id);

        if ($stmt->execute()) {
            header("Location: index.php?msg=Aluno ID " . $id . " alterado com sucesso!");
        } else {
             header("Location: index.php?msg=ERRO: Não foi possível alterar o aluno. " . $stmt->error);
        }
        $stmt->close();
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    // EXCLUIR ALUNO
    $id = $conn->real_escape_string($_GET['id']);
    
    $sql = "DELETE FROM alunos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php?msg=Aluno ID " . $id . " excluído com sucesso!");
    } else {
        header("Location: index.php?msg=ERRO: Não foi possível excluir o aluno. " . $stmt->error);
    }
    $stmt->close();
    
} else {
    // Redireciona para o index em caso de acesso direto ou ação inválida
    header("Location: index.php");
}

$conn->close(); 
exit();
?>