# Autenticação e Perfis

> O sistema possui **dois sistemas de autenticação paralelos**: o Laravel Auth padrão (para proprietários/gerentes) e um sistema de sessão próprio baseado em código de acesso (para funcionários/profissionais).

Veja também: [[Banco de Dados]] · [[Rotas e Controllers]] · [[Painel do Funcionário]]

---

## Sistema 1 — Proprietários e Gerentes (Laravel Auth)

Autenticação padrão do Laravel via `Auth::attempt()`. Guarda a sessão com cookie.

### Fluxo

```
GET  /login                → gateway de escolha (auth/gateway.blade.php)
GET  /login/proprietario   → formulário de login
POST /login/proprietario   → Auth::attempt() → redirect /panel/dashboard
GET  /register             → cadastro de barbearia + admin
POST /register             → cria Barbearia + User(role=admin) → Auth::login()
GET/POST /logout           → Auth::logout() → redirect /login
```

### Model `User`

```php
fillable: ['barbearia_id', 'nome', 'email', 'telefone', 'password', 'role']
hidden:   ['password', 'remember_token']
casts:    ['email_verified_at' => datetime, 'password' => hashed]
```

**Roles disponíveis:**

| Role      | Método helper    | Descrição               |
|-----------|------------------|-------------------------|
| `admin`   | `isAdmin()`      | Proprietário da barbearia|
| `gerente` | `isGerente()`    | Gerente com acesso ao painel|
| `barbeiro`| `isBarbeiro()`   | Acesso limitado          |

**Accessor `$user->initials`:** retorna as iniciais do nome (ex: "João Silva" → "JS")

### Middleware `auth`
Todas as rotas em `/panel/*` são protegidas pelo middleware `auth`. O tenant é derivado de `auth()->user()->barbearia_id` — nunca exposto como parâmetro de URL.

---

## Sistema 2 — Funcionários (Código de Acesso + Sessão)

Sistema independente do Laravel Auth. O profissional faz login com um **código alfanumérico de 6 caracteres** gerado pelo admin.

### Fluxo

```
GET  /funcionario/login    → formulário de código
POST /funcionario/login    → busca Profissional por codigo_acesso
                             → session(['funcionario_id', 'funcionario_nome'])
                             → Profissional::update(['is_online' => true])
GET  /funcionario/dashboard → lê session('funcionario_id')
POST /funcionario/logout   → Profissional::update(['is_online' => false])
                             → session()->forget(...)
```

### Geração do Código
```
POST /panel/profissionais/{profissional}/gerar-codigo
→ ProfissionalController@gerarCodigo
```
O código é gerado pelo admin no painel e entregue manualmente ao funcionário.

### Segurança
- O sistema **não usa Laravel Auth** — a sessão do funcionário é separada
- Não há middleware dedicado: cada action do `FuncionarioController` verifica manualmente `session('funcionario_id')`
- A ação `finalizar` valida que o `agendamento->profissional_id === session('funcionario_id')` antes de permitir

---

## Campos de Profissional para Auth

| Campo            | Descrição                                    |
|------------------|----------------------------------------------|
| `codigo_acesso`  | String 6 chars — o "password" do funcionário |
| `is_online`      | true quando há sessão ativa                  |
| `ultimo_login_at`| timestamp do último login                    |

---

## Cadastro de Nova Barbearia (Registro)

No POST `/register`, o sistema cria dois registros atomicamente:

1. **`Barbearia`** com `slug` único gerado a partir do nome + número aleatório
2. **`User`** com `role = 'admin'` vinculado à barbearia

Após criação, faz `Auth::login($user)` automaticamente.

---

## Super Admin

Existe uma tabela separada `super_admins` para administradores da plataforma SaaS. As rotas de admin ficam em `/admin/*` (prefix separado, não documentado ainda).

---

*Última atualização: 2026-06-06*
