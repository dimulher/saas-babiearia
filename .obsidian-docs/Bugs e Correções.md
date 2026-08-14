# Bugs e Correções

> Registro de todos os bugs significativos encontrados no projeto — causa raiz, sintoma, fix aplicado e onde não repetir o erro. Consultar ANTES de diagnosticar um erro novo.

Veja também: [[Deploy e Ambiente]] · [[Autenticação e Perfis]] · [[Banco de Dados]]

---

## Template de entrada

```
### [Título curto do bug]
**Sintoma:** o que aparece na tela / log
**Causa raiz:** por que acontece
**Fix:** o que foi mudado e onde
**Arquivo(s):** caminho dos arquivos modificados
**Data:** YYYY-MM-DD
```

---

## Bugs Resolvidos

---

### `Undefined variable $errors` em todas as views de auth

**Sintoma:** `ErrorException: Undefined variable $errors` em qualquer view que use `@error('campo')` ou `$errors->first()` — login, register, funcionário/login.

**Causa raiz:** O middleware `ShareErrorsFromSession` (que injeta `$errors`) só roda se a sessão for inicializada com sucesso. Em produção (Supabase/Vercel), quando a sessão falha no boot, `$errors` nunca é injetado. Tentar fazer o fix no layout (`@php $errors = $errors ?? new ViewErrorBag @endphp`) **não funciona** — o layout renderiza depois que as seções filho já foram avaliadas.

**Fix:** View composer global em `AppServiceProvider::boot()`:
```php
View::composer('*', function ($view) {
    if (!array_key_exists('errors', $view->getData())) {
        $view->with('errors', new \Illuminate\Support\ViewErrorBag);
    }
});
```
Esse composer roda **antes** de qualquer view renderizar — inclusive views standalone sem layout.

**Arquivo:** `app/Providers/AppServiceProvider.php`
**Data:** 2026-06-11

> [!WARNING]
> Nunca colocar o fallback de `$errors` no layout — chega tarde demais. Sempre via view composer no AppServiceProvider.

---

### CSRF Token Mismatch no login/registro (Vercel)

**Sintoma:** Formulário de login/registro retorna 419 Page Expired.

**Causa raiz:** No runtime `vercel-php`, o cookie de sessão muitas vezes não volta corretamente no POST após o GET (round-trip de cookie instável no ambiente serverless). O token CSRF gerado no GET não bate com o esperado no POST.

**Fix:** Excluir as rotas de auth da verificação CSRF em `bootstrap/app.php`:
```php
$middleware->validateCsrfTokens(except: [
    'login/proprietario', 'register', 'logout',
    'funcionario/login', 'forgot-password', 'reset-password',
    'webhooks/google-calendar/sync', 'webhooks/google-calendar/event-id',
]);
```

**Arquivo:** `bootstrap/app.php`
**Data:** 2026-06-11

> [!NOTE]
> Isso não é uma vulnerabilidade grave para rotas de auth porque CSRF protege principalmente contra ações autenticadas. Login/registro não tem estado a proteger. Webhooks externos nunca têm sessão.

---

### Sessão não persiste entre containers Vercel (redirect loop no painel)

**Sintoma:** Após login bem-sucedido, qualquer rota do painel redireciona de volta para `/login` (loop).

**Causa raiz:** `SESSION_DRIVER=file` armazena arquivos em `/tmp/sessions` do container local. O Vercel roteou o POST de login para um container e os GETs seguintes para outro container — que não tem a sessão.

**Fix:** Usar `SESSION_DRIVER=database` com a tabela `sessions` no Supabase. A sessão fica compartilhada entre todos os containers.

**Migration criada:** `database/migrations/2026_06_07_200000_create_sessions_table.php`
**Variável Vercel:** `SESSION_DRIVER=database`
**Data:** 2026-06-11

> [!WARNING]
> Nunca usar `SESSION_DRIVER=file` em produção no Vercel. Usar `database` ou `cookie` (cookie não precisa de tabela mas tem limite de tamanho ~4KB).

---

### Cada request rodando `migrate` + 4 comandos de cache

**Sintoma:** Plataforma extremamente lenta — toda requisição demorava vários segundos.

**Causa raiz:** Em `api/index.php`, `@unlink('/tmp/config.php')` era chamado incondicionalmente para qualquer request PostgreSQL. Isso sempre acionava o bloco de cold-start que roda `migrate --force`, `config:cache`, `route:cache`, `view:cache`.

**Fix:** O `@unlink` só roda quando `DB_USERNAME` precisa de correção em runtime (quando não contém o project-ref `postgres.hywqwshhfwwqqpogknoi`):
```php
if (!str_contains((string) $dbUser, '.')) {
    $fixed = 'postgres.hywqwshhfwwqqpogknoi';
    putenv('DB_USERNAME=' . $fixed);
    @unlink('/tmp/config.php'); // só aqui
}
```

**Arquivo:** `api/index.php`
**Data:** 2026-06-11

---

### FK em `eventos_google_calendar.barbearia_id` bloqueando INSERT em produção

**Sintoma:** Erro de constraint ao tentar inserir evento do Google Calendar — em produção a migration original não havia rodado.

**Causa raiz:** A migration original criou a tabela com `$table->foreignId('barbearia_id')->constrained('barbearias')->cascadeOnDelete()`. Quando a migration de correção tentou rodar em produção, a FK não existia e qualquer INSERT falhava com constraint violation.

**Fix:** Migration de correção (`2026_06_07_000001_fix_eventos_google_calendar_table.php`) recria a tabela **sem FK**, mantendo apenas o índice único composto `(barbearia_id, google_event_id)`.

**Arquivo:** `database/migrations/2026_06_07_000001_fix_eventos_google_calendar_table.php`
**Data:** 2026-06-07

> [!NOTE]
> Para tabelas que recebem dados de sistemas externos (Make.com, webhooks), evitar FK constraints rígidas — o sistema externo pode enviar dados antes que o registro pai exista.

---

### Make.com: `filter` rejeitado no route object do `builtin:BasicRouter`

**Sintoma:** `Validation failed: should NOT have additional properties, additionalProperty: 'filter'` ao tentar atualizar blueprint com filtro no route.

**Causa raiz:** O schema do `builtin:BasicRouter` só aceita `flow` no objeto de rota. Filtros **não ficam na rota** — ficam **no primeiro módulo da rota**, como propriedade do módulo.

**Fix:** Mover `filter` para dentro do módulo que deve ser filtrado:
```json
{
  "id": 2,
  "filter": {
    "name": "Criação",
    "conditions": [[{"a": "{{1.action}}", "b": "created", "o": "text:equal"}]]
  },
  "module": "google-calendar:createAnEvent",
  ...
}
```

**Data:** 2026-06-11

---

### Make.com: `gateway:BasicFilter` não existe como módulo

**Sintoma:** `Module not found 'gateway:BasicFilter' version '1'` ao tentar usar como filtro standalone.

**Causa raiz:** Não existe esse módulo no Make.com. Filtros entre módulos são atributos do módulo de destino, não módulos independentes.

**Fix:** Usar a propriedade `filter` diretamente no módulo de destino (ver bug acima).

**Data:** 2026-06-11

---

*Última atualização: 2026-06-11 — registro inicial com 6 bugs da fase de estabilização da plataforma*
