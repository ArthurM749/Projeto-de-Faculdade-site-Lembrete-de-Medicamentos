<?php
require_once 'includes/config.php';

$erroCadastro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['confirmar_senha'])) {
    if ($_POST['senha'] !== $_POST['confirmar_senha']) {
        $erroCadastro = "As senhas não coincidem!";
    } else {
        $db = conectarDB();
        $stmt = $db->prepare('SELECT id FROM usuarios WHERE email = :email');
        $stmt->bindValue(':email', $_POST['email']);
        $stmt->execute();
        if ($stmt->fetch()) {
            $erroCadastro = "Este e-mail já está cadastrado!";
        } else {
            $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
            $stmt = $db->prepare('INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)');
            $stmt->bindValue(':nome', $_POST['nome']);
            $stmt->bindValue(':email', $_POST['email']);
            $stmt->bindValue(':senha', $senhaHash);
            
            if ($stmt->execute()) {
                $_SESSION['usuario_id'] = $db->lastInsertId();
                header('Location: index.php');
                exit;
            } else {
                $erroCadastro = "Erro ao cadastrar. Tente novamente.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LembreMed - Cadastro</title>
    <link rel="shortcut icon" type="image/x-icon" href="💊">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="cadastro-container">
        <h2>LembreMed</h2>
        <p class="text-center">Cuide de você e de quem você ama.</p>
        
        <?php if ($erroCadastro): ?>
            <div class="erro"><?php echo $erroCadastro; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="campo">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div class="campo">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="campo">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <div class="campo">
                <label for="confirmar_senha">Confirmar Senha</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required>
            </div>
            <button type="submit">Cadastrar</button>
        </form>
        <p class="text-center">Já tem uma conta? <a href="login.php">Faça login</a></p>
    </div>
</body>
</html>