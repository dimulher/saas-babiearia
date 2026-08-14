# Agendamentos

> Módulo central do SaaS GlowSystem. Gerencia o ciclo completo de reservas — do agendamento online pelo cliente até a conclusão pelo profissional.

Relacionado: [[Banco de Dados]] · [[Autenticação e Perfis]] · [[Rotas e Controllers]] · [[Painel do Funcionário]] · [[Página de Agendamento Público]]

---

## Tabelas no Banco de Dados

Veja também: [[Banco de Dados]]

### `agendamentos`

Tabela principal. Cada linha representa um atendimento (passado, presente ou futuro).

| Coluna               | Tipo                  | Descrição                                              |
|----------------------|-----------------------|--------------------------------------------------------|
| `id`                 | bigint (PK)           | Identificador único                                    |
| `barbearia_id`       | FK → barbearias       | Tenant do agendamento (cascade delete)                 |
| `profissional_id`    | FK → profissionais    | Profissional responsável (cascade delete)              |
| `cliente_id`         | FK → clientes (null)  | Cliente cadastrado; `null` para agendamentos avulsos   |
| `servico_id`         | FK → servicos         | Serviço reservado (cascade delete)                     |
| `cliente_nome`       | string (null)         | Nome livre — usado quando não há cadastro              |
| `cliente_telefone`   | string (null)         | Telefone livre — usado quando não há cadastro          |
| `data_inicio`        | datetime              | Início do atendimento                                  |
| `data_fim`           | datetime              | Fim calculado (início + duração do serviço)            |
| `preco`              | decimal(10,2)         | Preço cobrado (pode ser 0 para clientes VIP)           |
| `status`             | enum                  | `pendente` · `confirmado` · `concluido` · `cancelado` · `faltou` |
| `observacoes`        | text (null)           | Campo livre de observações                             |
| `agendado_online`    | boolean               | `true` se veio pela página pública de agendamento      |
| `lembrete_enviado`   | boolean               | Controle de disparo de lembrete (WhatsApp/SMS)         |
| `created_at`         | timestamp             | —                                                      |
| `updated_at`         | timestamp             | —                                                      |
| `google_event_id`    | string (null)         | ID do evento no Google Calendar (salvo via callback Make) |
| `deleted_at`         | timestamp (null)      | Soft-delete habilitado                                 |

> **Arquivo de migration:** `database/migrations/2024_01_01_000006_create_agendamentos_table.php`
> **Migration de adição:** `2026_06_11_000001_add_google_event_id_to_agendamentos_table.php`

---

### `agendamentos_recorrentes`

Agendamentos fixos semanais (ex: "toda segunda às 10h"). Não geram entradas automáticas em `agendamentos` — servem como template para o operador confirmar.

| Coluna            | Tipo               | Descrição                                |
|-------------------|--------------------|------------------------------------------|
| `id`              | bigint (PK)        | —                                        |
| `barbearia_id`    | FK → barbearias    | Tenant (cascade delete)                  |
| `profissional_id` | FK → profissionais | Profissional fixo (cascade delete)       |
| `cliente_id`      | FK → clientes      | Cliente obrigatório (cascade delete)     |
| `servico_id`      | FK → servicos      | Serviço fixo (cascade delete)            |
| `dia_semana`      | tinyint            | 0 = Dom, 1 = Seg, …, 6 = Sáb            |
| `hora`            | time               | Horário fixo do atendimento              |
| `ativo`           | boolean            | Permite pausar sem excluir               |
| `created_at`      | timestamp          | —                                        |
| `updated_at`      | timestamp          | —                                        |

> **Arquivo de migration:** `database/migrations/2024_01_01_000012_create_agendamentos_recorrentes_table.php`

---

## Rotas Principais

Veja também: [[Rotas e Controllers]] · [[Autenticação e Perfis]]

### Públicas (sem autenticação)

| Método | URI                               | Controller / Ação                           | Nome                  | Descrição                                          |
|--------|-----------------------------------|---------------------------------------------|-----------------------|----------------------------------------------------|
| GET    | `/agendar/{slug?}`                | closure (inline)                            | `booking`             | Página pública de agendamento por slug da barbearia|
| POST   | `/agendar`                        | `AgendamentoController@store`               | `booking.store`       | Cria novo agendamento vindo da página pública      |
| GET    | `/webhooks/check-vip`             | `AgendamentoController@checkVip`            | `api.check-vip`       | Verifica se o telefone tem assinatura VIP ativa    |
| GET    | `/b/{slug}`                       | redirect → `booking`                        | —                     | Alias legado de slug curto                         |
| POST   | `/webhooks/google-calendar/sync`  | `GoogleCalendarSyncController@store`        | `api.google-calendar.sync` | Recebe eventos do Calendar via Make.com (token) |
| POST   | `/webhooks/google-calendar/event-id` | `GoogleCalendarSyncController@storeEventId` | `api.google-calendar.event-id` | Callback do Make: salva `google_event_id` após criar evento |

### Painel Interno (middleware `auth`)

Prefixo: `/panel` — Nome base: `panel.`

| Método | URI                                          | Controller / Ação                     | Nome                           | Descrição                                        |
|--------|----------------------------------------------|---------------------------------------|--------------------------------|--------------------------------------------------|
| GET    | `/panel/agendamentos`                        | `AgendamentoController@index`         | `panel.agendamentos`           | Lista agendamentos do dia (com filtros)          |
| PATCH  | `/panel/agendamentos/{agendamento}/status`   | `AgendamentoController@updateStatus`  | `panel.agendamentos.status`    | Altera status (pendente/confirmado/cancelado/faltou) e dispara sync Calendar |

#### Agendamentos Recorrentes

> Rotas ainda **não registradas** em `web.php` — o controller `AgendamentoRecorrenteController` existe mas as rotas precisam ser adicionadas.

| Método | URI (sugerida)                              | Controller / Ação                               | Descrição                       |
|--------|---------------------------------------------|-------------------------------------------------|---------------------------------|
| GET    | `/panel/agendamentos-recorrentes`           | `AgendamentoRecorrenteController@index`         | Lista recorrentes               |
| POST   | `/panel/agendamentos-recorrentes`           | `AgendamentoRecorrenteController@store`         | Cria novo recorrente            |
| DELETE | `/panel/agendamentos-recorrentes/{rec}`     | `AgendamentoRecorrenteController@destroy`       | Remove recorrente               |
| PATCH  | `/panel/agendamentos-recorrentes/{rec}/toggle` | `AgendamentoRecorrenteController@toggle`     | Ativa/desativa recorrente       |

---

## Controllers

### `AgendamentoController`

| Método           | Descrição                                                                                 |
|------------------|-------------------------------------------------------------------------------------------|
| `index()`        | Lista agendamentos do dia filtrados por `profissional_id` e/ou `status`                   |
| `store()`        | Valida e cria agendamento online; aplica desconto VIP; dispara `Notificacao` e sync Calendar (`action=created`) |
| `updateStatus()` | PATCH — altera `status` do agendamento; dispara sync Calendar (`updated` ou `cancelled`)  |
| `checkVip()`     | API que verifica se o telefone informado possui assinatura ativa (retorna JSON)           |

**Lógica VIP em `store()`:**
1. Recebe flag `is_vip` do front
2. Busca cliente pelo telefone (normalizado, sem formatação)
3. Consulta `Assinatura` ativa com `plano` carregado
4. Se válido → `preco = 0` e prefixo `[CLIENTE VIP]` na descrição

### `AgendamentoRecorrenteController`

| Método      | Descrição                                              |
|-------------|--------------------------------------------------------|
| `index()`   | Lista todos os recorrentes da barbearia autenticada    |
| `store()`   | Cria novo recorrente com validação de segurança (tenant)|
| `destroy()` | Remove recorrente e registra no log de atividades      |
| `toggle()`  | Ativa ou desativa recorrente sem excluir               |

---

## Fluxo de Agendamento Online

```
Cliente acessa /agendar/{slug}
    └─► Seleciona profissional, serviço, data/hora
    └─► Digita telefone → checkVip() verifica status VIP
    └─► POST /agendar → AgendamentoController@store
            ├─ Calcula data_fim (data_inicio + duracao_minutos)
            ├─ Aplica desconto VIP se elegível
            ├─ Salva em agendamentos (agendado_online = true)
            └─ Cria Notificacao no painel do proprietário
```

---

## Integração com Google Calendar (via Make.com)

> Sincronização bidirecional automática entre a plataforma e o Google Calendar. O profissional nunca precisa abrir o Calendar — tudo acontece automaticamente.

### Sentido 1 — Plataforma → Google Calendar (escrita)

```
Evento gerado por:
  - AgendamentoController@store         → action=created
  - AgendamentoController@updateStatus  → action=updated | cancelled
  - FuncionarioController@finalizar     → action=updated  (status → concluido)

    └─► App\Jobs\SyncAgendamentoToGoogleCalendar::dispatch($agendamento->id, $action)
            └─► POST para webhook do Make.com (services.make.agendamento_webhook)
                    └─► Cenário Make 4771510 "Agendamento → Google Calendar (Barbearia)"
                            ├─ Módulo 1: gateway:CustomWebHook (hook 2738899)
                            └─ Módulo 2: builtin:BasicRouter → 3 ramos
                                    ├─ Ramo A [filtro: action=created]
                                    │       ├─ google-calendar:createAnEvent
                                    │       └─ http:ActionSendData → POST /webhooks/google-calendar/event-id
                                    │               └─► GoogleCalendarSyncController@storeEventId
                                    │                       └─► agendamentos.google_event_id = event.id
                                    ├─ Ramo B [filtro: action=updated + google_event_id existe]
                                    │       └─ google-calendar:updateAnEvent (eventId = google_event_id)
                                    └─ Ramo C [filtro: action=cancelled + google_event_id existe]
                                            └─ google-calendar:deleteAnEvent (eventId = google_event_id)
```

- **Job:** `app/Jobs/SyncAgendamentoToGoogleCalendar.php` — envia `agendamento_id`, `cliente_nome`, `cliente_telefone`, `servico_nome`, `profissional_nome`, `data_inicio`, `data_fim`, `observacoes`, `status`, `action` e `google_event_id` via `Http::post()`.
- **Config:** `MAKE_AGENDAMENTO_WEBHOOK_URL` → `config/services.php` → `services.make.agendamento_webhook`.
- **Make.com:** org 462751, time 181064, pasta 320545, cenário **4771510**, hook **2738899**. Conexão Google: `vitorjpereira.12@gmail.com`.
- **Token callback:** `X-Calendar-Sync-Token` = mesmo token de `MAKE_CALENDAR_SYNC_TOKEN` — o Make envia no header para `/webhooks/google-calendar/event-id`.

**⚠️ Polling, não instantâneo:** cenário usa `scheduling: {"type": "indefinitely", "interval": 60}` — Make impõe ciclo mínimo de ~3 min. Evento aparece no Calendar em até 3 min após agendamento.

**Agendamentos sem `google_event_id`:** Ramos B e C só executam se `google_event_id` existir (filtro no Make). Agendamentos criados antes da implementação (2026-06-11) não sincronizam updates/cancelamentos.

---

### Sentido 2 — Google Calendar → Plataforma (leitura)

```
Cenário Make 4771604 (polling ~3min)
    └─► Módulo 1: util:BasicTrigger (gatilho agendado)
    └─► Módulo 2: google-calendar:searchEvents — próximos 45 dias do calendário primário
    └─► Módulo 3: json:CreateJSON — monta payload com data structure 281809
    └─► Módulo 4: POST https://saas-babiearia.vercel.app/webhooks/google-calendar/sync
            └─► GoogleCalendarSyncController@store
                    ├─ Autentica via header X-Calendar-Sync-Token
                    ├─ updateOrCreate em eventos_google_calendar (por barbearia_id + google_event_id)
                    └─ Se status = 'cancelled' → deleta o registro local
```

- **Endpoint:** `POST /webhooks/google-calendar/sync` (fora de `/api/` — ver [[Deploy e Ambiente]] sobre conflito do Vercel com `/api/`).
- **Autenticação:** header `X-Calendar-Sync-Token` — token compartilhado em `MAKE_CALENDAR_SYNC_TOKEN` / `config/services.php` → `services.make.calendar_sync_token`.
- **Controller:** `app/Http/Controllers/GoogleCalendarSyncController.php` — upsert idempotente (cada ciclo do Make reenvia todos os eventos dos próximos 45 dias sem dano).
- **Model:** `app/Models/EventoGoogleCalendar.php` → tabela `eventos_google_calendar`.
- **Make.com:** cenário **4771604**, data structure **281809** ("Evento Calendar Sync (Barbearia)").
- **CSRF:** excluído em `bootstrap/app.php` → `validateCsrfTokens(except: ['webhooks/google-calendar/sync'])`.

#### Tabela `eventos_google_calendar`

| Coluna            | Tipo                       | Descrição                                          |
|-------------------|----------------------------|----------------------------------------------------|
| `id`              | bigint (PK)                | —                                                  |
| `barbearia_id`    | unsignedBigInteger         | Tenant — **sem FK constraint** (ver nota abaixo)   |
| `google_event_id` | string                     | ID do evento no Google Calendar                    |
| `titulo`          | string (null)              | Campo `summary` do evento                          |
| `descricao`       | text (null)                | Campo `description` do evento                      |
| `inicio`          | datetime                   | Início do evento                                   |
| `fim`             | datetime                   | Fim do evento                                      |
| `dia_inteiro`     | boolean                    | Evento de dia inteiro?                             |
| `status`          | string (null)              | `confirmed`, `tentative`, `cancelled`              |
| `created_at`      | timestamp                  | —                                                  |
| `updated_at`      | timestamp                  | —                                                  |

- **Unique composto:** `(barbearia_id, google_event_id)` — um mesmo evento pode existir para barbearias diferentes.
- **Sem FK em barbearia_id** — a FK original (`constrained('barbearias')`) foi removida na migration de correção `2026_06_07_000001` porque a FK do Supabase bloqueava o INSERT em produção quando a migration original não havia sido aplicada.

> **Migrations:**
> - `2026_06_06_180000_create_eventos_google_calendar_table.php` — criação original (com FK, pode não ter rodado em produção)
> - `2026_06_07_000001_fix_eventos_google_calendar_table.php` — recria sem FK, com unique composto ✅

#### Gotchas do Make Cenário 4771604

> [!WARNING]
> **Eventos especiais do Google Calendar (Out of Office, Working Location, Focus Time, birthdays) retornam `null` para todos os campos** no módulo `google-calendar:searchEvents`. O cenário foi atualizado para filtrar apenas `eventTypes: ["default"]` e usar `formatDate(2.start; "YYYY-MM-DDTHH:mm:ss")` para garantir conversão correta das datas.

**Configuração atual do cenário (2026-06-07):**
- Módulo 2 (`searchEvents`): `eventTypes: ["default"]` — filtra eventos especiais
- Módulo 3 (`json:CreateJSON`): `inicio` e `fim` usam `formatDate` explícito
- Módulo 3: filtro "Apenas eventos com ID" (`{{2.id}} exist`) para pular registros inválidos

---

## Status do Agendamento

```
pendente → confirmado → concluido
    └─────────────────→ cancelado
    └─────────────────→ faltou
```

- **`pendente`**: criado, aguardando confirmação
- **`confirmado`**: aceito pelo profissional/admin
- **`concluido`**: atendimento realizado (o `FuncionarioController@finalizar` faz a transição)
- **`cancelado`**: cancelado por qualquer parte
- **`faltou`**: cliente não compareceu

---

## Dependências e Relações

- [[Banco de Dados]] — tabelas `barbearias`, `profissionais`, `clientes`, `servicos`
- [[Autenticação e Perfis]] — `auth()` middleware protege rotas do painel; `barbearia_id` no user define o tenant
- [[Painel do Funcionário]] — `FuncionarioController@finalizar` conclui agendamentos
- [[Clube de Assinatura]] — `Assinatura` e `Plano` são consultados para validar benefício VIP
- **Make.com / Google Calendar** — `App\Jobs\SyncAgendamentoToGoogleCalendar` sincroniza criação de agendamento com o Calendar do profissional (ver seção "Integração com Google Calendar" acima)

---

## Pendências / TODOs

- [x] ~~Exibir `eventos_google_calendar` na tela `/panel/agendamentos` (visão unificada)~~ ✅ 2026-06-06
- [x] ~~Sincronização Google Calendar → Plataforma (Make cenário 4771604)~~ ✅ 2026-06-07
- [x] ~~Sincronizar updates/cancelamentos com o Google Calendar~~ ✅ 2026-06-11 — `google_event_id` salvo via callback Make, Make cenário 4771510 atualizado com router 3 ramos
- [x] ~~Alterar status de agendamentos pelo painel~~ ✅ 2026-06-11 — `AgendamentoController@updateStatus` + botões na view
- [ ] Registrar rotas de `agendamentos-recorrentes` no `web.php`
- [ ] Implementar geração automática de `agendamentos` a partir dos recorrentes (command/scheduler)
- [ ] Adicionar envio de lembrete (campo `lembrete_enviado` já existe na tabela)
- [ ] Soft-delete: criar rota de restauração de agendamentos cancelados

---

*Última atualização: 2026-06-11 — sync Google Calendar completo (3 ações: created/updated/cancelled), `google_event_id` na tabela agendamentos, `updateStatus()` + botões de status no painel, Make cenário 4771510 atualizado com router 3 ramos + callback endpoint*
