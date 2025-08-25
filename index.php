<?php
require_once 'includes/config.php';
require_once 'includes/funcoes.php';

if (!estaLogado()) {
    header('Location: login.php');
    exit;
}

$usuario = obterUsuario();
$medicamentos = obterMedicamentos($_SESSION['usuario_id']);
$historico = obterHistorico($_SESSION['usuario_id']);

$mensagemSucesso = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['acao'])) {
        $db = conectarDB();

        // Adicionar novo medicamento
        if ($_POST['acao'] == 'adicionar' && isset($_POST['nome_remedio'], $_POST['dosagem'], $_POST['horario'])) {
            $anotacao = $_POST['anotacao'] ?? null;

            $stmt = $db->prepare('INSERT INTO medicamentos (usuario_id, nome, dose, horario, anotacao) 
                                 VALUES (:usuario_id, :nome, :dose, :horario, :anotacao)');
            $stmt->bindValue(':usuario_id', $_SESSION['usuario_id']);
            $stmt->bindValue(':nome', $_POST['nome_remedio']);
            $stmt->bindValue(':dose', $_POST['dosagem']);
            $stmt->bindValue(':horario', $_POST['horario']);
            $stmt->bindValue(':anotacao', $anotacao);

            if ($stmt->execute()) {
                $mensagemSucesso = "Lembrete salvo com sucesso!";
                $medicamentos = obterMedicamentos($_SESSION['usuario_id']);
            } else {
                $mensagemSucesso = "Erro ao salvar lembrete. Tente novamente.";
            }
        }
        // Registrar medicamento como tomado
        elseif ($_POST['acao'] == 'tomei_agora' && isset($_POST['medicamento_id'])) {
            $db = conectarDB();

            // Registrar no histórico
            $stmt = $db->prepare('INSERT INTO historico (medicamento_id) VALUES (:medicamento_id)');
            $stmt->bindValue(':medicamento_id', $_POST['medicamento_id']);
            $stmt->execute();

            $mensagemSucesso = "Medicação registrada!";
            $medicamentos = obterMedicamentos($_SESSION['usuario_id']);
            $historico = obterHistorico($_SESSION['usuario_id']);
        }
        // Excluir lembrete
        elseif ($_POST['acao'] == 'excluir_lembrete' && isset($_POST['medicamento_id'])) {
            $stmt = $db->prepare('DELETE FROM medicamentos WHERE id = :id AND usuario_id = :usuario_id');
            $stmt->bindValue(':id', $_POST['medicamento_id']);
            $stmt->bindValue(':usuario_id', $_SESSION['usuario_id']);

            if ($stmt->execute()) {
                $mensagemSucesso = "Lembrete excluído com sucesso!";
                $medicamentos = obterMedicamentos($_SESSION['usuario_id']);
            } else {
                $mensagemSucesso = "Erro ao excluir lembrete. Tente novamente.";
            }
        }
        // Excluir registro do histórico
        elseif ($_POST['acao'] == 'excluir_historico' && isset($_POST['historico_id'])) {
            $stmt = $db->prepare('DELETE FROM historico WHERE id = :id');
            $stmt->bindValue(':id', $_POST['historico_id']);

            if ($stmt->execute()) {
                $mensagemSucesso = "Registro do histórico excluído com sucesso!";
                $historico = obterHistorico($_SESSION['usuario_id']);
            } else {
                $mensagemSucesso = "Erro ao excluir registro. Tente novamente.";
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
    <title>LembreMed - Início</title>
    <link rel="shortcut icon" type="image/x-icon" href="💊">
    <link rel="stylesheet" href="css/style.css">
    <style>
        :root {
    /* Nova paleta de cores - tons mais suaves e profissionais */
    --azul-principal: #0A84FF;
    --azul-escuro: #0056CC;
    --branco: #FFFFFF;
    --cinza-claro: #F5F5F7;
    --cinza: #E5E5EA;
    --cinza-escuro: #8E8E93;
    --erro: #FF453A;
    --sucesso: #30D158;
    --fundo: #F2F2F7;
    --card-bg: #FFFFFF;
    --texto-principal: #1D1D1F;
    --texto-secundario: #48484A;
    --sistema-card: rgba(255, 255, 255, 0.75);
    --sombra-suave: 0 4px 20px rgba(0, 0, 0, 0.06);
    --sombra-intensa: 0 8px 24px rgba(0, 0, 0, 0.1);
    
    /* Novas variáveis para o redesign */
    --gradiente-azul: linear-gradient(135deg, var(--azul-principal), var(--azul-escuro));
    --gradiente-sucesso: linear-gradient(135deg, var(--sucesso), #2CA24F);
    --borda-sutil: 1px solid rgba(142, 142, 147, 0.15);
    --overlay-claro: rgba(255, 255, 255, 0.7);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Text', 'SF Pro Display', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
}

body {
    background-color: var(--fundo);
    color: var(--texto-principal);
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
    background-image: 
        radial-gradient(circle at 15% 50%, rgba(10, 132, 255, 0.03) 0%, transparent 25%),
        radial-gradient(circle at 85% 30%, rgba(48, 209, 88, 0.03) 0%, transparent 25%);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Header estilo iOS refinado */
header {
    background-color: var(--sistema-card);
    backdrop-filter: blur(30px) saturate(180%);
    -webkit-backdrop-filter: blur(30px) saturate(180%);
    color: var(--texto-principal);
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: var(--sombra-suave);
    position: sticky;
    top: 0;
    z-index: 100;
    border-bottom: var(--borda-sutil);
}

header h1 {
    font-size: 1.5rem;
    font-weight: 700;
    background: var(--gradiente-azul);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: -0.5px;
}

nav a {
    color: var(--azul-principal);
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    background-color: transparent;
    font-weight: 500;
    transition: all 0.2s ease;
    font-size: 0.95rem;
}

nav a:hover {
    background-color: rgba(10, 132, 255, 0.1);
}

main {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-top: 24px;
}

.bem-vindo {
    grid-column: 1 / -1;
    text-align: center;
    margin-bottom: 28px;
    padding: 20px;
    background-color: var(--overlay-claro);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-radius: 16px;
    border: var(--borda-sutil);
}

.bem-vindo h2 {
    color: var(--texto-principal);
    margin-bottom: 12px;
    font-weight: 600;
    font-size: 1.8rem;
}

.bem-vindo p {
    font-size: 1.2rem;
    color: var(--texto-secundario);
    font-weight: 400;
}

/* Cards estilo iOS refinado */
section {
    background-color: var(--card-bg);
    padding: 24px;
    border-radius: 16px;
    box-shadow: var(--sombra-suave);
    border: var(--borda-sutil);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

section:hover {
    transform: translateY(-2px);
    box-shadow: var(--sombra-intensa);
}

h3 {
    margin-bottom: 1.25rem;
    color: var(--texto-principal);
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--cinza);
    font-weight: 600;
    font-size: 1.25rem;
}

.campo {
    margin-bottom: 1.4rem;
}

.campo label {
    display: block;
    margin-bottom: 0.6rem;
    font-weight: 500;
    color: var(--texto-secundario);
    font-size: 0.9rem;
}

.campo input, .campo select, .campo textarea {
    width: 100%;
    padding: 0.9rem;
    border: 1px solid var(--cinza);
    border-radius: 12px;
    font-size: 1rem;
    background-color: var(--branco);
    transition: all 0.2s ease;
}

.campo input:focus, .campo select:focus, .campo textarea:focus {
    outline: none;
    border-color: var(--azul-principal);
    box-shadow: 0 0 0 3px rgba(10, 132, 255, 0.15);
}

/* Botões estilo iOS refinado */
button {
    background: var(--gradiente-azul);
    color: var(--branco);
    border: none;
    padding: 0.9rem 1.5rem;
    border-radius: 12px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(10, 132, 255, 0.25);
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(10, 132, 255, 0.35);
}

.erro {
    color: var(--erro);
    margin-bottom: 1rem;
    padding: 0.9rem;
    background-color: rgba(255, 69, 58, 0.08);
    border-radius: 12px;
    border-left: 4px solid var(--erro);
    font-weight: 500;
}

.sucesso {
    color: var(--sucesso);
    margin-bottom: 1rem;
    padding: 0.9rem;
    background-color: rgba(48, 209, 88, 0.08);
    border-radius: 12px;
    border-left: 4px solid var(--sucesso);
    font-weight: 500;
}

/* Lembretes estilo iOS refinado */
.lembrete {
    border: var(--borda-sutil);
    border-radius: 14px;
    padding: 1.2rem;
    margin-bottom: 1rem;
    background-color: var(--branco);
    transition: all 0.2s ease;
}

.lembrete:hover {
    transform: translateY(-3px);
    box-shadow: var(--sombra-suave);
}

.lembrete h4 {
    margin-bottom: 0.6rem;
    color: var(--texto-principal);
    font-weight: 600;
    font-size: 1.1rem;
}

.lembrete .horario {
    font-weight: 600;
    color: var(--azul-principal);
}

.tomei-agora {
    margin-top: 0.6rem;
    background: var(--gradiente-sucesso);
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(48, 209, 88, 0.3);
}

.tomei-agora:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(48, 209, 88, 0.4);
}

/* Tabelas refinadas */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1.2rem;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--sombra-suave);
}

table, th, td {
    border: 1px solid var(--cinza);
}

th, td {
    padding: 0.9rem;
    text-align: left;
}

th {
    background: var(--gradiente-azul);
    color: var(--branco);
    font-weight: 600;
}

tr:nth-child(even) {
    background-color: var(--cinza-claro);
}

/* Páginas de login e cadastro refinadas */
.login-container, .cadastro-container {
    max-width: 420px;
    margin: 60px auto;
    padding: 2.5rem;
    background-color: var(--card-bg);
    border-radius: 20px;
    box-shadow: var(--sombra-intensa);
    border: var(--borda-sutil);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}

.login-container h2, .cadastro-container h2 {
    text-align: center;
    margin-bottom: 1.5rem;
    color: var(--texto-principal);
    font-weight: 700;
    font-size: 1.8rem;
    background: var(--gradiente-azul);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.text-center {
    text-align: center;
    margin-top: 1.2rem;
    color: var(--texto-secundario);
}

/* Footer refinado */
footer {
    text-align: center;
    padding: 1.8rem;
    margin-top: 3rem;
    background-color: var(--sistema-card);
    backdrop-filter: blur(30px) saturate(180%);
    -webkit-backdrop-filter: blur(30px) saturate(180%);
    color: var(--texto-secundario);
    border-top: var(--borda-sutil);
}

/* Anotações refinadas */
.anotacao {
    font-style: italic;
    color: var(--cinza-escuro);
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px dashed var(--cinza);
    font-size: 0.9rem;
}

/* Outros elementos refinados */
.excluir {
    background-color: transparent;
    border: none;
    color: var(--erro);
    font-size: 1.2rem;
    cursor: pointer;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
    background: #ff5c5456;
}

.excluir:hover {
    background-color: rgba(255, 68, 58, 0.61);
    transform: scale(1.1);
}

.perfil-card {
    border: var(--borda-sutil);
    padding: 18px;
    border-radius: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    flex: 1;
    background-color: var(--card-bg);
}

.perfil-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--sombra-suave);
}

.perfil-card.selecionado {
    border-color: var(--azul-principal);
    background-color: rgba(10, 132, 255, 0.08);
    box-shadow: 0 0 0 2px rgba(10, 132, 255, 0.2);
}

/* Responsividade */
@media (max-width: 768px) {
    main {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .perfil-selector {
        flex-direction: column;
    }
    
    .login-container, .cadastro-container {
        margin: 30px 20px;
        padding: 2rem;
    }
}

/* Melhorias de acessibilidade visual */
*:focus-visible {
    outline: 2px solid var(--azul-principal);
    outline-offset: 2px;
}

/* Adicionando efeitos de transição suave para interações */
* {
    transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
}
    </style>
</head>

<body>
    <header>
        <h1>LembreMed</h1>
        <nav>
            <a href="logout.php">Sair</a>
        </nav>
    </header>

    <div class="container">
        <div class="bem-vindo">
            <h2>Olá, <?php echo htmlspecialchars($usuario['nome']); ?>!</h2>
            <p>Não esqueça mais de tomar seus remédios!</p>
        </div>

        <?php if ($mensagemSucesso): ?>
            <div class="sucesso"><?php echo $mensagemSucesso; ?></div>
        <?php endif; ?>

        <main>
            <section class="adicionar-medicamento">
                <h3>Adicionar Medicamento</h3>
                <form method="POST">
                    <input type="hidden" name="acao" value="adicionar">
                    <div class="campo">
                        <label for="nome_remedio">Nome do Remédio</label>
                        <input type="text" id="nome_remedio" name="nome_remedio" required>
                    </div>
                    <div class="campo">
                        <label for="dosagem">Dosagem</label>
                        <input type="text" id="dosagem" name="dosagem" placeholder="ex: 1 comprimido" required>
                    </div>
                    <div class="campo">
                        <label for="horario">Horário</label>
                        <input type="time" id="horario" name="horario" required>
                    </div>

                    <div class="campo">
                        <label for="anotacao">Anotação (opcional)</label>
                        <textarea id="anotacao" name="anotacao" rows="2" placeholder="Ex: Tomar após o almoço"></textarea>
                    </div>
                    <button type="submit">Salvar Lembrete</button>
                </form>
            </section>

            <section class="seus-lembretes">
                <h3>Seus Lembretes</h3>
                <?php if (count($medicamentos) > 0): ?>
                    <?php foreach ($medicamentos as $med): ?>
                        <div class="lembrete">
                            <div class="cabecalho-lembrete">
                                <h4><?php echo htmlspecialchars($med['nome']); ?></h4>
                                <form method="POST" class="form-excluir">
                                    <input type="hidden" name="acao" value="excluir_lembrete">
                                    <input type="hidden" name="medicamento_id" value="<?php echo $med['id']; ?>">
                                    <button type="submit" class="excluir" title="Excluir lembrete" onclick="return confirm('Tem certeza que deseja excluir este lembrete?')">✕</button>
                                </form>
                            </div>
                            <p>Dose: <?php echo htmlspecialchars($med['dose']); ?></p>
                            <p class="horario">Horário: <?php echo htmlspecialchars($med['horario']); ?></p>

                            <?php if (!empty($med['anotacao'])): ?>
                                <p class="anotacao">Anotação: <?php echo htmlspecialchars($med['anotacao']); ?></p>
                            <?php endif; ?>

                            <form method="POST" style="margin-top:10px;">
                                <input type="hidden" name="acao" value="tomei_agora">
                                <input type="hidden" name="medicamento_id" value="<?php echo $med['id']; ?>">
                                <button type="submit" class="tomei-agora">Tomei agora</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum lembrete cadastrado ainda.</p>
                <?php endif; ?>
            </section>

            <section class="historico">
                <h3>Histórico</h3>
                <?php if (count($historico) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Medicamento</th>
                                <th>Dose</th>
                                <th>Data/Hora</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historico as $hist): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($hist['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($hist['dose']); ?></td>
                                    <td><?php echo htmlspecialchars($hist['data_hora']); ?></td>
                                    <td>
                                        <form method="POST" class="form-excluir">
                                            <input type="hidden" name="acao" value="excluir_historico">
                                            <input type="hidden" name="historico_id" value="<?php echo $hist['id']; ?>">
                                            <button type="submit" class="excluir" title="Excluir registro" onclick="return confirm('Tem certeza que deseja excluir este registro do histórico?')">✕</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Nenhum histórico registrado.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <footer>
        <div class="footer-content" style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; padding: 20px 0; ">
            <div class="footer-brand" style="font-weight: bold; font-size: 1.1em; color:#f3f3f3;">
            <span style="vertical-align: middle; color: rgb(31, 34, 34);">💊 LembreMed</span>
            <span style="margin-left: 8px; color:rgb(43, 115, 209);">&copy; <?php echo date('Y'); ?></span>
            </div>
            <nav class="footer-nav">
            <ul style="list-style: none; display: flex; gap: 25px; padding: 15px; margin: 0;">
                <li><a href="login.php" style="text-decoration: none; color:rgb(25, 132, 255); font-weight: 500; transition: color 0.2s;">Login</a></li>
                <li><a href="sobre.php" style="text-decoration: none; color:rgb(25, 132, 255); font-weight: 500; transition: color 0.2s;">Sobre Nós</a></li>
                <?php if (estaLogado()): ?>
                    <li><a href="logout.php" style="text-decoration: none; color:rgb(255, 255, 255); font-weight: 500; transition: color 0.2s; background:rgb(255, 35, 35);">Sair</a></li>
                <?php endif; ?>
            </ul>
            </nav>
        </div>
    </footer>

    <script src="js/notificacoes_css.js"></script>

</body>

</html>