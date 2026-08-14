# Deploy e Ambiente

> Configuração de ambiente, variáveis de ambiente e estratégia de deploy no Vercel.

Veja também: [[Stack Tecnológica]] · [[Banco de Dados]]

---

## Ambientes

| Ambiente | Driver DB         | URL                                          | Status   |
|----------|-------------------|----------------------------------------------|----------|
| Local    | SQLite            | `http://127.0.0.1:8000`                      | ✅ Ativo  |
| Produção | PostgreSQL (Supabase) | `https://saas-babiearia.vercel.app`      | ✅ Ativo  |

---

## Arquivo `.env` (Local)

```env
APP_NAME="SaaS Barbearia"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

LOG_CHANNEL=stderr
LOG_LEVEL=error

SESSION_DRIVER=cookie
SESSION_LIFETIME=120
CACHE_DRIVER=array
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local

# WhatsApp API (não configurado)
WHATSAPP_API_URL=
WHATSAPP_API_TOKEN=
WHATSAPP_INSTANCE=

# MercadoPago (não configurado)
MERCADOPAGO_ACCESS_TOKEN=
MERCADOPAGO_PUBLIC_KEY=
```

---

## Deploy no Vercel

Configurado via `vercel.json` com o runtime `vercel-php@0.7.2`.

### Como funciona

```
vercel build
    └─► npm run build  (assets Vite → public/build/)
    └─► PHP via runtime vercel-php

Todas as rotas → api/index.php → Laravel bootstrap
```

### Estrutura de rotas no `vercel.json`

| Source           | Destino                |
|------------------|------------------------|
| `/build/(.*)`    | `public/build/$1`      |
| `/css/(.*)`      | `public/css/$1`        |
| `/js/(.*)`       | `public/js/$1`         |
| `/images/(.*)`   | `public/images/$1`     |
| `/favicon.ico`   | `public/favicon.ico`   |
| `/(.*)`          | `api/index.php` (Laravel)|

### Cache de Assets
Assets buildados recebem header `Cache-Control: public, max-age=31536000, immutable` (1 ano).

### Cold Start e Auto-migrate

O `api/index.php` detecta se é o primeiro request do container (verifica se `/tmp/routes.php` e `/tmp/config.php` existem) e, se não existirem, roda automaticamente:

```php
$console->call('migrate', ['--force' => true]);  // cria tabelas no Supabase se não existirem
$console->call('config:cache');
$console->call('route:cache');
$console->call('view:cache');
```

Isso garante que novas migrations sejam aplicadas automaticamente em produção sem intervenção manual.

### ⚠️ Conflito de roteamento: não usar prefixo `/api/` em rotas Laravel

O runtime `vercel-php` trata caminhos iniciados em `/api/` de forma especial (o entry point está em `api/index.php`), stripando o prefixo antes de passar para o PHP. Resultado: `POST /api/foo` chega no Laravel como `POST /foo` → rota não encontrada.

**Regra:** rotas externas (webhooks, endpoints sem sessão) devem usar o prefixo `/webhooks/` em vez de `/api/`.

Exemplo: `POST /webhooks/google-calendar/sync` ✅ — `POST /api/google-calendar/sync` ❌

### Limitações no Vercel
- **Filesystem:** uploads de imagens (`Storage::disk('local')`) não persistem entre deploys — necessário configurar S3/Cloudflare R2 para produção
- **Queue:** `QUEUE_CONNECTION=sync` — jobs executam de forma síncrona
- **MaxDuration:** 30 segundos por requisição

---

## Executando Localmente

```bash
# Instalar dependências
composer install
npm install

# Configurar banco
php artisan migrate
php artisan db:seed  # se houver seeders

# Iniciar servidores
php artisan serve    # backend em :8000
npm run dev          # Vite HMR em :5173
```

---

## Variáveis de Ambiente em Produção (Vercel)

| Variável                    | O que é                                                          | Status   |
|-----------------------------|------------------------------------------------------------------|----------|
| `APP_KEY`                   | Chave de criptografia Laravel                                    | ✅ Configurado |
| `APP_URL`                   | `https://saas-babiearia.vercel.app`                             | ✅ Configurado |
| `APP_ENV`                   | `production`                                                     | ✅ Configurado |
| `APP_DEBUG`                 | `false`                                                          | ✅ Configurado |
| `DB_CONNECTION`             | `pgsql` (Supabase)                                               | ✅ Configurado |
| `DB_HOST`                   | `aws-1-sa-east-1.pooler.supabase.com`                           | ✅ Configurado |
| `DB_PORT`                   | `6543` (Transaction Pooler)                                      | ✅ Configurado |
| `DB_USERNAME`               | `postgres.hywqwshhfwwqqpogknoi` — **formato obrigatório com project-ref** | ✅ Configurado |
| `DB_PASSWORD`               | Senha do Supabase (redefinida em 2026-06-06)                     | ✅ Configurado |
| `DB_DATABASE`               | `postgres`                                                       | ✅ Configurado |
| `MAKE_AGENDAMENTO_WEBHOOK_URL` | URL do webhook Make.com (cenário 4771510)                    | ✅ Configurado |
| `MAKE_CALENDAR_SYNC_TOKEN`  | Token para autenticar POSTs do Make → `/webhooks/google-calendar/sync` | ✅ Configurado |
| `SESSION_DRIVER`            | `cookie` — obrigatório em serverless (padrão é `database`, sem tabela) | ✅ Configurado |
| `MAIL_MAILER`               | `resend` — pacote `resend/resend-laravel` já instalado           | ⚙️ Configurar |
| `MAIL_FROM_ADDRESS`         | `noreply@glowsystem.com.br` (ou domínio verificado no Resend)   | ⚙️ Configurar |
| `MAIL_FROM_NAME`            | `GlowSystem`                                                     | ⚙️ Configurar |
| `RESEND_KEY`                | API key obtida em resend.com → API Keys                          | ⚙️ Configurar |
| `WHATSAPP_API_URL`          | URL da instância Z-API ou Evolution API                          | ⏳ Pendente |
| `WHATSAPP_API_TOKEN`        | Token de autenticação da API                                     | ⏳ Pendente |
| `MERCADOPAGO_ACCESS_TOKEN`  | Token da conta do MercadoPago                                    | ⏳ Pendente |

> **Supabase Transaction Pooler:** o username deve ser `postgres.[project-ref]` (não apenas `postgres`). Encontrar a string completa em Supabase → Settings → Database → Connection Pooling → Transaction mode.

---

### ⚠️ Deploy automático: branch `main`, não `master`

O Vercel monitora o branch **`main`** do repositório `dimulher/saas-babiearia`. Commits enviados ao branch `master` **não disparam deploy automático** — é necessário fazer merge em `main` ou redeploy manual pelo painel. O local de trabalho usa branch `master`; sempre executar `git push origin main` após commitar.

---

### ⚠️ `$errors` indefinido em views — causa raiz e fix

O driver de sessão padrão do Laravel é `database`, mas a tabela `sessions` não existe no Supabase. Com isso, `ShareErrorsFromSession` não injeta `$errors` nas views e qualquer diretiva `@error` causa `Undefined variable $errors`.

**Fix aplicado (2026-06-11):** `AppServiceProvider::boot()` registra um view composer global que injeta `ViewErrorBag` vazio como fallback antes de qualquer view renderizar. Isso cobre layouts, views standalone (ex: `funcionario/login.blade.php`) e qualquer view futura.

> ⚠️ Um fix via `@php` no layout (`guest.blade.php`) **não funciona** — o layout roda depois que as seções filho já foram avaliadas, chegando tarde demais.

---

*Última atualização: 2026-06-13 — Resend instalado (`resend/resend-laravel`); MAIL_MAILER=resend; RESEND_KEY pendente no Vercel*
