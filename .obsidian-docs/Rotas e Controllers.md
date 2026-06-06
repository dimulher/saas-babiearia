# Rotas e Controllers

> Mapeamento completo de todas as rotas do `routes/web.php`. Middleware `auth` protege o prefixo `/panel`.

Veja também: [[Autenticação e Perfis]] · [[Agendamentos]] · [[Financeiro]] · [[Clube de Assinatura]] · [[Painel do Funcionário]]

---

## Rotas Públicas (sem auth)

### Autenticação

| Método     | URI                        | Action / Controller                    | Nome                       |
|------------|----------------------------|----------------------------------------|----------------------------|
| GET        | `/login`                   | view `auth.gateway`                    | `login`                    |
| GET        | `/login/proprietario`      | view `auth.login`                      | `login.proprietario`       |
| POST       | `/login/proprietario`      | closure (Auth::attempt)                | `login.proprietario.post`  |
| GET        | `/register`                | view `auth.register`                   | `register`                 |
| POST       | `/register`                | closure (cria Barbearia + User)        | `register.post`            |
| GET / POST | `/logout`                  | closure (Auth::logout)                 | `logout`                   |

### Agendamento Público

| Método | URI              | Controller / Action              | Nome            |
|--------|------------------|----------------------------------|-----------------|
| GET    | `/agendar/{slug?}`| closure (dados reais da barbearia)| `booking`      |
| POST   | `/agendar`       | `AgendamentoController@store`    | `booking.store` |
| GET    | `/b/{slug}`      | redirect → `booking`             | —               |
| GET    | `/api/check-vip` | `AgendamentoController@checkVip` | `api.check-vip` |

### Funcionário

| Método | URI                                         | Controller / Action                   | Nome                            |
|--------|---------------------------------------------|---------------------------------------|---------------------------------|
| GET    | `/funcionario/login`                        | `FuncionarioController@showLogin`     | `funcionario.login`             |
| POST   | `/funcionario/login`                        | `FuncionarioController@login`         | `funcionario.login.post`        |
| GET    | `/funcionario/dashboard`                    | `FuncionarioController@dashboard`     | `funcionario.dashboard`         |
| POST   | `/funcionario/agendamentos/{ag}/finalizar`  | `FuncionarioController@finalizar`     | `funcionario.agendamento.finalizar` |
| POST   | `/funcionario/logout`                       | `FuncionarioController@logout`        | `funcionario.logout`            |

### Raiz

| Método | URI | Comportamento |
|--------|-----|---------------|
| GET    | `/` | Redireciona para `/panel/dashboard` (auth) ou `/login` |

---

## Rotas do Painel (middleware `auth`, prefixo `/panel`, nome `panel.*`)

### Dashboard e Notificações

| Método | URI                          | Controller / Action                           | Nome                       |
|--------|------------------------------|-----------------------------------------------|----------------------------|
| GET    | `/panel/dashboard`           | `DashboardController@index`                   | `panel.dashboard`          |
| GET    | `/panel/notificacoes`        | `NotificacaoController@index`                 | `panel.notificacoes.index` |
| POST   | `/panel/notificacoes/{id}/read` | `NotificacaoController@markAsRead`         | `panel.notificacoes.read`  |
| POST   | `/panel/notificacoes/read-all`  | `NotificacaoController@markAllAsRead`      | `panel.notificacoes.readAll`|
| DELETE | `/panel/notificacoes/{id}`   | `NotificacaoController@destroy`               | `panel.notificacoes.destroy`|

### Agendamentos

| Método | URI                     | Controller / Action           | Nome                  |
|--------|-------------------------|-------------------------------|-----------------------|
| GET    | `/panel/agendamentos`   | `AgendamentoController@index` | `panel.agendamentos`  |

> ⚠️ Rotas de agendamentos-recorrentes ainda não registradas. Ver [[Agendamentos]].

### Clube VIP (Assinaturas)

Prefixo `/panel/assinaturas`, nome `panel.assinaturas.*`

| Método | URI                              | Controller / Action                  | Nome                         |
|--------|----------------------------------|--------------------------------------|------------------------------|
| GET    | `/panel/assinaturas`             | `AssinaturaController@index`         | `panel.assinaturas.index`    |
| POST   | `/panel/assinaturas/planos`      | `AssinaturaController@storePlano`    | `panel.assinaturas.planos.store`|
| PATCH  | `/panel/assinaturas/planos/{pl}/toggle` | `AssinaturaController@togglePlano` | `panel.assinaturas.planos.toggle`|
| DELETE | `/panel/assinaturas/planos/{pl}` | `AssinaturaController@destroyPlano`  | `panel.assinaturas.planos.destroy`|
| POST   | `/panel/assinaturas`             | `AssinaturaController@storeAssinatura`| `panel.assinaturas.store`   |
| PATCH  | `/panel/assinaturas/{as}/toggle` | `AssinaturaController@toggleAssinatura`| `panel.assinaturas.toggle` |
| DELETE | `/panel/assinaturas/{as}`        | `AssinaturaController@destroyAssinatura`| `panel.assinaturas.destroy`|

### Comandas

| Método | URI                               | Controller / Action          | Nome                       |
|--------|-----------------------------------|------------------------------|----------------------------|
| GET    | `/panel/comandas`                 | `ComandaController@index`    | `panel.comandas`           |
| POST   | `/panel/comandas`                 | `ComandaController@store`    | `panel.comandas.store`     |
| GET    | `/panel/comandas/{comanda}`       | `ComandaController@show`     | `panel.comandas.show`      |
| POST   | `/panel/comandas/{cmd}/itens`     | `ComandaController@addItem`  | `panel.comandas.itens.store`|
| DELETE | `/panel/comandas/{cmd}/itens/{i}` | `ComandaController@removeItem`| `panel.comandas.itens.destroy`|
| POST   | `/panel/comandas/{cmd}/fechar`    | `ComandaController@close`    | `panel.comandas.fechar`    |

### Financeiro

| Método | URI                   | Controller / Action          | Nome                       |
|--------|-----------------------|------------------------------|----------------------------|
| GET    | `/panel/financeiro`   | `FinanceiroController@index` | `panel.financeiro.index`   |

### Contas (a pagar/receber)

Prefixo `/panel/contas`, nome `panel.contas.*`

| Método | URI                          | Controller / Action      | Nome                    |
|--------|------------------------------|--------------------------|-------------------------|
| GET    | `/panel/contas`              | `ContaController@index`  | `panel.contas.todas`    |
| POST   | `/panel/contas`              | `ContaController@store`  | `panel.contas.store`    |
| PATCH  | `/panel/contas/{conta}/pagar`| `ContaController@pagar`  | `panel.contas.pagar`    |
| DELETE | `/panel/contas/{conta}`      | `ContaController@destroy`| `panel.contas.destroy`  |

### Profissionais

| Método | URI                                          | Controller / Action                | Nome                                |
|--------|----------------------------------------------|------------------------------------|-------------------------------------|
| GET    | `/panel/profissionais`                       | `ProfissionalController@index`     | `panel.profissionais`               |
| POST   | `/panel/profissionais`                       | `ProfissionalController@store`     | `panel.profissionais.store`         |
| PUT    | `/panel/profissionais/{p}`                   | `ProfissionalController@update`    | `panel.profissionais.update`        |
| DELETE | `/panel/profissionais/{p}`                   | `ProfissionalController@destroy`   | `panel.profissionais.destroy`       |
| POST   | `/panel/profissionais/{p}/gerar-codigo`      | `ProfissionalController@gerarCodigo`| `panel.profissionais.gerar-codigo` |

### Serviços e Produtos

| Método | URI                        | Controller / Action         | Nome                      |
|--------|----------------------------|-----------------------------|---------------------------|
| GET    | `/panel/servicos`          | `ServicoController@index`   | `panel.servicos`          |
| POST   | `/panel/servicos`          | `ServicoController@store`   | `panel.servicos.store`    |
| PUT    | `/panel/servicos/{s}`      | `ServicoController@update`  | `panel.servicos.update`   |
| DELETE | `/panel/servicos/{s}`      | `ServicoController@destroy` | `panel.servicos.destroy`  |
| GET    | `/panel/produtos`          | `ProdutoController@index`   | `panel.produtos`          |
| POST   | `/panel/produtos`          | `ProdutoController@store`   | `panel.produtos.store`    |
| PUT    | `/panel/produtos/{p}`      | `ProdutoController@update`  | `panel.produtos.update`   |
| DELETE | `/panel/produtos/{p}`      | `ProdutoController@destroy` | `panel.produtos.destroy`  |

> [!NOTE]
> **Página `/panel/clientes` removida (2026-06-06).** Chegou a existir um `ClienteController` + view `panel.clientes` prontos mas sem rota registrada (gerando "route could not be found"); ao reportar o erro o usuário decidiu que a página não era necessária. Removidos: rotas `panel.clientes*`, `ClienteController`, a view `panel/clientes.blade.php`, o item "Clientes" da sidebar e o quick-link do dashboard. O **model `Cliente` foi mantido** — é usado em relacionamentos por `Agendamento`, `Comanda`, `Assinatura`, `AgendamentoRecorrente`, `Barbearia`, `RelatorioController`, `BookingController` etc.

### Expedientes e Bloqueios

| Método | URI                               | Controller / Action           | Nome                          |
|--------|-----------------------------------|-------------------------------|-------------------------------|
| GET    | `/panel/expedientes`              | `ExpedienteController@index`  | `panel.expedientes`           |
| POST   | `/panel/expedientes`              | `ExpedienteController@store`  | `panel.expedientes.store`     |
| GET    | `/panel/bloquear-horarios`        | `BloqueioController@index`    | `panel.bloquear-horarios`     |
| POST   | `/panel/bloquear-horarios`        | `BloqueioController@store`    | `panel.bloquear-horarios.store`|
| DELETE | `/panel/bloquear-horarios/{b}`    | `BloqueioController@destroy`  | `panel.bloquear-horarios.destroy`|

### Logs, Relatórios, WhatsApp, Configurações

| Método | URI                                 | Controller / Action                | Nome                           |
|--------|-------------------------------------|------------------------------------|--------------------------------|
| GET    | `/panel/logs`                       | `LogController@index`              | `panel.logs`                   |
| GET    | `/panel/logs/exportar`              | `LogController@export`             | `panel.logs.export`            |
| GET    | `/panel/relatorios`                 | `RelatorioController@index`        | `panel.relatorios.index`       |
| GET    | `/panel/whatsapp/mensagens`         | view `panel.whatsapp.mensagens`    | `panel.whatsapp.mensagens`     |
| GET    | `/panel/configuracoes/sistema`      | view `panel.configuracoes.sistema` | `panel.configuracoes.sistema`  |
| GET    | `/panel/configuracoes/barbearia`    | view `panel.configuracoes.barbearia`| `panel.configuracoes.barbearia`|
| GET    | `/panel/configuracoes/agendamento`  | view `panel.configuracoes.agendamento`| `panel.configuracoes.agendamento`|
| GET    | `/panel/configuracoes/conta`        | view `panel.configuracoes.conta`   | `panel.configuracoes.conta`    |
| GET    | `/panel/meu-plano`                  | view `panel.meu-plano`             | `panel.meu-plano`              |

---

## Lista de Controllers

| Controller                       | Responsabilidade                             |
|----------------------------------|----------------------------------------------|
| `AgendamentoController`          | Listagem e criação de agendamentos           |
| `AgendamentoRecorrenteController`| CRUD de agendamentos recorrentes             |
| `AssinaturaController`           | Clube VIP (planos + assinaturas)             |
| `BloqueioController`             | Bloqueio de horários                         |
| `ClienteController`              | CRUD de clientes                             |
| `ComandaController`              | Comandas e seus itens                        |
| `ContaController`                | Contas a pagar/receber                       |
| `DashboardController`            | Dashboard com KPIs agregados                 |
| `ExpedienteController`           | Horários de trabalho dos profissionais       |
| `FinanceiroController`           | Relatório financeiro consolidado             |
| `FuncionarioController`          | Auth e painel do funcionário                 |
| `LogController`                  | Visualização e exportação de logs            |
| `NotificacaoController`          | Notificações internas do painel              |
| `ProdutoController`              | CRUD de produtos com gestão de imagem        |
| `ProfissionalController`         | CRUD de profissionais + geração de código    |
| `RelatorioController`            | Relatórios analíticos                        |
| `ServicoController`              | CRUD de serviços com gestão de imagem        |

---

*Última atualização: 2026-06-06*
