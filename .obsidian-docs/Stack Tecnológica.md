# Stack Tecnológica

> Resumo de todas as tecnologias, pacotes e ferramentas usadas no projeto GlowSystem.

Veja também: [[Deploy e Ambiente]] · [[Frontend - Blade e Tailwind]] · [[Tema Visual GlowSystem]]

---

## Backend

| Tecnologia        | Versão   | Papel                                    |
|-------------------|----------|------------------------------------------|
| PHP               | ^8.2     | Linguagem principal                      |
| Laravel           | ^11.0    | Framework MVC                            |
| Livewire          | ^3.0     | Componentes reativos server-side         |
| Laravel Sanctum   | ^4.0     | API token auth (instalado, não em uso ainda) |
| Laravel Tinker    | ^2.9     | REPL para debug                          |
| Carbon            | (Laravel)| Manipulação de datas                     |
| SQLite            | —        | Banco de dados (dev e produção no Vercel)|

### Pacotes de Dev

| Pacote              | Uso                     |
|---------------------|-------------------------|
| FakerPHP            | Seeders e factories      |
| Laravel Pint        | Formatação de código PHP |
| Laravel Sail        | Docker (opcional)        |
| PHPUnit             | Testes automatizados     |
| Mockery             | Mocks em testes          |
| nunomaduro/collision| Erros bonitos no terminal|

---

## Frontend

| Tecnologia     | Versão   | Papel                                       |
|----------------|----------|---------------------------------------------|
| Blade          | (Laravel)| Template engine principal                   |
| Tailwind CSS   | ^3.4     | Utilitário CSS — dark mode via classe `dark`|
| Alpine.js      | ^3.13    | Reatividade leve no frontend                |
| Alpine Collapse| ^3.13    | Plugin de animação collapse do Alpine       |
| Flatpickr      | ^4.6     | Date/time picker                            |
| SweetAlert2    | ^11.10   | Diálogos de confirmação                     |
| Axios          | ^1.6     | Requisições AJAX                            |
| Vite           | ^5.0     | Bundler e dev server                        |

### Build

```bash
npm run dev    # servidor de desenvolvimento com HMR
npm run build  # bundle de produção (obrigatório antes do deploy)
```

---

## Serviços Externos (configuráveis via `.env`)

| Serviço           | Variável de Ambiente           | Status          |
|-------------------|-------------------------------|-----------------|
| WhatsApp API      | `WHATSAPP_API_URL` / `_TOKEN` | Integração futura|
| MercadoPago       | `MERCADOPAGO_ACCESS_TOKEN`    | Integração futura|
| SMTP / Mailpit    | `MAIL_*`                      | Configurado localmente|

---

## Arquitetura Geral

```
┌─────────────────────────────────────────────┐
│                  Browser                    │
│  Alpine.js  +  Tailwind  +  Flatpickr       │
└────────────────────┬────────────────────────┘
                     │ HTTP / AJAX (Axios)
┌────────────────────▼────────────────────────┐
│              Laravel 11 (PHP 8.2)           │
│  Routes → Middleware → Controllers           │
│  Models (Eloquent ORM) → SQLite             │
│  Blade Views ← Livewire Components           │
└─────────────────────────────────────────────┘
```

---

## Estrutura de Pastas Relevante

```
app/
  Http/Controllers/     → Controllers de cada feature
  Models/               → 18 modelos Eloquent
  Services/             → LogAtividadeService
resources/
  views/
    layouts/            → app.blade.php (painel), guest.blade.php
    panel/              → Views do painel admin
    auth/               → Login, registro, gateway
    booking/            → Página pública de agendamento
    funcionario/        → Painel do funcionário
database/
  migrations/           → 22 migrations numeradas
routes/
  web.php               → Todas as rotas (231 linhas)
```

---

*Última atualização: 2026-06-06*
