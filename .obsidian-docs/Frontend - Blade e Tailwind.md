# Frontend - Blade e Tailwind

> Interface construída com Blade (Laravel), Tailwind CSS 3 e Alpine.js. Design system unificado com dark mode nativo.

Veja também: [[Tema Visual GlowSystem]] · [[Stack Tecnológica]] · [[Rotas e Controllers]]

---

## Estrutura de Views

```
resources/views/
├── layouts/
│   ├── app.blade.php       → Layout principal do painel admin
│   ├── guest.blade.php     → Layout de páginas públicas (login, registro)
│   └── partials/           → Fragmentos reutilizáveis (sidebar, nav, etc.)
│
├── auth/
│   ├── gateway.blade.php   → Escolha de tipo de login (admin vs funcionário)
│   ├── login.blade.php     → Login do proprietário
│   └── register.blade.php  → Cadastro de nova barbearia
│
├── booking/
│   └── index.blade.php     → Página pública de agendamento (multi-step)
│
├── funcionario/
│   ├── login.blade.php     → Login por código de acesso
│   └── dashboard.blade.php → Agenda do dia + finalizar atendimento
│
├── panel/
│   ├── dashboard.blade.php         → KPIs e agenda do dia
│   ├── agendamentos.blade.php      → Lista de agendamentos com filtros
│   ├── agendamentos-recorrentes.blade.php → CRUD de agendamentos fixos
│   ├── profissionais.blade.php     → CRUD de profissionais
│   ├── servicos.blade.php          → CRUD de serviços com imagem
│   ├── produtos.blade.php          → CRUD de produtos com imagem e estoque
│   ├── clientes.blade.php          → Lista de clientes
│   ├── comandas.blade.php          → Lista de comandas
│   ├── comanda-detalhes.blade.php  → Detalhes e itens de uma comanda
│   ├── expedientes.blade.php       → Horários de trabalho
│   ├── bloquear-horarios.blade.php → Bloqueios de horário
│   ├── logs.blade.php              → Log de atividades
│   ├── meu-plano.blade.php         → Plano SaaS da barbearia
│   ├── assinaturas/                → Clube VIP
│   ├── configuracoes/              → Configurações do sistema
│   ├── financeiro/                 → Dashboard financeiro + gráficos
│   ├── relatorios/                 → Relatórios analíticos
│   └── whatsapp/                   → Mensagens e recarga de saldo
│
└── admin/
    └── (painel super admin da plataforma)
```

---

## Layout Principal (`layouts/app.blade.php`)

O layout do painel tem **25 KB** e inclui:
- Sidebar com navegação completa
- Topbar com notificações e perfil do usuário
- Dark mode toggle (classe `dark` no `<html>`)
- Sistema de alertas flash (`session('success')` / `session('error')`)
- Importação dos assets via `@vite`

---

## Bibliotecas JS Utilizadas

| Lib          | Uso no projeto                                      |
|--------------|-----------------------------------------------------|
| **Alpine.js**| Toggle de modais, dropdowns, tabs, reatividade leve |
| **Flatpickr**| Seletor de data/hora nos formulários de agendamento |
| **SweetAlert2** | Diálogos de confirmação (ex: "Tem certeza?")    |
| **Axios**    | Chamadas AJAX (ex: `check-vip`, carregamento dinâmico)|

### Exemplo de uso Alpine em modal
```html
<div x-data="{ open: false }">
    <button @click="open = true">Abrir</button>
    <div x-show="open" @click.away="open = false">...</div>
</div>
```

---

## Dark Mode

Configurado via `darkMode: 'class'` no `tailwind.config.js`. O toggle adiciona/remove a classe `dark` no `<html>`.

Classes dark mode são usadas em toda a interface:
```html
class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
```

---

## Tipografia

Fonte principal: **Outfit** (Google Fonts), configurada como `font-sans` no Tailwind:
```js
fontFamily: { sans: ['Outfit', 'ui-sans-serif', 'system-ui'] }
```

---

## Página de Agendamento Público (`booking/index.blade.php`)

Interface multi-step para clientes externos:
1. Selecionar profissional (ou ver todos)
2. Selecionar serviço
3. Selecionar data e hora (Flatpickr)
4. Informar nome e telefone
5. Verificação VIP automática via `GET /api/check-vip`
6. Confirmação com SweetAlert2

Suporta slug de barbearia (`/agendar/{slug}`) e link exclusivo de profissional (`?funcionario={id}`).

---

## Padrões de Formulário

- Todos os formulários usam `@csrf`
- Erros exibidos com `@error('campo')` / `$errors->first()`
- Flash messages via `session('success')` no layout
- Modais de confirmação de delete via SweetAlert2

---

*Última atualização: 2026-06-06*
