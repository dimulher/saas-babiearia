# Deploy e Ambiente

> Configuração de ambiente, variáveis de ambiente e estratégia de deploy no Vercel.

Veja também: [[Stack Tecnológica]] · [[Banco de Dados]]

---

## Ambientes

| Ambiente | Driver DB | URL                          | Status       |
|----------|-----------|------------------------------|--------------|
| Local    | SQLite    | `http://127.0.0.1:8000`      | ✅ Ativo      |
| Produção | SQLite    | Vercel (vercel-php runtime)  | ⚠️ Configurar|

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

### Limitações no Vercel
- **SQLite:** o arquivo `database.sqlite` deve ser versionado ou a DB precisa ser recriada a cada deploy
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

## Variáveis de Ambiente a Configurar para Produção

| Variável                    | O que é                                    |
|-----------------------------|--------------------------------------------|
| `APP_KEY`                   | Gerar com `php artisan key:generate`       |
| `APP_URL`                   | URL da produção no Vercel                  |
| `APP_ENV`                   | Mudar para `production`                    |
| `APP_DEBUG`                 | Mudar para `false`                         |
| `WHATSAPP_API_URL`          | URL da instância Z-API ou Evolution API    |
| `WHATSAPP_API_TOKEN`        | Token de autenticação da API               |
| `MERCADOPAGO_ACCESS_TOKEN`  | Token da conta do MercadoPago              |

---

*Última atualização: 2026-06-06*
