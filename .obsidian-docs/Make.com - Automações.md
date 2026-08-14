# Make.com — Automações

> Registro dos cenários ativos, receitas de blueprint e gotchas do Make.com para este projeto. Consultar antes de criar ou editar cenários.

Veja também: [[Agendamentos]] · [[Deploy e Ambiente]]

---

## Identificadores da Conta

| Item              | Valor   |
|-------------------|---------|
| Organization ID   | 462751  |
| Team ID           | 181064  |
| Folder ID (proj.) | 320545  |
| Conexão Google    | `vitorjpereira.12@gmail.com` (ID: 4728231) |

---

## Cenários Ativos

| ID      | Nome                                        | Trigger        | Intervalo | Status  |
|---------|---------------------------------------------|----------------|-----------|---------|
| 4771510 | Agendamento → Google Calendar (Barbearia)   | Webhook        | Instantâneo (webhook) | ✅ Ativo |
| 4771604 | Google Calendar → Plataforma (Leitura)      | Scheduler      | 60s (~3min real) | ✅ Ativo |
| 4779256 | Keep-Alive — GlowSystem Vercel              | Scheduler      | 900s (15min) | ✅ Ativo |

---

## Cenário 4771510 — Plataforma → Google Calendar

**Webhook hook ID:** 2738899
**Payload esperado (enviado pelo job `SyncAgendamentoToGoogleCalendar`):**

```json
{
  "agendamento_id": 123,
  "action": "created | updated | cancelled",
  "google_event_id": "abc123xyz",
  "servico_nome": "Corte + Barba",
  "cliente_nome": "João Silva",
  "cliente_telefone": "11999999999",
  "profissional_nome": "Carlos",
  "data_inicio": "2026-06-15T10:00:00",
  "data_fim": "2026-06-15T10:30:00",
  "status": "confirmado",
  "observacoes": "..."
}
```

**Estrutura do cenário (5 módulos):**

```
Módulo 1: gateway:CustomWebHook (hook 2738899)
    └─► Módulo 2: builtin:BasicRouter
            ├─ Rota 0 [filter: action=created]
            │       ├─ Módulo 2: google-calendar:createAnEvent (v5, conn 4728231)
            │       └─ Módulo 3: http:ActionSendData → POST /webhooks/google-calendar/event-id
            │               Header: X-Calendar-Sync-Token
            │               Body: {"agendamento_id": {{1.agendamento_id}}, "google_event_id": "{{2.id}}"}
            ├─ Rota 1 [filter: action=updated AND google_event_id existe]
            │       └─ Módulo 4: google-calendar:updateAnEvent (v5, eventId={{1.google_event_id}})
            └─ Rota 2 [filter: action=cancelled AND google_event_id existe]
                    └─ Módulo 5: google-calendar:deleteAnEvent (v5, eventId={{1.google_event_id}})
```

---

## Cenário 4771604 — Google Calendar → Plataforma

**Data structure:** 281809 ("Evento Calendar Sync — Barbearia")
**Endpoint destino:** `POST https://saas-babiearia.vercel.app/webhooks/google-calendar/sync`
**Token:** header `X-Calendar-Sync-Token`

**Campos enviados:**
```json
{
  "barbearia_id": 1,
  "google_event_id": "{{2.id}}",
  "titulo": "{{2.summary}}",
  "descricao": "{{2.description}}",
  "inicio": "formatDate(2.start; YYYY-MM-DDTHH:mm:ss)",
  "fim": "formatDate(2.end; YYYY-MM-DDTHH:mm:ss)",
  "dia_inteiro": false,
  "status": "{{2.status}}"
}
```

**Filtro ativo:** módulo `json:CreateJSON` tem `filter: {conditions: [[{a: "{{2.id}}", o: "exist"}]]}` — pula eventos sem ID.

---

## Cenário 4779256 — Keep-Alive

Faz GET em `https://saas-babiearia.vercel.app/up` a cada 15 minutos para evitar cold start do Vercel.

---

## Receitas de Blueprint

### Estrutura mínima de um cenário

```json
{
  "name": "Nome do Cenário",
  "flow": [ ...módulos... ],
  "metadata": {
    "instant": false,
    "version": 1,
    "scenario": {
      "roundtrips": 1,
      "maxErrors": 3,
      "autoCommit": true,
      "autoCommitTriggerLast": true,
      "sequential": false,
      "confidential": false,
      "dataloss": false,
      "dlq": false,
      "freshVariables": false
    },
    "designer": {"orphans": []},
    "zone": "us1.make.com"
  }
}
```

### Scheduling válido

```json
{"type": "indefinitely", "interval": 900}
```
O `interval` é em **segundos** (não minutos). Mínimo real no Make Free: ~180s (3 min).

### Webhook trigger

```json
{
  "id": 1,
  "module": "gateway:CustomWebHook",
  "version": 1,
  "parameters": {"hook": HOOK_ID, "maxResults": 1},
  "mapper": {},
  "metadata": {
    "restore": {"parameters": {"hook": {"label": "Nome do Hook"}}},
    "designer": {"x": 0, "y": 0}
  }
}
```

### Filtro entre módulos (CORRETO)

O `filter` vai **dentro do módulo de destino**, NÃO no objeto de rota do router:

```json
{
  "id": 2,
  "filter": {
    "name": "Nome do filtro",
    "conditions": [[
      {"a": "{{1.campo}}", "b": "valor", "o": "text:equal"},
      {"a": "{{1.outro_campo}}", "b": "", "o": "exist"}
    ]]
  },
  "module": "nome:doModulo",
  ...
}
```

**Operadores úteis:**
| Operador | Significado |
|----------|-------------|
| `text:equal` | Igual (string) |
| `text:notequal` | Diferente |
| `exist` | Campo existe e não é vazio |
| `number:greater` | Maior que |
| `number:less` | Menor que |

**Lógica AND/OR:** condições no mesmo array inner = AND; arrays diferentes = OR:
```json
"conditions": [
  [{"a": "x", "b": "1", "o": "text:equal"}, {"a": "y", "b": "2", "o": "text:equal"}],  // AND
  [{"a": "z", "b": "3", "o": "text:equal"}]  // OR
]
```

### Router com 3 ramos

```json
{
  "id": 10,
  "module": "builtin:BasicRouter",
  "version": 1,
  "mapper": null,
  "metadata": {"designer": {"x": 300, "y": 0}},
  "routes": [
    {"flow": [ /* primeiro módulo tem filter dentro */ ]},
    {"flow": [ /* segundo módulo tem filter dentro */ ]},
    {"flow": [ /* terceiro módulo tem filter dentro */ ]}
  ]
}
```

> [!WARNING]
> `routes[N]` só aceita a propriedade `flow`. NÃO colocar `filter` diretamente no objeto de rota — isso viola o schema e rejeita o blueprint inteiro.

### HTTP POST com JSON

```json
{
  "id": 99,
  "module": "http:ActionSendData",
  "version": 3,
  "parameters": {"handleErrors": false},
  "mapper": {
    "url": "https://minha-url.com/endpoint",
    "method": "post",
    "headers": [
      {"name": "X-Token", "value": "meu-token"},
      {"name": "Content-Type", "value": "application/json"}
    ],
    "bodyType": "raw",
    "contentType": "application/json",
    "body": "{\"campo\": \"{{1.valor}}\"}",
    "parseResponse": false,
    "followRedirect": true,
    "gzip": true,
    "rejectUnauthorized": true,
    "serializeUrl": false
  }
}
```

### Google Calendar — createAnEvent (v5)

```json
{
  "module": "google-calendar:createAnEvent",
  "version": 5,
  "parameters": {"__IMTCONN__": 4728231},
  "mapper": {
    "calendar": "primary",
    "select": "detail",
    "summary": "{{1.titulo}}",
    "description": "{{1.descricao}}",
    "start": "{{1.data_inicio}}",
    "end": "{{1.data_fim}}",
    "allDayEvent": false,
    "visibility": "default",
    "transparency": "opaque",
    "conferenceDate": false,
    "attendees": [],
    "overrides": []
  },
  "metadata": {
    "restore": {
      "expect": {
        "select": {"label": "In Detail"},
        "calendar": {"label": "Primary Calendar"},
        "visibility": {"label": "Default"},
        "allDayEvent": {"label": "No"},
        "transparency": {"label": "Busy"},
        "conferenceDate": {"label": "No"}
      },
      "parameters": {"__IMTCONN__": {"label": "My Google connection (vitorjpereira.12@gmail.com)"}}
    }
  }
}
```

---

## Gotchas e Regras

| Gotcha | Detalhe |
|--------|---------|
| Intervalo em **segundos** | `interval: 900` = 15 min. `interval: 15` = 15 segundos (muito curto → erro). |
| Filtro fica no **módulo**, não na rota | `builtin:BasicRouter.routes[N]` só aceita `flow`. |
| `gateway:BasicFilter` não existe | Não há módulo filter standalone. Usar `filter` como prop do módulo. |
| Versão dos módulos Google Calendar | Usar sempre `version: 5` para `createAnEvent` e `updateAnEvent`. `deleteAnEvent` também `version: 5`. |
| Templates `{{1.campo}}` | Referem ao módulo com `id: 1`. Módulo 2 → `{{2.campo}}`. |
| Datas do Google Calendar | Usar `formatDate(2.start; "YYYY-MM-DDTHH:mm:ss")` explicitamente — o campo bruto pode vir em formato não padrão. |
| Eventos especiais (Out of Office, etc.) | Retornam campos `null`. Filtrar com `eventTypes: ["default"]` no `searchEvents`. |
| `/api/` prefix no Vercel | O Vercel strip `/api/` antes de passar para PHP. Webhooks devem usar `/webhooks/`. |

---

*Última atualização: 2026-06-11 — criação da nota com cenários ativos, payloads, receitas de blueprint e tabela de gotchas*
