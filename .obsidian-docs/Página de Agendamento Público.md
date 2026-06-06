# Página de Agendamento Público

> Interface pública acessível por clientes externos (sem login) para agendar atendimentos online. Acessada via `/agendar/{slug}` — um link único por barbearia.

Veja também: [[Agendamentos]] · [[Clube de Assinatura]] · [[Frontend - Blade e Tailwind]] · [[Banco de Dados]]

---

## Acesso

| URL                          | Descrição                                  |
|------------------------------|--------------------------------------------|
| `/agendar/{slug}`            | Página da barbearia pelo slug único        |
| `/agendar/{slug}?funcionario={id}` | Link exclusivo de um profissional    |
| `/b/{slug}`                  | Alias curto (redirect para `/agendar`)     |

O **slug** é gerado no cadastro da barbearia: `nome-da-barbearia-{número aleatório}`.
O link de agendamento também está disponível no model: `$barbearia->link_agendamento`.

---

## Fluxo da Página (Multi-Step, Alpine.js — `resources/views/booking/index.blade.php`)

> Atualizado em 2026-06-06 após leitura direta do componente Alpine `booking()`. A ordem real difere da documentação anterior — o serviço é escolhido **antes** do profissional.

```
Step 1: Escolha do Serviço
    → catálogo em grid 2 colunas com foto, nome, duração e preço
    → lista servicos com ativo=true da barbearia (imagem via $s->imagem_url)

Step 2: Escolha do Profissional   (pulado se link ?funcionario={id} → modo "exclusivo")
    → opção "Qualquer profissional" + lista de profissionais ativos/aceita_agendamento_online
    → se exclusivo: pré-seleciona e pula direto para Step 3

Step 3: Data e Hora
    → input date nativo + grid de horários fixos (08:00–19:30, intervalos de 30min)

Step 4: Adicionais (Produtos)        — só aparece se a barbearia tem produtos ativos
    → catálogo em grid 2 colunas com foto, nome e preço (multi-seleção via checkbox)
    → lista produtos com ativo=true (imagem via $p->imagem_url)

Step 5: Confirmação
    → resumo do pedido + formulário (nome, WhatsApp, observação, toggle "Sou VIP")
    → telefone → GET /api/check-vip → badge VIP (se aplicável)
    → POST /agendar → AgendamentoController@store

Step 6: Sucesso
    → tela de confirmação com resumo + botão "Fazer nova reserva"
```

Navegação controlada por `etapa` (Alpine state). `avancarDeServico()` decide pular Step 2 se `exclusivo`; `voltarDeDateHora()` faz o caminho inverso.

---

## Dados Passados para a View

```php
compact('profissionais', 'servicos', 'produtos', 'preselected', 'exclusivo', 'barbearia')
```

| Variável       | Tipo       | Conteúdo                                     |
|----------------|------------|----------------------------------------------|
| `profissionais`| Collection | Profissionais ativos que aceitam agendamento |
| `servicos`     | Collection | Serviços ativos da barbearia                 |
| `produtos`     | Collection | Produtos ativos (para seleção adicional)     |
| `preselected`  | Model/null | Profissional pré-selecionado (link exclusivo)|
| `exclusivo`    | bool       | Se é link de profissional exclusivo          |
| `barbearia`    | Model      | Dados da barbearia (nome, logo, etc.)        |

---

## Validação no Store

```php
// AgendamentoController@store
[
    'nomeCliente'    => 'required|string|max:255',
    'telefone'       => 'required|string|max:255',
    'barbearia_id'   => 'required|exists:barbearias,id',
    'profissional_id'=> 'nullable|integer',
    'servico_id'     => 'required|integer',
    'data'           => 'required|date',
    'hora'           => 'required|string',
    'descricao'      => 'nullable|string|max:1000',
    'produtos_ids'   => 'nullable|array',
    'is_vip'         => 'nullable|boolean',
]
```

---

## Produtos no Agendamento

O cliente pode selecionar produtos na página pública. Esses produtos são registrados na coluna `observacoes` do agendamento como texto formatado:
```
"Pomada Capilar (R$ 35.00), Óleo de Barba (R$ 28.00)"
```

> Nota: os produtos não geram `ComandaItem` direto — isso acontece quando a comanda é criada ao finalizar o atendimento.

---

## Catálogo Visual de Serviços e Produtos (2026-06)

Tanto o Step 1 (serviços) quanto o Step 4 (produtos) usam o mesmo padrão de **card de catálogo em grid 2 colunas**:
- Foto em destaque (`aspect-square`, `object-cover`, zoom suave no hover)
- Fallback com ícone (fa-scissors / fa-box) quando `imagem_url` é nulo
- Nome, duração/preço logo abaixo da foto
- Seleção visual: badge de check (produtos, multi-seleção) ou chevron (serviços, navegação)

As imagens vêm do accessor `imagem_url` em [[Banco de Dados|Produto e Servico]] (`Storage::url()` ou URL externa). Imagens placeholder de demonstração (SVG com gradiente + emoji) foram geradas pelo seeder `ProdutosServicosImagensSeeder`.

> ⚠️ Bug corrigido: o card de produto exibia "R$ 0,00" porque referenciava `$p->preco` — o campo correto em `Produto` é `preco_venda`.

> ⚠️ Bug corrigido: seleção de produto parecia não funcionar (sem feedback visual ao clicar). Causa: mismatch de tipo — o `<input type="checkbox" value="{{ $p->id }}">` guarda o ID como **string** em `produtos_ids` (via `x-model`), mas o binding `:class` comparava com `produtos_ids.includes({{ $p->id }})` (número). `Array.includes` usa `===`, então `["5"].includes(5)` é sempre `false`. O clique alternava o array internamente, mas a borda verde/check nunca acendia. Corrigido comparando string-com-string: `produtos_ids.includes('{{ $p->id }}')`.

## Alinhamento Visual com o Painel (2026-06)

A página pública usava tokens de design próprios e divergentes do [[Tema Visual GlowSystem|tema do painel]]. Padronizado para usar os mesmos tokens:
- Fundo: `#030712` → `#0B0F19` (igual ao painel)
- Border-radius custom (`rounded-[24px]`, `[20px]`, `[16px]`) → escala padrão Tailwind (`rounded-2xl`, `rounded-xl`)
- Títulos: removido `font-black uppercase`, agora `font-bold tracking-tight` (igual aos headers do painel)
- Botões CTA: trocados de branco (`bg-white text-gray-950`) para verde sólido (`bg-green-500 hover:bg-green-600 text-white`)
- Cards de resumo: `bg-[#111827] border-gray-800/50` (mesmo padrão dos cards/tabelas do painel)

## Logo do Estabelecimento

O avatar circular no topo da página renderiza `$barbearia->logo` via `Storage::url()`, com fallback para a inicial do nome em gradiente. Logo de demonstração gerado como SVG (gradiente verde + ícone de tesoura) e salvo em `logos/{slug}.svg`.

## Notificação Automática

Ao criar um agendamento online, uma `Notificacao` é criada automaticamente para o painel:
```php
Notificacao::create([
    'tipo'  => 'agendamento',
    'icone' => 'fa-calendar-plus',
    'cor'   => 'violet',
    'titulo'=> 'Novo Agendamento Online',
    'mensagem' => "{nome} agendou {servico} com {profissional} para {data}",
])
```

---

*Última atualização: 2026-06-06 (catálogo visual de produtos/serviços, alinhamento de tema com o painel, logo do estabelecimento)*
