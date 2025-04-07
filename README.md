# Top 5 musicas Tião Carreiro & Pardinho - Backend

Este é o backend da aplicação **Tião Carreiro**, desenvolvido em **Laravel 11**.
🔗 Acesse também o repositório do frontend:
https://github.com/viniblack/tiao-carreiro-fe

## 🚀 Tecnologias Utilizadas

-   **Laravel 11**
-   **PHP 8.2+**
-   **Composer**
-   **SQLite**
-   **PHPUnit**

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

### 6. Execute os seeders

```bash
php artisan db:seed
```

Os seeders adicionam algumas músicas e criam dois tipos de usuários para teste:

```text
Usuário comum:
Email: member@email.com
Senha: senha123

Usuário admin:
Email: admin@email.com
Senha: senha123
```

### 7. Inicie o servidor de desenvolvimento

```bash
php artisan serve
```

A aplicação estará disponível em: http://localhost:8000

---

## 📁 Estrutura do Projeto

```bash
tiao-carreiro-be/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   ├── Requests/
│   │   ├── Resources/
│   ├── Models/
│   ├── Providers/
│   └── Services/
│
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── public/
├── resources/
├── routes/
│   ├── api.php
│   └── web.php
├── storage/
├── tests/
├── .env.example
├── artisan
└── composer.json
```

---

## 🧪 Testes

```bash
php artisan test
```
