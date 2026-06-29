# Additional Fields

Módulo Laravel para campos extras genéricos, exposto como **relation Eloquent** plugável em qualquer model.

## Visão geral

Três tabelas, um vínculo genérico por `target` (uuid):

| Tabela | Papel |
|--------|--------|
| `additional_fields` | Catálogo (label, type, options) |
| `additional_field_rules` | Quais fields aplicam a qual owner (`target` = uuid da category, etc.) |
| `additional_field_values` | Valores salvos por entidade (`target` = uuid do post, etc.) |

A **porta de entrada** no model é a trait `HasAdditionalFields` + attribute `#[AdditionalFieldsPath]`. Tudo é opt-in: models sem a trait não são afetados.

## Estrutura

```
AdditionalFields/
├── Attributes/AdditionalFieldsPath.php   # configura o path do owner
├── Concerns/
│   ├── HasAdditionalFields.php           # porta de entrada (relation)
│   ├── Repository.php                    # CRUD base dos repositories do catálogo
│   └── UuidGenerator.php                 # uuid automático nas models
├── Contracts/RepositoriesInterface.php
├── Models/
│   ├── AdditionalField.php
│   ├── AdditionalFieldRule.php
│   └── AdditionalFieldValue.php
├── Relations/AdditionalFieldsRelation.php  # núcleo: sync, records, eager load
├── Repositories/                           # CRUD do catálogo (opcional)
└── database/migrations/
```

## Instalação

### 1. Composer (projeto host)

Adicione o repositório e o pacote, ou copie `app/Domain/AdditionalFields` para o projeto.

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "git@github.com:adahox/AdditionalFields.git"
    }
  ],
  "require": {
    "adahox/additional-fields": "^1.0"
  }
}
```

Autoload PSR-4 no host (se não usar pacote composer):

```json
"App\\Domain\\AdditionalFields\\": "app/Domain/AdditionalFields/"
```

### 2. Migrations

```bash
php artisan migrate --path=app/Domain/AdditionalFields/database/migrations
```

Ou publique/copie `database/migrations/2026_01_01_000000_create_additional_fields_tables.php`.

### 3. Plug no model

```php
use App\Domain\AdditionalFields\Attributes\AdditionalFieldsPath;
use App\Domain\AdditionalFields\Concerns\HasAdditionalFields;

#[AdditionalFieldsPath('category')]
class Post extends Model
{
    use HasAdditionalFields;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_uuid', 'uuid');
    }
}
```

- `path`: relação/atributo que resolve o **owner** das rules (ex.: `category` → usa `category.uuid` como `target` nas rules).
- `key`: chave pública do model (padrão: `uuid`).

## Uso

Igual a qualquer relation Eloquent:

```php
// valores + definição do field (JSON: field primeiro, value por último)
$post->additionalFields;
$post->additionalFields();

// catálogo permitido para este post (via category)
$post->additionalFields()->allowedFields();

// persistir respostas
$post->additionalFields()->sync([
    ['additional_field_uuid' => '...', 'value' => 'resposta'],
]);

// eager load
Post::with('additionalFields')->get();
```

### Alias de relation (opcional)

```php
public function extraFields(): AdditionalFieldsRelation
{
    return $this->additionalFields();
}
```

### Serialização (`toArray` / API)

Cada item em `additional_fields` serializa como:

```json
{
  "uuid": "field-uuid",
  "label": "Cor",
  "type": "select",
  "options": ["A", "B"],
  "target": "category-uuid",
  "value": "A"
}
```

Definição do field primeiro, `value` (resposta) por último — ver `AdditionalFieldValue::toArray()`.

## Fluxo de dados

```
Post (uuid)
  └─ category (owner via AdditionalFieldsPath)
       └─ additional_field_rules.target = category.uuid
            └─ additional_fields (catálogo permitido)

Post (uuid)
  └─ additional_field_values.target = post.uuid
       └─ value + additionalField (definição)
```

## Repositories (catálogo)

Opcionais para CRUD de `AdditionalField` / rules / values:

- `AdditionalFieldRepository::filter(['target' => $categoryUuid])` — fields de uma category
- `AdditionalFieldRuleRepository`, `AdditionalFieldValueRepository` — trait `Repository` interna

## Requisitos

- PHP >= 8.2
- Laravel >= 11 (`illuminate/database`)

## Integração no host (exemplo CMS)

| Camada | Responsabilidade |
|--------|------------------|
| `Post` + `HasAdditionalFields` | porta de entrada |
| `Strategy` | `$post->additionalFields()->sync($values)` |
| `Adapter` | `$item->toArray()` |
| `Http/Requests` | validação de `additional_field_values` |

Models **sem** a trait continuam funcionando sem alteração.

## Changelog

### v1.0.0

- Trait `HasAdditionalFields` como porta de entrada
- `AdditionalFieldsRelation` extends `Relation` (sync, eager load, records)
- Attribute `AdditionalFieldsPath` para owner configurável
- Domain autocontido (`UuidGenerator`, `Repository`, `Contracts`)
- Removido `AdditionalFields::for()` (factory estática)
