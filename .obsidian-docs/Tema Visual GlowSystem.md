# Tema Visual GlowSystem

> Identidade visual do SaaS. Design system baseado em Tailwind CSS 3 com paleta verde, dark mode e fonte Outfit.

Veja também: [[Frontend - Blade e Tailwind]] · [[Stack Tecnológica]]

---

## Paleta de Cores

### Cor Primária — Verde

Configurada como extensão do Tailwind em `tailwind.config.js`:

| Token          | Hex       | Uso principal                    |
|----------------|-----------|----------------------------------|
| `primary-50`   | `#f0fdf4` | Backgrounds suaves               |
| `primary-100`  | `#dcfce7` | Hover de cards                   |
| `primary-200`  | `#bbf7d0` | Bordas destacadas                |
| `primary-300`  | `#86efac` | Ícones secundários               |
| `primary-400`  | `#4ade80` | Badges e tags                    |
| `primary-500`  | `#22c55e` | Cor principal (botões, links)    |
| `primary-600`  | `#16a34a` | Hover de botões primários        |
| `primary-700`  | `#15803d` | Estado ativo                     |
| `primary-800`  | `#166534` | Texto em fundo claro             |
| `primary-900`  | `#14532d` | Títulos em modo dark             |

### Paleta Complementar (Tailwind padrão)
- **Cinzas:** `gray-50` a `gray-950` — backgrounds e textos
- **Violeta:** destaque para notificações de agendamento online
- **Amarelo:** alertas de estoque baixo
- **Vermelho:** erros, cancelamentos, status "faltou"

---

## Tipografia

| Elemento    | Fonte   | Peso       | Tamanho padrão |
|-------------|---------|------------|----------------|
| Body        | Outfit  | 400        | `text-sm`      |
| Títulos H1  | Outfit  | 700 (bold) | `text-2xl`     |
| Labels      | Outfit  | 500        | `text-xs`      |
| Números KPI | Outfit  | 700        | `text-3xl`     |

Fonte carregada via `<link>` no layout: `https://fonts.googleapis.com/css2?family=Outfit`

---

## Dark Mode

- Ativado via classe `dark` no elemento `<html>`
- Toggle persistido em `localStorage`
- Padrão visual: fundo `gray-900`, texto `gray-100`, cards `gray-800`

---

## Ícones

**Font Awesome** (versão Free) para todos os ícones:
- Sidebar navigation: `fa-calendar`, `fa-users`, `fa-scissors`, etc.
- Notificações: `fa-calendar-plus`, `fa-bell`
- Status de agendamento: `fa-check-circle`, `fa-times-circle`

---

## Componentes Visuais Padrão

### Cards de KPI (Dashboard)
```html
<div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
    <div class="text-3xl font-bold text-primary-600">R$ 1.200</div>
    <div class="text-sm text-gray-500 dark:text-gray-400">Faturamento hoje</div>
</div>
```

### Badges de Status

| Status        | Classe Tailwind                              |
|---------------|----------------------------------------------|
| `pendente`    | `bg-yellow-100 text-yellow-800`              |
| `confirmado`  | `bg-blue-100 text-blue-800`                  |
| `concluido`   | `bg-green-100 text-green-800`                |
| `cancelado`   | `bg-red-100 text-red-800`                    |
| `faltou`      | `bg-gray-100 text-gray-800`                  |

### Botão Primário
```html
<button class="bg-primary-600 hover:bg-primary-700 text-white font-medium px-4 py-2 rounded-lg transition-colors">
    Ação
</button>
```

### Sombras de Botão (`shadow-{cor}-900/XX`, nunca `-100`/`-200`)

> [!WARNING]
> Bug visual corrigido (2026-06-06): vários botões "premium" (`btn-premium`, `bg-emerald-600`) usavam `shadow-{cor}-100`/`-200` (ex.: `shadow-xl shadow-emerald-200`, `shadow-xl shadow-violet-200`). Em fundo escuro, sombras com tons claros/pastéis criam um **halo brilhante** ao redor do botão (efeito de "glow" indesejado). O padrão correto do tema dark é sombra escura com opacidade: `shadow-{cor}-900/20` a `/40` (ex.: `shadow-green-900/30`, `shadow-emerald-900/30`), igual ao usado nos demais botões da plataforma. Corrigido em: `assinaturas/index`, `bloquear-horarios`, `agendamentos-recorrentes`, `contas/index`, `whatsapp/mensagens`, `comanda-detalhes`.

### Tabelas
- Cabeçalhos: `bg-gray-50 dark:bg-gray-700`
- Linhas: `hover:bg-gray-50 dark:hover:bg-gray-700`
- Bordas: `divide-y divide-gray-200 dark:divide-gray-700`

---

## Nome do Sistema

O produto se chama **GlowSystem** — nome de marca usado nas views, e-mails e documentação. O repositório usa "babiearia" (typo do original "barbearia") no nome do projeto.

---

*Última atualização: 2026-06-06*
