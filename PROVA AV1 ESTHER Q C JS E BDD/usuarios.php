<?php
// usuarios.php
session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo'];
    
    $fileName = "usuarios.txt";
    $tempFile = "usuariostemp.txt";
    
    if ($_POST['acao'] == 'criar') {
        // Verificar se email já existe
        $emailExiste = false;
        if (file_exists($fileName)) {
            $file = fopen($fileName, "r");
            while (!feof($file)) {
                $linha = fgets($file);
                if (trim($linha) == "") continue;
                $dados = explode(";", $linha);
                if (count($dados) >= 3 && trim($dados[1]) == $email) {
                    $emailExiste = true;
                    break;
                }
            }
            fclose($file);
        }
        
        if (!$emailExiste) {
            $ID = time();
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $linha = "$ID;$nome;$email;$senhaHash;$tipo\n";
            
            $file = fopen($fileName, "a") or die("Não foi possível abrir o arquivo.");
            fwrite($file, $linha);
            fclose($file);
            $msg = "Usuário criado com sucesso!";
        } else {
            $msg = "Erro: Email já cadastrado!";
        }
    }
    
    if ($_POST['acao'] == 'editar') {
        $ID = $_POST['ID'];
        
        if (file_exists($fileName)) {
            $file = fopen($fileName, "r");
            $temp = fopen($tempFile, "w");
            
            while (!feof($file)) {
                $linha = fgets($file);
                if (trim($linha) == "") continue;
                
                $dados = explode(";", $linha);
                if (count($dados) >= 5) {
                    if (trim($dados[0]) == $ID) {
                        $senhaHash = !empty($senha) ? password_hash($senha, PASSWORD_DEFAULT) : trim($dados[3]);
                        $novaLinha = "$ID;$nome;$email;$senhaHash;$tipo\n";
                        fwrite($temp, $novaLinha);
                    } else {
                        fwrite($temp, $linha);
                    }
                }
            }
            
            fclose($file);
            fclose($temp);
            
            if (rename($tempFile, $fileName)) {
                $msg = "Usuário atualizado com sucesso!";
            } else {
                $msg = "Erro ao atualizar usuário.";
            }
        }
    }
}

if (isset($_GET['excluir'])) {
    $ID = $_GET['excluir'];
    $fileName = "usuarios.txt";
    $tempFile = "usuariostemp.txt";
    
    if (file_exists($fileName)) {
        $file = fopen($fileName, "r");
        $temp = fopen($tempFile, "w");
        $encontrou = false;
        
        while (!feof($file)) {
            $linha = fgets($file);
            if (trim($linha) == "") continue;
            
            $dados = explode(";", $linha);
            if (count($dados) >= 5) {
                if (trim($dados[0]) == $ID) {
                    $encontrou = true;
                } else {
                    fwrite($temp, $linha);
                }
            }
        }
        
        fclose($file);
        fclose($temp);
        
        if ($encontrou) {
            if (rename($tempFile, $fileName)) {
                $msg = "Usuário excluído com sucesso!";
            } else {
                $msg = "Erro ao excluir usuário.";
            }
        } else {
            unlink($tempFile);
            $msg = "Usuário não encontrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários</title>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>
<body>
    <section class="menu-container">
        <h1>Gerenciar Usuários</h1>
        
        <?php if (!empty($msg)): ?>
            <p><?php echo $msg; ?></p>
        <?php endif; ?>
        
        <section class="menu">
            <a href="usuarios.php?acao=listar">Listar Usuários</a>
            <a href="usuarios.php?acao=criar">Criar Usuário</a>
            <a href="menu.php">Voltar ao Menu</a>
        </section>
        
        <?php
        $acao = isset($_GET['acao']) ? $_GET['acao'] : 'listar';
        
        if ($acao == 'criar' || $acao == 'editar'): 
            $nome = $email = $tipo = '';
            $ID = '';
            
            if ($acao == 'editar' && isset($_GET['ID'])) {
                $ID = $_GET['ID'];
                $fileName = "usuarios.txt";
                
                if (file_exists($fileName)) {
                    $file = fopen($fileName, "r");
                    while (!feof($file)) {
                        $linha = fgets($file);
                        if (trim($linha) == "") continue;
                        
                        $dados = explode(";", $linha);
                        if (count($dados) >= 5 && trim($dados[0]) == $ID) {
                            $nome = $dados[1];
                            $email = $dados[2];
                            $tipo = trim($dados[4]);
                            break;
                        }
                    }
                    fclose($file);
                }
            }
        ?>
        
            <h2><?php echo $acao == 'criar' ? 'Criar Usuário' : 'Editar Usuário'; ?></h2>
            
            <form method="post">
                <input type="hidden" name="acao" value="<?php echo $acao; ?>">
                <?php if ($acao == 'editar'): ?>
                    <input type="hidden" name="ID" value="<?php echo $ID; ?>">
                <?php endif; ?>
                
                Nome: <input type="text" name="nome" value="<?php echo $nome; ?>" required><br><br>
                Email: <input type="email" name="email" value="<?php echo $email; ?>" required><br><br>
                Senha: <input type="password" name="senha" <?php echo $acao == 'criar' ? 'required' : 'placeholder="Deixe em branco para manter"'; ?>><br><br>
                Tipo: 
                <select name="tipo" required>
                    <option value="usuario" <?php echo $tipo == 'usuario' ? 'selected' : ''; ?>>Usuário</option>
                    <option value="admin" <?php echo $tipo == 'admin' ? 'selected' : ''; ?>>Administrador</option>
                </select><br><br>
                
                <input type="submit" value="<?php echo $acao == 'criar' ? 'Criar' : 'Atualizar'; ?>">
                <a href="usuarios.php">Cancelar</a>
            </form>
            
        <?php else: ?>
        
            <h2>Lista de Usuários</h2>
            <table border="1" style="width: 100%;">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Ações</th>
                </tr>
                <?php
                $fileName = "usuarios.txt";
                if (file_exists($fileName)) {
                    $file = fopen($fileName, "r");
                    
                    while (!feof($file)) {
                        $linha = fgets($file);
                        if (trim($linha) == "") continue;
                        
                        $dados = explode(";", $linha);
                        if (count($dados) >= 5) {
                            echo "<tr>
                                <td>" . $dados[0] . "</td>
                                <td>" . $dados[1] . "</td>
                                <td>" . $dados[2] . "</td>
                                <td>" . trim($dados[4]) . "</td>
                                <td>
                                    <a href='usuarios.php?acao=editar&ID=" . $dados[0] . "'>Editar</a> | 
                                    <a href='usuarios.php?excluir=" . $dados[0] . "' onclick='return confirm(\"Tem certeza?\")'>Excluir</a>
                                </td>
                            </tr>";
                        }
                    }
                    fclose($file);
                }
                ?>
            </table>
            
        <?php endif; ?>
    </section>
</body>
</html>