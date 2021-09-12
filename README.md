# Blog

Projeto de estudo aplicando clean code, clean architecture e tdd.

Algumas customizações foram criadas por mim mesmo,
os attributes de rotas e scan de rotas, para uma melhor legibilidade na escrita de controladores.

### Tecnologias utilizadas
* Slim Framework
* Doctrine ORM
* PHPUnit

### Pré-Requisitos
PHP 8.0

### Instalação de Dependências
``composer install``

### Banco de dados
O projeto utiliza MySQL.
Sugestão de instalação utilizando docker

``docker run --name mysql-blog -e MYSQL_ROOT_PASSWORD=secret -p 13306:3306 -d ``

Criação da base de dados

``create database blog``

``composer migrations:migrate``

### Iniciando o servidor
``composer server:start``

### Swagger UI
http://localhost:8000/swagger/ui

### Testes
``composer test``
