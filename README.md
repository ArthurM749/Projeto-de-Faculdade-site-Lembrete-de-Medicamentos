💊 LembreMed

Site para lembrete de medicamentos — projeto de faculdade para cadastrar e organizar horários e doses de remédios, registrar quando foram tomados e manter um histórico simples e intuitivo.

Índice

Visão geral

Funcionalidades

Stack técnica

Estrutura do projeto

Banco de dados (schema sugerido)

Instalação & execução (desenvolvimento)

Configuração (includes/config.php)

Segurança e boas práticas

Ideias para evolução

Contribuição

Autores

Licença

Visão geral

LembreMed é uma aplicação simples e direta para ajudar usuários a não esquecerem seus medicamentos: cadastro de usuários, registro de medicamentos com dosagem e horário, marcação “Tomei agora” e histórico de administrações. A aplicação já inclui páginas de login/cadastro e uma interface com design responsivo. Veja as páginas principais e a lógica de backend nos arquivos index.php, login.php, cadastro.php e sobre.php. 
 
 
 

O visual e a tipografia foram cuidadosamente trabalhados no style.css para um layout moderno e acessível. 

Funcionalidades (implementadas)

Cadastro de usuário (nome, email, senha — com password_hash). 

Login com verificação de senha (password_verify) e sessão. 

Adicionar lembretes de medicamento: nome, dose, horário e anotação opcional. (formulário em index.php). 

Listagem dos lembretes do usuário com opções de excluir. 

Registrar que o medicamento foi tomado (botão Tomei agora) — grava entrada no histórico. 

Histórico de tomadas com data/hora e opção de exclusão. 

UI responsiva e refinada (CSS com variáveis, cards, botões, efeitos). 

Stack técnica

Backend: PHP (uso de PDO para acesso ao banco). (ver includes/config.php / chamadas PDO em index.php). 

Banco: MySQL / MariaDB (sugerido) — usando PDO; fácil adaptação para SQLite. 

Frontend: HTML, CSS (arquivo css/style.css), JavaScript mínimo (ex.: js/notificacoes_css.js). 
 

Estrutura (principais arquivos)
/
├─ index.php           # Página principal: adicionar/visualizar lembretes e histórico. :contentReference[oaicite:16]{index=16}
├─ login.php           # Formulário de login. :contentReference[oaicite:17]{index=17}
├─ cadastro.php        # Formulário de cadastro (nova conta). :contentReference[oaicite:18]{index=18}
├─ sobre.php           # Página "Sobre" / equipe / apresentação. :contentReference[oaicite:19]{index=19}
├─ css/
│  └─ style.css        # Estilo principal. :contentReference[oaicite:20]{index=20}
├─ includes/
│  ├─ config.php       # Conexão/ configurações (deve existir). 
│  └─ funcoes.php      # Funções auxiliares (estaLogado, obterUsuario, etc.). :contentReference[oaicite:21]{index=21}
├─ js/
│  └─ notificacoes_css.js  # (script de notificação/UX) :contentReference[oaicite:22]{index=22}


Observação: index.php, login.php e cadastro.php fazem require_once 'includes/config.php' e includes/funcoes.php — garanta que esses arquivos existam e estejam corretamente configurados. 

Banco de dados — schema sugerido (baseado no código)

Abaixo um schema sugerido — alinhei os nomes de colunas a partir das queries vistas em index.php, cadastro.php e login.php. Ajuste conforme o seu includes/funcoes.php se já houver migrações.

-- banco: lembremed

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE medicamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  nome VARCHAR(255) NOT NULL,
  dose VARCHAR(100) DEFAULT NULL,
  horario TIME DEFAULT NULL,
  anotacao TEXT DEFAULT NULL,
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE historico (
  id INT AUTO_INCREMENT PRIMARY KEY,
  medicamento_id INT NOT NULL,
  data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (medicamento_id) REFERENCES medicamentos(id) ON DELETE CASCADE
);


As consultas INSERT/DELETE observadas em index.php confirmam estas colunas (ex.: INSERT INTO medicamentos (usuario_id, nome, dose, horario, anotacao) e INSERT INTO historico (medicamento_id)). 

Instalação & execução (modo desenvolvimento)

Clone

git clone <seu-repo>.git
cd <seu-repo>


Criar banco

Crie o banco e execute o SQL do schema sugerido acima (via mysql ou ferramenta GUI).

Configurar includes/config.php

Crie/edite includes/config.php com DSN, usuário e senha do banco. Abaixo um exemplo mínimo (coloque credenciais reais e proteja o arquivo):

<?php
// includes/config.php (exemplo)
session_start();

function conectarDB() {
    $host = '127.0.0.1';
    $db   = 'lembremed';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opts = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    return new PDO($dsn, $user, $pass, $opts);
}


Verifique includes/funcoes.php

Garanta que as funções estaLogado(), obterUsuario(), obterMedicamentos() e obterHistorico() existam e usem conectarDB() (os arquivos index.php/sobre.php fazem require desses includes). 
 

Rodar servidor PHP local (dev)

php -S localhost:8000


Abra http://localhost:8000/login.php no navegador. (ou ajuste seu VirtualHost / XAMPP / Laragon conforme preferir).

Configurações recomendadas

PHP: 7.4+ (ou 8.x). Uso de password_hash/password_verify no cadastro/login.

Banco: MySQL/MariaDB (charset utf8mb4) — configure pdo corretamente. 

Segurança & boas práticas

As senhas já são tratadas com password_hash no cadastro e password_verify no login — bom.

Use sempre prepared statements (já presente no código via PDO). 

Reforce:

Use HTTPS em produção.

Defina flags seguras nos cookies (HttpOnly, Secure, SameSite).

Regenerar session_id() após login.

Implementar CSRF tokens para formulários sensíveis (ex.: exclusões).

Sanitização/escape na saída (vejo uso de htmlspecialchars ao imprimir nomes/anotações — continue assim). 

Melhoria & roadmap (sugestões)

Enviar notificações reais (push / email / SMS) usando job/cron no servidor.

Adicionar agendamento avançado (repetição diária/intervalos).

Integração com Web Push API para lembretes no dispositivo.

Área administrativa para visualizar métricas/usuários.

Testes automatizados (PHPUnit) e pipelines CI.

Contribuição

Abra uma issue descrevendo a proposta.

Crie uma branch feature/nome-curto.

Faça pull request com descrição clara e screenshots (quando aplicar).

Autores

Equipe do projeto (extraído da página Sobre): Arthur Moura, Tiago Mendes, Samuel, Arthur Henrique, entre outros membros listados em sobre.php. 

Observações finais

Documentei o schema sugerido, o fluxo de autenticação e as operações principais com base nos arquivos enviados (index.php, login.php, cadastro.php, sobre.php) e no CSS (style.css). Consulte os arquivos originais para confirmar nomes/rotas exatas e ajustar includes/config.php/includes/funcoes.php.
