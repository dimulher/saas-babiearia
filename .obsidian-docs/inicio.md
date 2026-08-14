# Início — SaaS GlowSystem (Barbearia)

> Nota índice do projeto. Navega pelo grafo clicando nos links abaixo. Última auditoria completa: **2026-06-06**.

---

## Visão Geral

- [[Stack Tecnológica]] — PHP 8.2, Laravel 11, Livewire, Alpine.js, Tailwind, SQLite
- [[Deploy e Ambiente]] — Vercel + vercel-php, variáveis de ambiente, build local

---

## Backend

- [[Banco de Dados]] — 18 modelos, todas as tabelas e relações documentadas
- [[Rotas e Controllers]] — Mapa completo de todas as 60+ rotas e 17 controllers
- [[Autenticação e Perfis]] — Laravel Auth (admin) + Código de Acesso (funcionário)

---

## Frontend

- [[Frontend - Blade e Tailwind]] — Estrutura de views, Alpine.js, Flatpickr, SweetAlert2
- [[Tema Visual GlowSystem]] — Paleta verde, dark mode, tipografia Outfit, componentes

---

## Funcionalidades

- [[Agendamentos]] — Schema, rotas, fluxo online, status, recorrentes ⭐
- [[Financeiro]] — Comandas, contas, FinanceiroController, relatórios
- [[Clube de Assinatura]] — Planos VIP, assinaturas, integração com agendamento
- [[Painel do Funcionário]] — Código de acesso, finalizar atendimento
- [[Página de Agendamento Público]] — Multi-step, verificação VIP, slug único

---

## Operação

- [[Deploy e Ambiente]] — Variáveis, Vercel, limitações de produção, gotchas de sessão/CSRF
- [[Make.com - Automações]] — Cenários ativos, receitas de blueprint, gotchas ⭐
- [[Bugs e Correções]] — Registro de todos os bugs resolvidos + causa raiz ⭐
- [[Padrões de Código]] — Snippets reutilizáveis: cards, badges, AJAX, modais, controllers ⭐
- [[Logs e Monitoramento]] — (a criar) `LogAtividadeService`, tabela `logs_atividades`

---

## Pendências do Projeto

> [!NOTE]
> **Agendamentos Recorrentes:** rotas registradas e view criada em 2026-06-13. Controller, rotas e view completos. ✅

> [!IMPORTANT]
> **Integrações pendentes:** WhatsApp API e MercadoPago estão no `.env` mas não implementados.

> [!TIP]
> **Para produção:** configurar storage externo (S3/R2) para imagens e resolver persistência do SQLite no Vercel.

---

## Notas Planejadas (ainda não criadas)

- `Logs e Monitoramento` — `LogAtividadeService` + tabela `logs_atividades`
- `Sobre o Projeto` — Contexto e objetivo do negócio
- `Roadmap` — Próximas funcionalidades
- `Diário de Desenvolvimento` — Registro cronológico
- `Decisões Técnicas` — ADRs
- `Bugs e Correções` — Tracker de issues

---

*Vault atualizado em: 2026-06-11 — Responsividade mobile: breakpoints, tabelas, dropdown, tabs, grids documentados em [[Padrões de Código]]*
