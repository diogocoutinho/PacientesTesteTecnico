# Projeto de Teste de Pacientes

Este projeto é uma aplicação Laravel que fornece uma interface para gerenciar pacientes. Ele inclui funcionalidades para listar, criar, atualizar e excluir pacientes, bem como exportar os dados dos pacientes para um arquivo CSV ou JSON.

## Requisitos

- Docker
- Docker Compose
- Node.js V18.19.0

## Instalação com Docker

1. Clone o repositório:

```bash
git clone https://github.com/diogocoutinho/PacientesTesteTecnico.git
```

2. Entre no diretório do projeto:

```bash
cd PacientesTesteTecnico
```

3. Construa e inicie os containers do Docker:

```bash
docker-compose up -d
```

4. Instale as dependências do Composer:

```bash
docker-compose exec app composer install
```

5. Copie o arquivo `.env.example` para `.env`:

```bash
docker-compose exec app cp .env.example .env
```

6. Gere a chave da aplicação:

```bash
docker-compose exec app php artisan key:generate
```

7. Configure o banco de dados no arquivo `.env`. As configurações padrão para o banco de dados são PostgreSQL no Docker são:

```bash
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=123456
```

8. Execute as migrações do banco de dados:

```bash
docker-compose exec app php artisan migrate
```

9. Rode node para compilar os assets e acesse a aplicação em http://localhost

```bash
docker-compose exec app npm install && npm run dev
```

## Testes

Para executar os testes, execute o seguinte comando:

```bash
docker-compose exec app php artisan test
```

## Documentação da API



## Agradecimentos

Agradeço a oportunidade de participar do processo seletivo e espero que gostem do projeto.

## Licença

Este projeto é open-source e licenciado sob a [MIT license](https://opensource.org/licenses/MIT).




