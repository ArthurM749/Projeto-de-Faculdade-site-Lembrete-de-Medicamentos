# Projeto de Faculdade — Lembrete de Medicamentos

Este projeto é um sistema web desenvolvido para auxiliar usuários no controle e organização do uso de medicamentos, permitindo cadastrar remédios, horários e doses, além de emitir lembretes para não esquecer de tomar as medicações. O objetivo é trazer praticidade e segurança ao gerenciamento de tratamentos, sendo ideal para uso pessoal ou de familiares.

## Índice

- [Sobre o Projeto](#sobre-o-projeto)
- [Funcionalidades](#funcionalidades)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Requisitos para Execução](#requisitos-para-execução)
- [Como Rodar o Projeto Localmente](#como-rodar-o-projeto-localmente)
- [Estrutura de Pastas](#estrutura-de-pastas)
- [Observações Importantes](#observações-importantes)

---

## Sobre o Projeto

O sistema foi desenvolvido como parte de um projeto acadêmico, com o intuito de facilitar a vida de quem precisa administrar o uso de vários medicamentos. Com uma interface simples, o usuário pode registrar todos os remédios em uso, agendar horários de cada dose e visualizar facilmente as próximas medicações.

## Funcionalidades

- Cadastro de medicamentos (nome, dosagem, observações, etc.)
- Definição de horários para cada medicamento
- Visualização de todos os medicamentos cadastrados e seus horários
- Edição e exclusão de medicamentos e horários
- Interface intuitiva para facilitar o uso, mesmo para quem não tem experiência com tecnologia
- Sistema totalmente local (não requer internet para funcionar após instalado)
- Possibilidade de organizar a rotina de várias pessoas (adaptável)

## Tecnologias Utilizadas

- **PHP** — Lógica de backend, manipulação de dados e integração com arquivos/sessões
- **CSS** — Estilização e responsividade das páginas
- **JavaScript** — Funcionalidades interativas e melhorias na experiência do usuário
- **HTML** — Estruturação das páginas

## Requisitos para Execução

- Ter o **XAMPP** instalado em sua máquina (inclui Apache e PHP)
- Navegador de internet de sua preferência (Chrome, Firefox, Edge, etc.)

## Como Rodar o Projeto Localmente

1. **Instale o XAMPP**  
   Faça o download e instale o XAMPP em seu computador, se ainda não tiver.

2. **Coloque o Projeto na Pasta Correta**  
   Extraia ou mova a pasta do projeto para dentro do diretório `htdocs` localizado na pasta onde o XAMPP foi instalado.  
   Exemplo:  
   ```
   C:\xampp\htdocs\Projeto-de-Faculdade-site-Lembrete-de-Medicamentos
   ```

3. **Inicie o Servidor Apache**  
   Abra o painel de controle do XAMPP e clique em "Start" no módulo Apache.

4. **Acesse o Sistema no Navegador**  
   Abra o navegador e digite o seguinte endereço:  
   ```
   http://localhost/Projeto-de-Faculdade-site-Lembrete-de-Medicamentos
   ```

5. **Pronto!**  
   O sistema estará disponível para uso localmente.

## Estrutura de Pastas

- `/` — Raiz do projeto
  - `index.php` — Página inicial do sistema
  - `cadastro.php` — Página para cadastro de medicamentos
  - `listar.php` — Página para listar e gerenciar medicamentos
  - `assets/` — Contém arquivos estáticos como CSS, JS e imagens
  - `includes/` — Arquivos auxiliares e funções reutilizáveis
  - `README.md` — Este arquivo com instruções e informações do projeto

## Observações Importantes

- O sistema foi desenvolvido para uso local, não sendo necessário (ou recomendado) publicá-lo na internet.
- Recomenda-se manter o XAMPP sempre ativado enquanto estiver utilizando o sistema.
- Caso deseje adaptar o sistema para outros cenários (como controle de medicamentos para familiares), basta cadastrar os medicamentos separadamente identificando-os por nome ou observação.
- Em caso de dúvidas, procure por tutoriais de XAMPP ou PHP caso não esteja conseguindo instalar ou executar o sistema.
