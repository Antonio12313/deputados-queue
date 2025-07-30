# Link para visualizar online
 - **https://desktop-d2nh9lp.tailb3304c.ts.net**
# Projeto Deputados - Laravel + Inertia + React TypeScript

Sistema para gerenciamento e análise de dados de deputados brasileiros, desenvolvido com Laravel, Inertia.js e React TypeScript.

## 🛠️ Stack Tecnológica

- **Backend**: Laravel
- **Frontend**: React TypeScript
- **Bridge**: Inertia.js
- **Containerização**: Docker
- **Monitoramento de Filas**: Laravel Horizon
- **Gerenciador de Processos**: Supervisor

## 📋 Pré-requisitos

- Docker e Docker Compose instalados
- Git

## 🚀 Configuração do Projeto

### 1. Clone e Configure o Ambiente

```bash
git clone https://github.com/Antonio12313/deputados-queue.git
cd deputados-queue
```

Configure o arquivo `.env` com as variáveis de ambiente necessárias (copie de `.env.example` se disponível).

### 2. Inicialização dos Containers

```bash
docker compose up -d --build
```

Este comando irá construir e inicializar todos os containers necessários.

### 3. Instalação das Dependências PHP

Entre no container principal e instale as dependências do Composer:

```bash
# Entrar no container principal
docker exec -it [NOME_DO_CONTAINER_PHP] bash

# Instalar dependências PHP
composer install
# ou para atualizar
composer update
```

### 4. Instalação das Dependências Node.js

Ainda dentro do container, instale as dependências do NPM:

```bash
npm install
```

### 5. Configuração do Banco de Dados

Execute as migrações para criar as tabelas:

```bash
php artisan migrate
```

### 6. Configuração do Frontend

Para desenvolvimento:
```bash
npm run dev
```

Para produção:
```bash
npm run build
```

## 📊 Monitoramento de Filas

O Laravel Horizon está configurado para iniciar automaticamente com o container através do Supervisor.

**Acesse o painel do Horizon em**: `http://localhost:8080/horizon`

Aqui você pode monitorar todas as filas e jobs em processamento.

## 🏛️ Comandos para Dados dos Deputados

Para popular o sistema com dados dos deputados, execute os comandos na seguinte ordem:

### 1. Buscar Deputados Ativos

```bash
php artisan deputados:fetch
```

⚠️ **Aguarde todos os jobs serem processados antes de prosseguir**

### 2. Carregar Detalhes dos Deputados

```bash
php artisan deputados:load-details
```

⚠️ **Aguarde todos os jobs serem processados antes de prosseguir**

### 3. Buscar Despesas dos Deputados

```bash
php artisan deputados:fetch-despesas
```

⚠️ **Aguarde todos os jobs serem processados antes de prosseguir**

### 4. Buscar Situações dos Deputados

```bash
php artisan deputados:fetch-situacoes
```

## 📝 Importante

- **Ordem dos Comandos**: Execute os comandos na ordem especificada para garantir a integridade dos dados
- **Monitoramento**: Use o Horizon (`localhost:8080/horizon`) para acompanhar o progresso dos jobs
- **Processamento Assíncrono**: Todos os comandos utilizam jobs em background para melhor performance
- **Aguardar Conclusão**: É fundamental aguardar a conclusão de todos os jobs de um comando antes de executar o próximo

## 🔗 Links Úteis

- **Aplicação Principal**: `http://localhost:8080`
- **Laravel Horizon**: `http://localhost:8080/horizon`

## 📁 Estrutura do Projeto

```
├── app/
│   ├── Console/Commands/     # Comandos Artisan customizados
│   ├── Jobs/                 # Jobs para processamento assíncrono
│   └── ...
├── resources/
│   ├── js/                   # Componentes React TypeScript
│   └── ...
├── docker-compose.yml        # Configuração Docker
└── README.md
```

## 🐛 Solução de Problemas

Se encontrar problemas durante a configuração:

1. Verifique se todos os containers estão rodando: `docker ps`
2. Consulte os logs dos containers: `docker logs [NOME_DO_CONTAINER]`
3. Verifique o status das filas no Horizon
4. Certifique-se de que as variáveis de ambiente estão configuradas corretamente

## 📞 Suporte

Para dúvidas ou problemas, consulte a documentação do Laravel, Inertia.js ou abra uma issue no repositório.
