# Clube de Assinatura

> Funcionalidade de fidelização: a barbearia cria planos mensais e os clientes assinam para receber benefícios (ex: serviços gratuitos, descontos).

Veja também: [[Banco de Dados]] · [[Agendamentos]] · [[Rotas e Controllers]]

---

## Conceito

O Clube de Assinatura é um módulo de **fidelização B2C** onde:
- O **proprietário** cria planos com preço mensal e lista de benefícios
- Os **clientes cadastrados** são associados a um plano pelo admin
- Durante o **agendamento online**, o sistema detecta automaticamente clientes VIP pelo telefone e aplica os benefícios (ex: serviço gratuito)

---

## Tabelas

Ver schemas completos em [[Banco de Dados]].

### `planos`
Criados pelo proprietário da barbearia.

| Campo          | Descrição                                |
|----------------|------------------------------------------|
| `nome`         | Ex: "Plano Gold", "Plano Premium"        |
| `valor_mensal` | Mensalidade em R$                        |
| `recursos`     | JSON array de strings (benefícios)       |
| `ativo`        | Controla exibição                        |

**Cast `recursos`:** automaticamente deserializado como array PHP.

### `assinaturas`
Vínculo entre um cliente e um plano.

| Campo            | Descrição                              |
|------------------|----------------------------------------|
| `status`         | `ativo` · `cancelado` · `suspenso`     |
| `dia_vencimento` | Dia do mês para cobrança (1-31)        |
| `data_inicio`    | Data de início da assinatura           |

---

## Controller: `AssinaturaController`

### Planos

| Método          | Ação                                        |
|-----------------|---------------------------------------------|
| `index()`       | Lista planos e assinaturas da barbearia     |
| `storePlano()`  | Cria novo plano VIP                         |
| `togglePlano()` | Ativa/desativa um plano                     |
| `destroyPlano()`| Remove um plano                             |

### Assinaturas

| Método                | Ação                                    |
|-----------------------|-----------------------------------------|
| `storeAssinatura()`   | Vincula cliente a um plano              |
| `toggleAssinatura()`  | Ativa/suspende assinatura               |
| `destroyAssinatura()` | Remove assinatura                       |

---

## Integração com Agendamento

A verificação VIP acontece em dois momentos:

### 1. No frontend (tempo real)
```
Usuário digita telefone na página de agendamento
    └─► GET /api/check-vip?telefone=&barbearia_id=
            └─► AgendamentoController@checkVip
                    ├─ Busca cliente por telefone (normalizado)
                    ├─ Verifica Assinatura(status='ativo') com plano
                    └─ Retorna { isVip: true/false, plano: "Gold" }
```

O frontend exibe um badge VIP e permite ao cliente marcar o campo `is_vip`.

### 2. No backend (validação)
```
POST /agendar
    └─► AgendamentoController@store
            ├─ Se is_vip=true → valida novamente no servidor
            ├─ Se assinatura ativa encontrada:
            │     preco = 0
            │     descricao = "[CLIENTE VIP - GOLD] Benefício Aplicado."
            └─ Cria agendamento com preco_final
```

> A validação é sempre feita no backend — o frontend é apenas UX.

---

## Normalização do Telefone

O matching de telefone usa regex para remover formatação:
```php
preg_replace('/[^0-9]/', '', $telefone)
```
Isso garante que `(11) 99999-9999` e `11999999999` sejam tratados como iguais.

---

## Rotas

Prefixo: `/panel/assinaturas` — Nome base: `panel.assinaturas.*`

| Método | URI                               | Action                           |
|--------|-----------------------------------|----------------------------------|
| GET    | `/panel/assinaturas`              | `index` (planos + assinaturas)   |
| POST   | `/panel/assinaturas/planos`       | `storePlano`                     |
| PATCH  | `/panel/assinaturas/planos/{p}/toggle` | `togglePlano`              |
| DELETE | `/panel/assinaturas/planos/{p}`   | `destroyPlano`                   |
| POST   | `/panel/assinaturas`              | `storeAssinatura`                |
| PATCH  | `/panel/assinaturas/{a}/toggle`   | `toggleAssinatura`               |
| DELETE | `/panel/assinaturas/{a}`          | `destroyAssinatura`              |

---

## Pendências / TODOs

- [ ] Integração com MercadoPago para cobrança automática (`MERCADOPAGO_ACCESS_TOKEN` já no `.env`)
- [ ] Webhook para atualizar status de assinatura automaticamente
- [ ] Notificação de vencimento para o cliente (WhatsApp)
- [ ] Relatório de receita recorrente (MRR)

---

*Última atualização: 2026-06-06*
