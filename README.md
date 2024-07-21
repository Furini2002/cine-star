# Cinestar

Cinestar é uma plataforma fictícia de críticas de filmes, onde os usuários podem acessar filmes, adicionar novos filmes e escrever críticas e avaliações. O objetivo é permitir uma interação rica entre os usuários e criar uma base de dados de filmes e avaliações.

## Descrição do Projeto

Cinestar permite que os usuários:
- Acessem filmes disponíveis na plataforma.
- Adicionem novos filmes.
- Escrevam e visualizem críticas e avaliações de filmes.
- Visualizem perfis de outros usuários e vejam os filmes que eles adicionaram e avaliaram.

### Funcionalidades principais:

- **Cadastro e Login:** Apenas usuários logados podem adicionar e avaliar filmes.
- **Avaliação Única:** Cada usuário pode escrever apenas uma crítica por filme.
- **Perfis de Usuários:** Permite visualizar os perfis e atividades de outros usuários.
- **Gestão de Filmes:** Filmes podem ser adicionados, editados e excluídos.
- **Gestão de Usuários:** Usuários podem editar suas informações, exceto seus e-mails.
- **Página Principal:** Mostra os últimos filmes adicionados e filmes categorizados.

## Instalação

### Requisitos

- PHP
- Servidor web (ex: Apache)
- Banco de dados MySQL
- Composer (para gerenciar dependências, se necessário)
- Navegador web

### Passo a Passo

1. Clone o repositório:
    ```bash
    git clone https://github.com/usuario/cinestar.git
    ```

2. Navegue até o diretório do projeto:
    ```bash
    cd cinestar
    ```

3. Configure o banco de dados:
    - Crie um banco de dados MySQL.
    - Importe o arquivo `database.sql` que se encontra na raiz do projeto para criar as tabelas necessárias.

4. Configure as credenciais do banco de dados:
    - Edite o arquivo `config.php` com as suas credenciais do banco de dados.

5. Inicie o servidor web e acesse o projeto pelo navegador.

## Uso

1. Abra o navegador e acesse o projeto através do URL fornecido pelo seu servidor web.
2. Cadastre-se ou faça login.
3. Navegue pelo site para adicionar, avaliar e criticar filmes.
4. Acesse perfis de outros usuários para ver suas contribuições.

## Estrutura do Projeto

```plaintext
cinestar/
│
├── css/              # Arquivos CSS
├── dao/              # Arquivos de acesso a dados
├── img/              # Imagens do projeto
├── models/           # Modelos do sistema
├── templates/        # Templates para páginas web
│
├── config.php        # Configurações do projeto
├── index.php         # Página inicial
├── ...               # Outros arquivos PHP na raiz do projeto
└── README.md         # Este arquivo
