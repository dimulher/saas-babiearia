# Banco de Dados

> Banco SQLite em desenvolvimento (`database/database.sqlite`). Todas as tabelas seguem o padrão multi-tenant via `barbearia_id`. Migrations em `database/migrations/`.

Veja também: [[Autenticação e Perfis]] · [[Agendamentos]] · [[Financeiro]] · [[Clube de Assinatura]]

---

## Diagrama de Entidades

```
barbearias
  ├── users (1:N)
  ├── profissionais (1:N)
  ├── clientes (1:N)
  ├── servicos (1:N)
  ├── produtos (1:N)
  ├── agendamentos (1:N)
  │     └── comandas (1:1)
  │           └── comanda_itens (1:N)
  ├── expedientes (1:N)
  ├── contas (1:N)
  ├── planos (1:N)
  │     └── assinaturas (1:N) → clientes
  ├── notificacoes (1:N)
  ├── logs_atividades (1:N)
  └── agendamentos_recorrentes (1:N)
```

---

## Tabelas

### `barbearias`
Entidade raiz do sistema multi-tenant. Cada barbearia é um tenant isolado.

| Coluna            | Tipo             | Descrição                              |
|-------------------|------------------|----------------------------------------|
| `id`              | bigint PK        | —                                      |
| `nome`            | string           | Nome da barbearia                      |
| `slug`            | string unique    | URL pública: `/agendar/{slug}`         |
| `email`           | string           | —                                      |
| `telefone`        | string (null)    | —                                      |
| `logo`            | string (null)    | Caminho da imagem                      |
| `capa`            | string (null)    | Imagem de capa da página pública       |
| `descricao`       | text (null)      | —                                      |
| `endereco`        | string (null)    | —                                      |
| `cidade`          | string (null)    | —                                      |
| `estado`          | string (null)    | —                                      |
| `cep`             | string (null)    | —                                      |
| `instagram`       | string (null)    | —                                      |
| `facebook`        | string (null)    | —                                      |
| `whatsapp`        | string (null)    | —                                      |
| `ativo`           | boolean          | —                                      |
| `plano`           | string           | Plano SaaS: `basic`, `pro`, etc.       |
| `plano_expira_em` | datetime (null)  | Expiração do plano SaaS                |
| `tipo`            | string (null)    | Tipo do estabelecimento (migration 13) |
| `deleted_at`      | timestamp (null) | Soft-delete                            |

---

### `users`
Usuários do painel admin (proprietários/gerentes). Autenticação via Laravel Auth.

| Coluna         | Tipo             | Descrição                          |
|----------------|------------------|------------------------------------|
| `id`           | bigint PK        | —                                  |
| `barbearia_id` | FK → barbearias  | Tenant                             |
| `nome`         | string           | —                                  |
| `email`        | string unique    | Login                              |
| `telefone`     | string (null)    | —                                  |
| `password`     | hashed           | —                                  |
| `role`         | string           | `admin` · `gerente` · `barbeiro`   |
| `deleted_at`   | timestamp (null) | Soft-delete                        |

---

### `profissionais`
Funcionários que atendem clientes. Acesso via código de 6 dígitos (painel do funcionário).

| Coluna                      | Tipo             | Descrição                                |
|-----------------------------|------------------|------------------------------------------|
| `id`                        | bigint PK        | —                                        |
| `barbearia_id`              | FK → barbearias  | Tenant                                   |
| `nome`                      | string           | —                                        |
| `email`                     | string (null)    | —                                        |
| `telefone`                  | string (null)    | —                                        |
| `foto`                      | string (null)    | Avatar                                   |
| `comissao_percentual`       | decimal(5,2)     | % de comissão sobre atendimentos         |
| `ativo`                     | boolean          | —                                        |
| `aceita_agendamento_online` | boolean          | Aparece na página pública                |
| `codigo_acesso`             | string (null)    | Código de 6 chars para login próprio     |
| `is_online`                 | boolean          | Status em tempo real (migration 2026-05) |
| `ultimo_login_at`           | datetime (null)  | Última entrada no painel (migration 2026)|
| `deleted_at`                | timestamp (null) | Soft-delete                              |

---

### `clientes`
Clientes cadastrados da barbearia. Podem existir agendamentos sem cliente cadastrado.

| Coluna            | Tipo             | Descrição                        |
|-------------------|------------------|----------------------------------|
| `id`              | bigint PK        | —                                |
| `barbearia_id`    | FK → barbearias  | Tenant                           |
| `nome`            | string           | —                                |
| `email`           | string (null)    | —                                |
| `telefone`        | string (null)    | Principal                        |
| `whatsapp`        | string (null)    | —                                |
| `data_nascimento` | date (null)      | —                                |
| `tipo`            | string (null)    | Ex: `vip`, `regular`             |
| `observacoes`     | text (null)      | —                                |
| `ativo`           | boolean          | —                                |
| `deleted_at`      | timestamp (null) | Soft-delete                      |

---

### `servicos`
Catálogo de serviços oferecidos.

| Coluna               | Tipo             | Descrição                           |
|----------------------|------------------|-------------------------------------|
| `id`                 | bigint PK        | —                                   |
| `barbearia_id`       | FK → barbearias  | Tenant                              |
| `nome`               | string           | —                                   |
| `descricao`          | text (null)      | —                                   |
| `imagem`             | string (null)    | Path ou URL (migration 2026-06)     |
| `preco`              | decimal(10,2)    | —                                   |
| `duracao_minutos`    | integer          | Usada para calcular `data_fim`      |
| `cor`                | string (null)    | Cor no calendário                   |
| `ativo`              | boolean          | —                                   |
| `disponivel_online`  | boolean          | Exibe na página pública             |
| `deleted_at`         | timestamp (null) | Soft-delete                         |

---

### `produtos`
Estoque de produtos (para venda nas comandas).

| Coluna            | Tipo             | Descrição                           |
|-------------------|------------------|-------------------------------------|
| `id`              | bigint PK        | —                                   |
| `barbearia_id`    | FK → barbearias  | Tenant                              |
| `nome`            | string           | —                                   |
| `descricao`       | text (null)      | —                                   |
| `codigo`          | string (null)    | Código interno / SKU                |
| `imagem`          | string (null)    | Path ou URL (migration 2026-06)     |
| `preco_custo`     | decimal(10,2)    | —                                   |
| `preco_venda`     | decimal(10,2)    | —                                   |
| `estoque_atual`   | integer          | —                                   |
| `estoque_minimo`  | integer          | Alerta de estoque baixo             |
| `unidade`         | string           | Ex: `un`, `ml`, `g`                 |
| `ativo`           | boolean          | —                                   |
| `deleted_at`      | timestamp (null) | Soft-delete                         |

---

### `agendamentos`
Ver nota dedicada: [[Agendamentos]]

---

### `agendamentos_recorrentes`
Ver nota dedicada: [[Agendamentos]]

---

### `expedientes`
Horários de trabalho de cada profissional por dia da semana.

| Coluna             | Tipo            | Descrição                           |
|--------------------|-----------------|-------------------------------------|
| `id`               | bigint PK       | —                                   |
| `barbearia_id`     | FK → barbearias | Tenant                              |
| `profissional_id`  | FK → profissionais | —                                |
| `dia_semana`       | tinyint         | 0=Dom … 6=Sáb                       |
| `hora_inicio`      | time            | —                                   |
| `hora_fim`         | time            | —                                   |
| `intervalo_inicio` | time (null)     | Início do intervalo/almoço          |
| `intervalo_fim`    | time (null)     | Fim do intervalo                    |
| `ativo`            | boolean         | —                                   |

---

### `comandas`
Registro financeiro de cada atendimento. Gerada ao finalizar um agendamento.

| Coluna            | Tipo             | Descrição                              |
|-------------------|------------------|----------------------------------------|
| `id`              | bigint PK        | —                                      |
| `barbearia_id`    | FK → barbearias  | Tenant                                 |
| `profissional_id` | FK → profissionais | —                                    |
| `cliente_id`      | FK → clientes (null) | —                                  |
| `agendamento_id`  | FK → agendamentos (null) | —                              |
| `cliente_nome`    | string           | Nome avulso (sem cadastro)             |
| `subtotal`        | decimal(10,2)    | Soma dos itens                         |
| `desconto`        | decimal(10,2)    | —                                      |
| `total`           | decimal(10,2)    | subtotal − desconto                    |
| `forma_pagamento` | string           | `dinheiro` · `cartao` · `pix` · `outro`|
| `status`          | string           | `aberta` · `fechada`                   |
| `observacoes`     | text (null)      | —                                      |
| `fechada_em`      | datetime (null)  | —                                      |
| `deleted_at`      | timestamp (null) | Soft-delete                            |

---

### `comanda_itens`
Itens de cada comanda (serviços e produtos).

| Coluna           | Tipo               | Descrição                 |
|------------------|--------------------|---------------------------|
| `id`             | bigint PK          | —                         |
| `comanda_id`     | FK → comandas      | —                         |
| `servico_id`     | FK → servicos (null) | —                       |
| `produto_id`     | FK → produtos (null) | —                       |
| `descricao`      | string             | Nome do item              |
| `quantidade`     | integer            | —                         |
| `preco_unitario` | decimal(10,2)      | —                         |
| `subtotal`       | decimal(10,2)      | quantidade × preco_unitario|

---

### `contas`
Contas a pagar e a receber da barbearia.

| Coluna           | Tipo             | Descrição                                 |
|------------------|------------------|-------------------------------------------|
| `id`             | bigint PK        | —                                         |
| `barbearia_id`   | FK → barbearias  | Tenant                                    |
| `descricao`      | string           | —                                         |
| `valor`          | decimal(10,2)    | —                                         |
| `tipo`           | string           | `receita` · `despesa`                     |
| `status`         | string           | `pendente` · `pago`                       |
| `vencimento`     | date             | —                                         |
| `pago_em`        | date (null)      | —                                         |
| `recorrencia`    | string (null)    | Ex: `mensal`, `semanal`                   |
| `parcela_atual`  | integer (null)   | —                                         |
| `total_parcelas` | integer (null)   | —                                         |
| `categoria`      | string (null)    | Ex: `aluguel`, `produto`                  |
| `observacoes`    | text (null)      | —                                         |
| `deleted_at`     | timestamp (null) | Soft-delete                               |

---

### `planos`
Planos do Clube de Assinatura (criados pelo proprietário da barbearia).

| Coluna          | Tipo            | Descrição                              |
|-----------------|-----------------|----------------------------------------|
| `id`            | bigint PK       | —                                      |
| `barbearia_id`  | FK → barbearias | Tenant                                 |
| `nome`          | string          | —                                      |
| `valor_mensal`  | decimal(10,2)   | —                                      |
| `recursos`      | json            | Array de benefícios (cast: array)      |
| `ativo`         | boolean         | —                                      |

---

### `assinaturas`
Assinatura de um cliente a um plano do clube VIP.

| Coluna           | Tipo            | Descrição                        |
|------------------|-----------------|----------------------------------|
| `id`             | bigint PK       | —                                |
| `barbearia_id`   | FK → barbearias | Tenant                           |
| `cliente_id`     | FK → clientes   | —                                |
| `plano_id`       | FK → planos     | —                                |
| `status`         | string          | `ativo` · `cancelado` · `suspenso`|
| `dia_vencimento` | integer         | Dia do mês do vencimento         |
| `data_inicio`    | date            | —                                |

---

### `notificacoes`
Notificações internas no painel (ex: novo agendamento online).

| Coluna         | Tipo            | Descrição                              |
|----------------|-----------------|----------------------------------------|
| `id`           | bigint PK       | —                                      |
| `barbearia_id` | FK → barbearias | Tenant                                 |
| `tipo`         | string          | Ex: `agendamento`                      |
| `icone`        | string          | Classe FontAwesome                     |
| `cor`          | string          | Cor do badge                           |
| `titulo`       | string          | —                                      |
| `mensagem`     | text            | —                                      |
| `lida`         | boolean         | —                                      |

---

### `logs_atividades`
Auditoria de ações dos usuários.

| Coluna        | Tipo            | Descrição                              |
|---------------|-----------------|----------------------------------------|
| `id`          | bigint PK       | —                                      |
| `barbearia_id`| FK → barbearias | Tenant                                 |
| `user_id`     | FK → users (null)| —                                     |
| `acao`        | string          | Ex: `agendamento_recorrente_criado`    |
| `descricao`   | text            | —                                      |
| `model_type`  | string (null)   | —                                      |
| `model_id`    | bigint (null)   | —                                      |

---

### `super_admins`
Administradores da plataforma SaaS (acesso global).

| Coluna     | Tipo      | Descrição |
|------------|-----------|-----------|
| `id`       | bigint PK | —         |
| `email`    | string    | —         |
| `password` | hashed    | —         |

---

## Padrões do Banco

- **Multi-tenant:** toda tabela usa `barbearia_id` como escopo
- **Soft-delete:** habilitado em `barbearias`, `users`, `profissionais`, `clientes`, `servicos`, `produtos`, `agendamentos`, `comandas`, `contas`
- **Driver:** SQLite (dev) → produção no Vercel via SQLite (arquivo persistido)
- **Migrations:** numeradas sequencialmente em `database/migrations/`

---

*Última atualização: 2026-06-06*
