# Top 5 musicas Tião Carreiro & Pardinho - Backend

Este é o backend da aplicação **Tião Carreiro**, desenvolvido em **Laravel 11**.

## 🚀 Tecnologias Utilizadas

-   **Laravel 11**
-   **PHP 8.2+**
-   **Composer**
-   **SQLite**

## ⚙️ Como rodar localmente

### 1. Clone o repositório

```bash
git clone https://github.com/viniblack/tiao-carreiro-be.git
cd tiao-carreiro-be
```

### 2. Instale as dependências

```bash
composer install
```

### 3. Copie o arquivo `.env` e configure as variáveis de ambiente

```bash
cp .env.example .env
```

Configure o banco de dados e outras variáveis necessárias no arquivo `.env`.

### 4. Gere a chave da aplicação

```bash
php artisan key:generate
```

### 5. Execute as migrations

```bash
php artisan migrate
```

### 6. Inicie o servidor de desenvolvimento

```bash
php artisan serve
```

A aplicação estará disponível em: `http://localhost:8000`

---

## 📁 Estrutura do Projeto

```bash
├── app/
│   ├── Console/         # Comandos Artisan personalizados
│   ├── Exceptions/      # Tratamento de exceções
│   ├── Http/
│   │   ├── Controllers/ # Lógica dos controladores da aplicação
│   │   ├── Middleware/  # Middlewares HTTP
│   ├── Models/          # Modelos Eloquent
│   └── Providers/       # Providers de serviço da aplicação
│
├── bootstrap/           # Arquivo de bootstrapping da aplicação
├── config/              # Arquivos de configuração (auth, database, mail, etc)
├── database/
│   ├── factories/       # Fábricas para testes e seeds
│   ├── migrations/      # Arquivos de migração do banco de dados
│   └── seeders/         # Seeds para popular o banco com dados
│
├── public/              # Pasta pública (index.php, assets públicos)
├── resources/           # Views, e arquivos frontend (caso use blade, etc)
├── routes/
│   ├── api.php          # Rotas da API
│   └── web.php          # Rotas web (caso use)
├── storage/             # Arquivos gerados pela aplicação (logs, cache, etc)
├── tests/               # Testes automatizados (Feature, Unit)
├── .env                 # Variáveis de ambiente
├── artisan              # Executável do Laravel Artisan
└── composer.json        # Dependências PHP
```

---

## 🧪 Testes

Se houver testes implementados:

```bash
php artisan test
```

## 📌 Observações

-   Certifique-se de estar usando uma versão do PHP compatível (8.2+).
-   Verifique se o banco de dados está rodando corretamente.
-   Caso esteja usando Docker ou Laravel Sail, adapte os comandos conforme necessário.
