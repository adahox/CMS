---
name: model-guarded
description: Define mass assignment em models Laravel com #[Guarded] em vez de #[Fillable]. Use ao criar ou editar models, factories, módulos novos, ou quando o usuário mencionar guarded, mass assignment ou atributos de model.
---

# Model Guarded

ao invés de usar Fillable nas models, adote o padrão #guarded

## When to Use

- Ao criar uma nova model
- Ao editar models existentes que usam `#[Fillable]`
- Ao implementar módulos completos (junto com a skill `modulos`)

## Instructions

1. **Não use** `#[Fillable]` nem `$fillable`.
2. **Use** `#[Guarded]` com o atributo PHP do Laravel:

```php
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded(['id', 'uuid'])]
class Post extends Model
{
    //
}
```

3. **Campos guardados por padrão**
   - Sempre: `id`
   - Models com `HasUuid`: `id`, `uuid`
   - Models sem uuid (ex.: `User`): apenas `id`

4. **Demais colunas** da migration ficam disponíveis para mass assignment via dados validados no FormRequest — não listar campo a campo.

5. **Manter** `#[Hidden]` em models que expõem atributos sensíveis (ex.: `User`).

6. **Ao migrar** de `#[Fillable]` para `#[Guarded]`:
   - Remover `use Illuminate\Database\Eloquent\Attributes\Fillable`
   - Remover o atributo `#[Fillable([...])]`
   - Adicionar `#[Guarded]` com os campos de sistema acima
   - Não alterar factories, services ou requests só por causa da troca

## Exemplos

**Post / Category (com uuid):**

```php
#[Guarded(['id', 'uuid'])]
class Post extends Model
{
    use HasFactory, HasUuid;
}
```

**User (sem uuid):**

```php
#[Guarded(['id'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    //
}
```

## Não fazer

- Não misturar `#[Fillable]` e `#[Guarded]` na mesma model
- Não usar `$guarded = ['*']` (bloqueia tudo)
- Não guardar campos de domínio (`title`, `name`, `category_uuid`, etc.) — só campos de sistema
