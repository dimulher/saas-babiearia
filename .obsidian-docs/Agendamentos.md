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

---

## Pendências / TODOs

- [ ] Registrar rotas de `agendamentos-recorrentes` no `web.php`
- [ ] Implementar geração automática de `agendamentos` a partir dos recorrentes (command/scheduler)
- [ ] Adicionar envio de lembrete (campo `lembrete_enviado` já existe na tabela)
- [ ] Soft-delete: criar rota de restauração de agendamentos cancelados

---

*Última atualização: 2026-06-06*
