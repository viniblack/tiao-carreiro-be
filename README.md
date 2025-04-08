# Projeto Tião Carreiro (Backend)

Este é o **backend** do projeto _Tião Carreiro_, construído com **Laravel 11** e **PHP 8.2+**.  
A API REST expõe os dados de músicas e usuários da aplicação, permitindo visualização, sugestão de vídeos e controle de acessos.

> O projeto utiliza **roles de usuário**, seeders personalizados e banco de dados em **SQLite** para facilitar o setup local.

🔗 Veja também o [repositório do frontend](https://github.com/viniblack/tiao-carreiro-fe)

---

## 🚀 Tecnologias & Ferramentas

- ⚙️ **Laravel 11**
- 🐘 **PHP 8.2+**
- 📦 **Composer**
- 🧾 **SQLite**
- 🧪 **PHPUnit** (testes)

---

## 🧑‍💻 Como rodar localmente

### 1. Clone o repositório

```bash
git clone https://github.com/viniblack/tiao-carreiro-be.git
cd tiao-carreiro-be
```

### 2. Instale as dependências do PHP

```bash
composer install
```

### 3. Configure o ambiente
Copie o arquivo `.env` e edite conforme necessário:
```bash
cp .env.example .env
```
> 💡 Por padrão, o projeto usa SQLite, ideal para testes locais.
Altere a variável DB_CONNECTION se quiser usar MySQL ou outro banco.

Configure o banco de dados e outras variáveis necessárias no arquivo `.env`.

### 4. Gere a chave da aplicação

```bash
php artisan key:generate
```

### 5. Rode as migrations

```bash
php artisan migrate
```

### 6. Popule o banco com seeders

```bash
php artisan db:seed
```

Isso criará músicas de exemplo e dois usuários:

```text
👤 Usuário comum:
  Email: member@email.com
  Senha: senha123

🛠️ Administrador:
  Email: admin@email.com
  Senha: senha123
```

### 7. nicie o servidor

```bash
php artisan serve
```

A API estará disponível em: http://localhost:8000

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

Execute todos os testes da aplicação com:

```bash
php artisan test
```

## 🙌 Contribuições

Este projeto foi criado como um desafio técnico individual.
Sinta-se à vontade para clonar, abrir issues ou sugerir melhorias!

