# Padrões de Código

> Snippets reutilizáveis e padrões estabelecidos no projeto. Consultar ao criar novos componentes para manter consistência visual e técnica.

Veja também: [[Frontend - Blade e Tailwind]] · [[Tema Visual GlowSystem]] · [[Rotas e Controllers]]

---

## Padrões Blade / Tailwind

### Card de item com ações (padrão da listagem do painel)

Usado em agendamentos, profissionais, serviços — fundo `#0B0F19`, borda esverdeada no hover.

```blade
<div class="bg-[#0B0F19] border border-gray-800 hover:border-green-800/40 rounded-xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all group">
    <!-- Conteúdo principal -->
    <div class="flex items-center gap-5">
        <!-- Horário / ID -->
        <div class="text-center w-14">
            <div class="text-lg font-bold text-white tracking-tight">{{ $hora }}</div>
            <div class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">{{ $subtitulo }}</div>
        </div>
        <div class="h-10 w-px bg-gray-800"></div>
        <!-- Info principal -->
        <div>
            <h3 class="text-sm font-bold text-white uppercase tracking-tight group-hover:text-green-400 transition-colors">{{ $titulo }}</h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ $subtexto }}</p>
        </div>
    </div>
    <!-- Ações / badges -->
    <div class="flex items-center gap-2 flex-wrap justify-end">
        <!-- badges e botões aqui -->
    </div>
</div>
```

---

### Badge de status (cores por estado)

Padrão de cores para status de agendamentos. Usar sempre `rounded-full text-[9px] font-bold uppercase tracking-widest border`.

```blade
{{-- Pendente --}}
<span class="px-3 py-1 bg-amber-900/30 text-amber-400 rounded-full text-[9px] font-bold uppercase tracking-widest border border-amber-800/50">Pendente</span>

{{-- Confirmado --}}
<span class="px-3 py-1 bg-blue-900/30 text-blue-400 rounded-full text-[9px] font-bold uppercase tracking-widest border border-blue-800/50">Confirmado</span>

{{-- Concluído --}}
<span class="px-3 py-1 bg-emerald-900/30 text-emerald-400 rounded-full text-[9px] font-bold uppercase tracking-widest border border-emerald-800/50">Concluído</span>

{{-- Cancelado / Faltou --}}
<span class="px-3 py-1 bg-rose-900/30 text-rose-400 rounded-full text-[9px] font-bold uppercase tracking-widest border border-rose-800/50">Cancelado</span>

{{-- Google Calendar --}}
<span class="px-3 py-1 bg-blue-900/20 text-blue-400 rounded-full text-[9px] font-bold uppercase tracking-widest border border-blue-800/40">
    <i class="fa-brands fa-google mr-1"></i>Calendar
</span>
```

---

### Botão de ação pequeno (inline)

```blade
{{-- Primário (ação positiva) --}}
<button class="px-3 py-1 bg-blue-600 hover:bg-blue-500 text-white rounded-full text-[9px] font-bold uppercase tracking-widest transition-all">Confirmar</button>

{{-- Destrutivo --}}
<button class="px-3 py-1 bg-rose-900/50 hover:bg-rose-800 text-rose-300 rounded-full text-[9px] font-bold uppercase tracking-widest border border-rose-800/50 transition-all">Cancelar</button>

{{-- Neutro --}}
<button class="px-3 py-1 bg-gray-800 hover:bg-gray-700 text-gray-400 rounded-full text-[9px] font-bold uppercase tracking-widest border border-gray-700 transition-all">Faltou</button>
```

---

### Estado vazio (empty state)

```blade
<div class="flex flex-col items-center justify-center py-20 text-gray-400 bg-gray-900/50 rounded-2xl border border-dashed border-gray-800">
    <i class="fa-regular fa-calendar-xmark text-5xl mb-4 text-gray-700"></i>
    <h3 class="text-base font-bold text-white uppercase tracking-tight">Título do estado vazio</h3>
    <p class="text-[10px] text-gray-500 mt-2 uppercase font-bold tracking-widest">Subtexto explicativo.</p>
</div>
```

---

### Formulário com filtros (topo da listagem)

```blade
<form action="{{ route('panel.rota') }}" method="GET"
      class="bg-[#111827] border border-gray-800/50 rounded-2xl p-5 flex flex-wrap items-center gap-3">
    <div class="flex items-center gap-2 bg-[#0B0F19] px-4 py-2.5 rounded-xl border border-gray-800">
        <i class="fa-solid fa-calendar text-green-500 text-xs"></i>
        <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()"
            class="bg-transparent border-none p-0 text-xs font-bold uppercase tracking-widest focus:ring-0 text-gray-300">
    </div>
    {{-- Adicionar mais filtros com o mesmo padrão --}}
</form>
```

---

### Callout de aviso no painel

```blade
{{-- Warning --}}
<div class="bg-amber-900/20 border border-amber-800/40 rounded-xl p-4 flex items-start gap-3">
    <i class="fa-solid fa-triangle-exclamation text-amber-400 mt-0.5"></i>
    <div>
        <p class="text-sm font-bold text-amber-300">Título do aviso</p>
        <p class="text-xs text-amber-400/70 mt-1">Detalhes do aviso.</p>
    </div>
</div>

{{-- Info --}}
<div class="bg-blue-900/20 border border-blue-800/40 rounded-xl p-4 flex items-start gap-3">
    <i class="fa-solid fa-circle-info text-blue-400 mt-0.5"></i>
    <p class="text-sm text-blue-300">Mensagem informativa.</p>
</div>
```

---

## Padrões JavaScript / Alpine.js

### AJAX action com CSRF (fetch + reload)

Padrão usado em `updateStatus` — ação PATCH/POST sem redirecionar a página:

```js
function executarAcao(id, dados) {
    fetch(`/panel/recurso/${id}/acao`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify(dados),
    })
    .then(r => r.json())
    .then(data => { if (data.success) window.location.reload(); })
    .catch(() => alert('Erro ao executar ação. Tente novamente.'));
}
```

**Como incluir na view (sem poluir o layout):**
```blade
@push('scripts')
<script>
// Seu JS aqui
</script>
@endpush
```
Requer `@stack('scripts')` antes do `</body>` em `layouts/app.blade.php` — já configurado.

---

### Modal Alpine.js com formulário

```blade
<div x-data="{ open: false, itemId: null }">
    {{-- Trigger --}}
    <button @click="open = true; itemId = {{ $item->id }}"
            class="px-3 py-1 bg-rose-900/50 text-rose-300 rounded-full text-[9px] font-bold uppercase border border-rose-800/50">
        Excluir
    </button>

    {{-- Modal --}}
    <div x-show="open" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div @click.away="open = false"
             class="bg-[#111827] border border-gray-800 rounded-2xl p-6 max-w-md w-full mx-4 shadow-2xl">
            <h3 class="text-lg font-bold text-white mb-2">Confirmar exclusão</h3>
            <p class="text-sm text-gray-400 mb-6">Esta ação não pode ser desfeita.</p>
            <div class="flex gap-3 justify-end">
                <button @click="open = false"
                        class="px-4 py-2 bg-gray-800 text-gray-300 rounded-xl text-sm font-bold hover:bg-gray-700 transition-all">
                    Cancelar
                </button>
                <form :action="`/panel/recurso/${itemId}`" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-rose-600 text-white rounded-xl text-sm font-bold hover:bg-rose-500 transition-all">
                        Excluir
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
```

---

## Padrões de Controller

### PATCH de status com sync externo

```php
public function updateStatus(Request $request, ModelName $model)
{
    // 1. Autorização por tenant
    if ($model->barbearia_id !== auth()->user()->barbearia_id) {
        abort(403);
    }

    // 2. Validação
    $request->validate([
        'status' => 'required|in:ativo,inativo,cancelado',
    ]);

    // 3. Atualiza
    $model->update(['status' => $request->status]);

    // 4. Dispara job/evento externo se necessário
    // SomeJob::dispatch($model->id, $request->status);

    return response()->json(['success' => true, 'status' => $request->status]);
}
```

**Rota correspondente:**
```php
Route::patch('/recurso/{model}/status', [Controller::class, 'updateStatus'])->name('recurso.status');
```

---

### Webhook autenticado por token

```php
public function webhookHandler(Request $request)
{
    $token = config('services.make.calendar_sync_token');
    if (!$token || $request->header('X-Calendar-Sync-Token') !== $token) {
        return response()->json(['error' => 'Não autorizado'], 401);
    }

    $request->validate([
        'campo_obrigatorio' => 'required|string',
    ]);

    // ... lógica do webhook

    return response()->json(['success' => true]);
}
```

**Adicionar à lista de exceções CSRF em `bootstrap/app.php`:**
```php
$middleware->validateCsrfTokens(except: [
    // ...
    'webhooks/meu-webhook',
]);
```

---

## Padrões de Banco de Dados

### Migration de adição de coluna

```php
public function up(): void
{
    Schema::table('nome_tabela', function (Blueprint $table) {
        $table->string('nova_coluna')->nullable()->after('coluna_existente');
    });
}

public function down(): void
{
    Schema::table('nome_tabela', function (Blueprint $table) {
        $table->dropColumn('nova_coluna');
    });
}
```

### Não usar FK em tabelas que recebem dados externos

Ver [[Bugs e Correções]] — bug de FK em `eventos_google_calendar`. Para tabelas que recebem webhooks ou dados de sistemas externos, usar apenas índices únicos sem constraint FK.

```php
// ✅ Correto para tabelas com dados externos
$table->unsignedBigInteger('barbearia_id');
$table->unique(['barbearia_id', 'chave_externa']);

// ❌ Evitar FK quando dado pode chegar antes do registro pai
$table->foreignId('barbearia_id')->constrained()->cascadeOnDelete();
```

---

## Padrões de Responsividade Mobile

### Regras gerais de breakpoints

| Cenário | Padrão |
|---------|--------|
| Card padding em auth | `p-6 sm:p-10` (nunca só `p-10`) |
| Botão posicionado absolutamente em card com padding variável | `top-5 sm:top-8 left-5 sm:left-8` |
| Grid de métricas 2→4 colunas | `grid-cols-2 lg:grid-cols-4` (2 cols no mobile é OK) |
| Grid de 1→3 colunas | `grid-cols-1 md:grid-cols-2 lg:grid-cols-3` (nunca pular direto para 3) |
| Painel de dropdown flutuante com largura fixa | Adicionar `max-w-[calc(100vw-1rem)]` junto à `w-[360px]` |
| Tabs com muitos itens | `w-fit max-w-full overflow-x-auto` — nunca apenas `w-fit` |
| Células de tabela | `px-3 sm:px-6 py-N` (todas as tabelas têm `overflow-x-auto` no wrapper) |

### Notification dropdown (app.blade.php)

```html
class="absolute right-0 top-full mt-2 z-50 w-[360px] max-w-[calc(100vw-1rem)] ..."
```
Sem `max-w-[calc(100vw-1rem)]`, em telas < 360px o dropdown estoura o viewport para a esquerda.

### Tabs scrolláveis (financeiro)

```html
<div class="flex gap-1 p-1 bg-gray-900 rounded-xl w-fit max-w-full overflow-x-auto border border-gray-800">
```
`max-w-full` limita o container ao pai; `overflow-x-auto` ativa o scroll horizontal quando os botões ultrapassam essa largura.

### Grid de agenda + coluna lateral (dashboard)

```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 ..."><!-- agenda --></div>
    <div class="..."><!-- sidebar --></div>
</div>
```
Em `md`: 2 colunas (agenda | sidebar). Em `lg`: 3 colunas (agenda ocupa 2, sidebar 1).

---

*Última atualização: 2026-06-11 — adicionada seção de responsividade mobile com todos os padrões aplicados na sessão*
