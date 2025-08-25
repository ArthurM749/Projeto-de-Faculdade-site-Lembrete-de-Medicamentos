<?php
require_once 'includes/config.php';
require_once 'includes/funcoes.php';

// Não requer login para acessar a página Sobre
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre - LembreMed</title>
    <link rel="shortcut icon" type="image/x-icon" href="💊">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Estilos adicionais para a página Sobre */
        :root {
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Estilos para a página Sobre - Atualizados */
        .sobre-container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 40px;
            background-color: var(--card-bg);
            border-radius: 20px;
            box-shadow: var(--sombra-suave);
            border: var(--borda-sutil);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .sobre-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 25px;
            border-bottom: 1px solid rgba(142, 142, 147, 0.2);
        }

        .sobre-header h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 15px;
            background: var(--gradiente-azul);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .sobre-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 50px;
        }
        
        .sobre-text {
            line-height: 1.8;
            max-width: 800px;
            text-align: center;
            margin-bottom: 40px;
            font-size: 1.1rem;
            color: var(--texto-secundario);
        }
        
        .sobre-text p {
            margin-bottom: 25px;
        }
        
        .logo-container {
            margin: 40px 0;
            text-align: center;
            padding: 30px;
            background: var(--overlay-claro);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            border: var(--borda-sutil);
        }
        
        .logo-lembremed {
            font-size: 3.8rem;
            font-weight: 700;
            background: var(--gradiente-azul);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 15px;
            display: inline-block;
            padding: 10px;
        }
        
        .slogan {
            font-size: 1.3rem;
            color: var(--cinza-escuro);
            font-style: italic;
            font-weight: 500;
        }
        
        .equipe {
            margin-top: 50px;
            width: 100%;
        }
        
        .equipe h3 {
            text-align: center;
            margin-bottom: 40px;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--texto-principal);
        }
        
        .membros {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
        }
        
        .membro {
            text-align: center;
            padding: 25px 20px;
            background-color: var(--card-bg);
            border-radius: 18px;
            transition: all 0.3s ease;
            box-shadow: var(--sombra-suave);
            border: var(--borda-sutil);
        }
        
        .membro:hover {
            transform: translateY(-8px);
            box-shadow: var(--sombra-intensa);
        }
        
        .membro-avatar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: var(--gradiente-azul);
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            font-weight: bold;
            box-shadow: 0 6px 16px rgba(10, 132, 255, 0.3);
        }
        
        .membro-nome {
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--texto-principal);
            font-size: 1.1rem;
        }
        
        .membro-funcao {
            font-size: 0.95rem;
            color: var(--cinza-escuro);
            font-weight: 500;
        }
        
        .evento-info {
            background: var(--overlay-claro);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 30px;
            border-radius: 18px;
            margin-top: 40px;
            text-align: center;
            border: var(--borda-sutil);
        }

        .evento-info h3 {
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--texto-principal);
        }

        .evento-info p {
            color: var(--texto-secundario);
            line-height: 1.7;
        }
        
        footer {
            text-align: center;
            padding: 2rem;
            margin-top: 4rem;
            background-color: var(--sistema-card);
            backdrop-filter: blur(30px) saturate(180%);
            -webkit-backdrop-filter: blur(30px) saturate(180%);
            color: var(--texto-secundario);
            border-top: var(--borda-sutil);
        }

        .footer-content {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            padding: 20px 0;
        }

        .footer-brand {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--texto-principal);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-nav ul {
            list-style: none;
            display: flex;
            gap: 25px;
            padding: 15px;
            margin: 0;
        }

        .footer-nav a {
            text-decoration: none;
            color: var(--azul-principal);
            font-weight: 500;
            transition: color 0.2s;
            padding: 8px 16px;
            border-radius: 8px;
        }

        .footer-nav a:hover {
            background-color: rgba(10, 132, 255, 0.1);
        }

        .btn-sair {
            background-color: rgba(255, 69, 58, 0.15);
            padding: 8px 16px;
            border-radius: 8px;
            color: var(--erro);
        }

        .btn-sair:hover {
            background-color: rgba(255, 69, 58, 0.25);
        }
        
        @media (max-width: 768px) {
            .membros {
                grid-template-columns: 1fr 1fr;
            }
            
            .logo-lembremed {
                font-size: 2.8rem;
            }

            .sobre-container {
                padding: 25px;
                margin: 30px 15px;
            }

            .footer-content {
                flex-direction: column;
                gap: 20px;
            }
        }
        
        @media (max-width: 480px) {
            .membros {
                grid-template-columns: 1fr;
            }

            .sobre-header h2 {
                font-size: 1.8rem;
            }

            .logo-lembremed {
                font-size: 2.2rem;
            }

            .slogan {
                font-size: 1.1rem;
            }
        }
    </style>
</head>

<body>
    <header>
        <h1>LembreMed</h1>
        <nav>
            <?php if (estaLogado()): ?>
                <a href="index.php">Voltar</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="container">
        <div class="sobre-container">
            <div class="sobre-header">
                <h2>Sobre o Projeto</h2>
            </div>
            
            <div class="sobre-content">
                <div class="sobre-text">
                    <p>O LembreMed é um sistema desenvolvido para ajudar pessoas a manterem o controle de seus medicamentos, oferecendo uma forma simples de registrar horários, dosagens e receber lembretes. Nosso objetivo é tornar o cuidado com a saúde mais seguro e prático, evitando esquecimentos e garantindo que o tratamento seja seguido corretamente.</p>
                    
                    <p>Este projeto foi criado por seis estudantes do curso de Ciência da Computação da Faculdade Univértix, como parte da Semana da Informática. Nosso foco é aplicar o que aprendemos na área de desenvolvimento web para criar soluções reais e úteis para o dia a dia.</p>
                </div>
                
                <div class="logo-container">
                    <div class="logo-lembremed">💊 LembreMed</div>
                    <div class="slogan">Sua saúde em primeiro lugar</div>
                </div>
                
                <div class="sobre-text">
                    <p>Acreditamos que a tecnologia pode desempenhar um papel fundamental na melhoria da qualidade de vida das pessoas, especialmente quando se trata de saúde. O LembreMed foi projetado para ser intuitivo, acessível e eficiente, ajudando usuários a gerenciar seus medicamentos de forma independente e confiável.</p>
                </div>
            </div>
            
            <div class="equipe">
                <h3>Nossa Equipe</h3>
                
                <div class="membros">
                    <div class="membro">
                        <div class="membro-avatar">A</div>
                        <div class="membro-nome">Arthur Moura</div>
                        <div class="membro-funcao">Desenvolvedor Full-stack</div>
                    </div>
                    
                    <div class="membro">
                        <div class="membro-avatar">J</div>
                        <div class="membro-nome">João Pedro</div>
                        <div class="membro-funcao">Redator</div>
                    </div>
                    
                    <div class="membro">
                        <div class="membro-avatar">N</div>
                        <div class="membro-nome">Noé Felipe</div>
                        <div class="membro-funcao">Designer</div>
                    </div>
                    
                    <div class="membro">
                        <div class="membro-avatar">T</div>
                        <div class="membro-nome">Tiago Mendes</div>
                        <div class="membro-funcao">Imagens</div>
                    </div>
                    
                    <div class="membro">
                        <div class="membro-avatar">S</div>
                        <div class="membro-nome">Samuel</div>
                        <div class="membro-funcao">Apresentação</div>
                    </div>
                    
                    <div class="membro">
                        <div class="membro-avatar">A</div>
                        <div class="membro-nome">Arthur Henrique</div>
                        <div class="membro-funcao">Relator</div>
                    </div>
                </div>
            </div>
            
            <div class="evento-info">
                <h3>Semana da Informática - Univértix</h3>
                <p>Este projeto foi desenvolvido como parte da Semana da Informática da Faculdade Univértix, um evento anual que reúne estudantes, professores e profissionais da área de tecnologia para compartilhar conhecimentos e desenvolver soluções inovadoras.</p>
            </div>
        </div>
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
                <li><a href="index.php" style="text-decoration: none; color:rgb(25, 132, 255); font-weight: 500; transition: color 0.2s;">Página - Início</a></li>
                <?php if (estaLogado()): ?>
                    <li><a href="logout.php" style="text-decoration: none; color:rgb(255, 255, 255); font-weight: 500; transition: color 0.2s; background:rgb(255, 35, 35);">Sair</a></li>
                <?php endif; ?>
            </ul>
            </nav>
        </div>
    </footer>
</body>

</html>