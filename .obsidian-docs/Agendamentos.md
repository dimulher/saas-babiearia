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
| `deleted_at`         | timestamp (null)      | Soft-delete habilitado                                 |

> **Arquivo de migration:** `database/migrations/2024_01_01_000006_create_agendamentos_table.php`

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

| Método | URI                        | Controller / Ação                           | Nome                  | Descrição                                          |
|--------|----------------------------|---------------------------------------------|-----------------------|----------------------------------------------------|
| GET    | `/agendar/{slug?}`         | closure (inline)                            | `booking`             | Página pública de agendamento por slug da barbearia|
| POST   | `/agendar`                 | `AgendamentoController@store`               | `booking.store`       | Cria novo agendamento vindo da página pública      |
| GET    | `/api/check-vip`           | `AgendamentoController@checkVip`            | `api.check-vip`       | Verifica se o telefone tem assinatura VIP ativa    |
| GET    | `/b/{slug}`                | redirect → `booking`                        | —                     | Alias legado de slug curto                         |

### Painel Interno (middleware `auth`)

Prefixo: `/panel` — Nome base: `panel.`

| Método | URI                              | Controller / Ação                                  | Nome                           | Descrição                                    |
|--------|----------------------------------|----------------------------------------------------|--------------------------------|----------------------------------------------|
| GET    | `/panel/agendamentos`            | `AgendamentoController@index`                      | `panel.agendamentos`           | Lista agendamentos do dia (com filtros)       |

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

| Método      | Descrição                                                                                 |
|-------------|-------------------------------------------------------------------------------------------|
| `index()`   | Lista agendamentos do dia filtrados por `profissional_id` e/ou `status`                   |
| `store()`   | Valida e cria agendamento online; aplica desconto VIP; dispara `Notificacao` para o painel|
| `checkVip()`| API que verifica se o telefone informado possui assinatura ativa (retorna JSON)           |

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

> Sincroniza automaticamente cada novo agendamento online com o Google Calendar do profissional, sem que ele precise abrir o Calendar manualmente.

**Como funciona:**

```
AgendamentoController@store cria o Agendamento
    └─► Dispara App\Jobs\SyncAgendamentoToGoogleCalendar (fila, ShouldQueue)
            └─► POST para webhook do Make.com (config services.make.agendamento_webhook)
                    └─► Cenário Make "Agendamento → Google Calendar (Barbearia)"
                            ├─ Módulo 1: Custom Webhook (gateway:CustomWebHook, hook 2738899)
                            └─ Módulo 2: google-calendar:createAnEvent (conexão vitorjpereira.12@gmail.com)
                                    └─► Evento criado no Google Calendar do profissional
```

- **Job:** `app/Jobs/SyncAgendamentoToGoogleCalendar.php` — envia `cliente_nome`, `cliente_telefone`, `servico_nome`, `profissional_nome`, `data_inicio`, `data_fim`, `observacoes`, `status` e `action` (`created`) para o webhook via `Http::post()`.
- **Disparo:** chamado em `AgendamentoController@store` logo após criar a `Notificacao` do painel — `SyncAgendamentoToGoogleCalendar::dispatch($agendamento->id, 'created')`.
- **Config:** URL do webhook fica em `MAKE_AGENDAMENTO_WEBHOOK_URL` (`.env` / `config/services.php` → `services.make.agendamento_webhook`).
- **Make.com:** organização 462751, time 181064, pasta 320545, cenário 4771510, hook 2738899 (conta `jorgemurilho@gmail.com`). Conexão Google usa `vitorjpereira.12@gmail.com` (mais próxima da conta solicitada `vitorj.teste2@gmail.com`, que não existia entre as conexões já autorizadas).

**⚠️ Detalhe importante de configuração — gatilho não é "instant" de fato:**

O cenário foi criado com `metadata.instant: true` e `scheduling: on-demand` (configuração padrão para webhooks instantâneos), mas na prática o listener instantâneo **não disparava sozinho** — as requisições só ficavam acumuladas na fila do hook (`queueCount` crescendo) até serem processadas manualmente. Para resolver isso e garantir automação 100% sem intervenção humana, o agendamento foi trocado para *polling* (`scheduling: {"type": "indefinitely", "interval": 60}`). O Make ajustou sozinho para um ciclo mínimo de ~3 minutos (`nextExec` sempre 180s à frente, mesmo pedindo 60s — provável limite do plano da conta). **Resultado:** o evento aparece no Google Calendar automaticamente em até ~3 minutos após o agendamento — não é instantâneo, mas totalmente automático (testado e confirmado: execuções com `authorId: null` rodando sozinhas a cada ciclo, sem nenhum clique manual).

**Escopo atual — só cobre criação:**

Por enquanto o job só é disparado na **criação** (`action: 'created'`). Atualizações e cancelamentos de agendamento **ainda não disparam sync** — o cenário Make só sabe *criar* eventos, não buscar/atualizar/excluir. Disparar `'updated'`/`'cancelled'` agora geraria eventos duplicados no Calendar. Isso requer trabalho futuro: armazenar o `google_event_id` retornado pela criação e construir lógica de busca+atualização no Make antes de habilitar sync em updates/cancelamentos.

**Pendências para visão unificada (cliente nunca precisa abrir o Google Calendar):**
- [ ] Cenário de leitura no Make: buscar eventos do Calendar e enviar de volta para a plataforma
- [ ] Endpoint/tela no painel Laravel para exibir os eventos do Calendar dentro da própria plataforma
- [ ] Suporte a updates/cancelamentos (depende de guardar `google_event_id` e lógica de find-and-update no Make)

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

- [ ] Registrar rotas de `agendamentos-recorrentes` no `web.php`
- [ ] Implementar geração automática de `agendamentos` a partir dos recorrentes (command/scheduler)
- [ ] Adicionar envio de lembrete (campo `lembrete_enviado` já existe na tabela)
- [ ] Soft-delete: criar rota de restauração de agendamentos cancelados
- [ ] Sincronizar updates/cancelamentos com o Google Calendar (guardar `google_event_id`, lógica de find-and-update no Make)
- [ ] Cenário Make de leitura do Calendar + tela no painel para visão unificada (sem precisar abrir o Google Calendar)

---

*Última atualização: 2026-06-06 — adicionada integração Make.com → Google Calendar*
