# Mini ERP - Pedidos, Produtos, Cupons e Estoque

Este é um mini sistema ERP desenvolvido em PHP com MySQL, que permite o gerenciamento de:

- Produtos e variações
- Estoque por variação
- Cupons com validade e valor mínimo
- Carrinho de compras com regras de frete

---

## 🚀 Tecnologias

- PHP (puro)
- MySQL
- Bootstrap 5
- JavaScript (máscara, consulta CEP e DataTables)

---

## 📂 Estrutura

```
├── config/
│   └── db.php               # Conexão com o banco de dados (não subir para o GitHub)
├── controllers/             # Controladores de lógica
├── js/                      # Scripts JavaScript (máscaras, CEP)
├── models/                  # Lógica de dados e queries SQL
├── views/                   # Telas PHP (produtos, carrinho, estoque, cupons)
├── banco.sql                # Script SQL para criar e popular o banco de dados
└── index.php                # Redireciona para a tela inicial (produtos.php)
```

---

## 🛠️ Como usar

### 1. Clone o repositório

```bash
git clone https://github.com/SEU_USUARIO/mini-erp-pedidos.git
cd mini-erp-pedidos
```

### 2. Configure o ambiente

Você pode executar este projeto via:

#### 🔸 XAMPP ou WAMP (recomendado)

1. Coloque a pasta do projeto dentro de `htdocs` (ex: `C:/xampp/htdocs/mini-erp`)
2. Inicie o **Apache** e **MySQL** pelo painel do XAMPP
3. Acesse `http://localhost/mini-erp/index.php`

#### 🔸 Servidor embutido PHP (alternativa)

```bash
php -S localhost:8000
```
Depois acesse `http://localhost:8000`

---

### 3. Configure o banco de dados

1. Crie um banco chamado `mini_erp` no phpMyAdmin
2. Importe o arquivo `banco.sql` incluído no projeto
3. Crie o arquivo `config/db.php` com o conteúdo abaixo:

```php
<?php
$mysqli = new mysqli("localhost", "root", "", "mini_erp");
if ($mysqli->connect_error) {
    die("Erro na conexão: " . $mysqli->connect_error);
}
```

---

## ✨ Funcionalidades

- Cadastro e edição de produtos com variações e controle de estoque
- Carrinho com regras de frete:
  - R$20,00 (subtotal < 52)
  - R$15,00 (entre 52 e 166,59)
  - Grátis (acima de R$200)
- Aplicação de cupons com validade e valor mínimo
- Máscara de moeda nos campos de preço e valores
- Consulta de endereço via CEP (https://viacep.com.br)
- Webhook para integração externa de pedidos (não incluído nesta versão pública)

---

## 📸 Telas principais

- produtos.php → Cadastro, edição, compra e exclusão de produtos
- carrinho.php → Visualização dos produtos no carrinho, aplicação de cupom
- estoque_gerenciamento.php → Atualização de estoque por variação
- cupons.php → Cadastro e listagem de cupons

---

## 🤝 Contribuição

Pull requests são bem-vindos. Para grandes mudanças, abra uma issue primeiro para discutirmos.

---

## 📄 Licença

Este projeto está sob a licença MIT. Sinta-se livre para utilizar, contribuir e distribuir.

---

### 👨‍💻 Desenvolvido por Thiago Henrique dos Santos
[LinkedIn](www.linkedin.com/in/thiago-henrique-dos-santos-b13b36209) | [GitHub](https://github.com/thig7179)
