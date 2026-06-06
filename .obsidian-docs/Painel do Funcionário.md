# Painel do Funcionário

> Interface exclusiva para profissionais (barbeiros, manicures, etc.) acompanharem seus agendamentos do dia e finalizarem atendimentos — sem acesso ao painel admin completo.

Veja também: [[Autenticação e Perfis]] · [[Agendamentos]] · [[Financeiro]] · [[Banco de Dados]]

---

## Acesso

O funcionário acessa via código de 6 caracteres gerado pelo admin no painel principal.

```
/funcionario/login  →  informa código  →  /funcionario/dashboard
```

Não usa o `Auth` do Laravel — a sessão é armazenada em `session('funcionario_id')`.

Ver detalhes completos em [[Autenticação e Perfis]].

---

## Controller: `FuncionarioController`

### `showLogin()`
Exibe o formulário de código. Se `session('funcionario_id')` existir, redireciona direto para o dashboard.

### `login(Request $request)`
Valida o código (tamanho exato de 6 chars), busca o `Profissional` ativo, armazena na sessão e atualiza `is_online = true`.

### `dashboard(Request $request)`
Carrega os agendamentos do profissional com filtro de período:

| Param `?filtro=` | Intervalo carregado |
|------------------|---------------------|
| `hoje` (default) | Agendamentos de hoje |
| `semana`         | Esta semana          |
| `todos`          | Sem filtro de data   |

Campos exibidos por agendamento: horário, serviço, cliente, status.

### `logout()`
Define `is_online = false` no profissional e limpa a sessão.

### `finalizar(Request $request, Agendamento $agendamento)`
Ação principal do painel. Ao clicar "Finalizar Atendimento":

1. Valida que o agendamento pertence ao funcionário logado (segurança)
2. Atualiza `agendamento->status = 'concluido'`
3. Cria uma **Comanda** fechada automaticamente
4. Adiciona o serviço do agendamento como **ComandaItem**
5. Chama `comanda->calcularTotal()`

---

## Status de Online

O campo `profissional.is_online` é atualizado em tempo real:
- `true` ao fazer login no painel do funcionário
- `false` ao fazer logout
- `false` ao fazer logout via admin

Esse campo pode ser exibido no painel admin para ver quais profissionais estão ativos.

---

## Views

| View                          | Descrição                              |
|-------------------------------|----------------------------------------|
| `funcionario/login.blade.php` | Formulário do código de acesso         |
| `funcionario/dashboard.blade.php` | Lista de agendamentos + botão finalizar|

---

## Rotas

| Método | URI                                         | Nome                                |
|--------|---------------------------------------------|-------------------------------------|
| GET    | `/funcionario/login`                        | `funcionario.login`                 |
| POST   | `/funcionario/login`                        | `funcionario.login.post`            |
| GET    | `/funcionario/dashboard`                    | `funcionario.dashboard`             |
| POST   | `/funcionario/agendamentos/{ag}/finalizar`  | `funcionario.agendamento.finalizar` |
| POST   | `/funcionario/logout`                       | `funcionario.logout`                |

> ⚠️ Essas rotas **não têm middleware** de autenticação formal — a validação é feita manualmente em cada action verificando `session('funcionario_id')`.

---

## Pendências / TODOs

- [ ] Adicionar middleware dedicado para proteger as rotas do funcionário
- [ ] Permitir que o funcionário adicione produtos extras na comanda ao finalizar
- [ ] Exibir histórico de atendimentos do dia encerrado
- [ ] Notificação push quando um novo agendamento for atribuído

---

*Última atualização: 2026-06-06*
