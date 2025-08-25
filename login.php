<?php
require_once 'includes/config.php';

$erroLogin = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'], $_POST['senha'])) {
    $db = conectarDB();
    $stmt = $db->prepare('SELECT * FROM usuarios WHERE email = :email');
    $stmt->bindValue(':email', $_POST['email']);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario && password_verify($_POST['senha'], $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        header('Location: index.php');
        exit;
    } else {
        $erroLogin = "E-mail ou senha incorretos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LembreMed - Login</title>
    <link rel="shortcut icon" type="image/x-icon" href="💊">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>LembreMed</h2>
        <p class="text-center">Sua saúde em dia, com lembretes no horário certo.</p>
        
        <?php if ($erroLogin): ?>
            <div class="erro"><?php echo $erroLogin; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="campo">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="campo">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
        <p class="text-center">Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
    </div>
</body>
</html>