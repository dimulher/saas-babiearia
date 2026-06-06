# Financeiro

> Módulo que centraliza toda a movimentação financeira da barbearia: comandas de atendimento, contas a pagar/receber e relatórios de faturamento.

Veja também: [[Banco de Dados]] · [[Agendamentos]] · [[Rotas e Controllers]]

---

## Conceito: Duas Fontes de Receita

O módulo financeiro agrega dados de **duas fontes distintas**:

1. **Comandas** — geradas ao finalizar um atendimento (via painel do funcionário ou admin). Representam receita de serviços e venda de produtos.
2. **Contas** — lançamentos manuais de receitas e despesas (aluguel, produto, etc.).

O `FinanceiroController` consolida ambas para gerar os resumos.

---

## Tabelas Envolvidas

Ver schemas completos em [[Banco de Dados]].

### `comandas`
Cada atendimento finalizado gera uma comanda. Uma comanda pode ter vários itens (serviços + produtos).

**Status:** `aberta` → `fechada`

**Relações:**
- `barbearia` belongsTo
- `profissional` belongsTo
- `cliente` belongsTo (nullable)
- `agendamento` belongsTo (nullable)
- `itens` hasMany → `comanda_itens`

**Método `calcularTotal()`:** soma `itens->subtotal`, subtrai desconto, salva o registro.

### `comanda_itens`
Itens adicionados a uma comanda. Podem referenciar `servico_id` ou `produto_id` (ambos nullable).

### `contas`
Lançamentos financeiros manuais.

**Tipos:** `receita` · `despesa`
**Status:** `pendente` · `pago`

**Accessor `$conta->vencida`:** retorna `true` se está pendente e o vencimento já passou.

---

## Controller: `FinanceiroController`

### `index(Request $request)`

Rota: `GET /panel/financeiro`

Executa 4 queries otimizadas:

| Query | O que retorna |
|-------|--------------|
| 1 | Contas pagas no período filtrado |
| 2 | Comandas fechadas no período filtrado |
| 3 | `selectRaw` único com resumos de hoje e do mês (receitas + despesas) |
| 4 | Faturamento por profissional, gráfico diário, top 5 serviços |

**Períodos disponíveis** (via query param `?periodo=`):

| Valor          | Intervalo                         |
|----------------|-----------------------------------|
| `hoje`         | startOfDay → endOfDay             |
| `semana`       | startOfWeek → endOfWeek           |
| `mes` (default)| startOfMonth → endOfMonth         |
| `personalizado`| requer `?de=` e `?ate=`           |

**Saída para a view:**

```
movimentacoes      → comandas + contas mescladas e ordenadas por data
totalEntradas      → soma das receitas no período
totalSaidas        → soma das despesas no período
saldo              → totalEntradas - totalSaidas
resumoHoje         → { receitas, despesas, lucro }
resumoMes          → { receitas, despesas, lucro }
faturamentoPorProfissional → ranking de profissionais
faturamentoDiario  → dados para gráfico de linha
servicosDistribuiucao → top 5 serviços mais faturados
```

---

## Controller: `ComandaController`

| Método       | Ação                                                  |
|--------------|-------------------------------------------------------|
| `index()`    | Lista comandas da barbearia com filtros               |
| `store()`    | Cria nova comanda (aberta)                            |
| `show()`     | Detalhes de uma comanda + itens                       |
| `addItem()`  | Adiciona item (serviço ou produto) à comanda          |
| `removeItem()`| Remove item da comanda                              |
| `close()`    | Fecha a comanda (define `fechada_em`, `forma_pagamento`)|

---

## Controller: `ContaController`

| Método     | Ação                                         |
|------------|----------------------------------------------|
| `index()`  | Lista todas as contas da barbearia           |
| `store()`  | Cria nova conta (a pagar ou a receber)       |
| `pagar()`  | Marca conta como paga (`status = pago`, define `pago_em`) |
| `destroy()`| Soft-delete da conta                         |

---

## Fluxo: Finalização de Atendimento → Comanda

```
Funcionário clica "Finalizar" no dashboard
    └─► POST /funcionario/agendamentos/{ag}/finalizar
            ├─ agendamento->status = 'concluido'
            ├─ Comanda::create (status='fechada', fechada_em=now())
            ├─ ComandaItem::create (serviço principal)
            └─ comanda->calcularTotal()
```

> O admin pode adicionar itens extras a uma comanda aberta manualmente pelo painel.

---

## Views

| View                          | Responsabilidade                          |
|-------------------------------|-------------------------------------------|
| `panel/financeiro/index`      | Dashboard financeiro com gráficos         |
| `panel/comandas`              | Lista de comandas                         |
| `panel/comanda-detalhes`      | Detalhes + itens de uma comanda           |
| `panel/contas/`               | Gestão de contas a pagar/receber          |
| `panel/relatorios/`           | Relatórios analíticos                     |

---

*Última atualização: 2026-06-06*
