---
name: sem-comentarios
description: Proíbe block comments, function comments e inline comments no código do projeto. Use ao criar ou editar PHP/TS/JS, revisar PRs, refatorar models/controllers/services, ou quando o usuário pedir código sem comentários.
---

# Sem Comentários

block comments, function comments, inline comments não devem jamais ser utilizados. codigo bom não precisa de comentário

## When to Use

- Ao criar ou editar qualquer arquivo do projeto
- Ao revisar código gerado pelo agente
- Ao refatorar arquivos que ainda tenham PHPDoc ou comentários

## Instructions

1. **Não adicionar** comentários no código.
2. **Não manter** comentários existentes ao editar um arquivo — remova quando tocar no arquivo.
3. **Não usar**:
   - Block comments (`/** ... */`)
   - Function/method comments (`/** @return ... */`)
   - Inline comments (`// ...`, `# ...`, `/* ... */`)
   - PHPDoc de `@property`, `@param`, `@return`, `@use`, etc.

4. **Preferir código autoexplicativo**:
   - Nomes claros de classes, métodos e variáveis
   - Tipagem nativa do PHP/TypeScript (`: HasMany`, `: JsonResponse`, etc.)
   - Estrutura pequena e responsabilidade única

## Exemplos proibidos

Como em `Category.php`:

```php
/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 */
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory, HasUuid;

    /**
     * @return HasMany<Post, $this>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_uuid', 'uuid');
    }
}
```

## Forma correta

```php
class Category extends Model
{
    use HasFactory, HasUuid;

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_uuid', 'uuid');
    }
}
```

## Não fazer

- Não deixar comentários “temporários” ou TODO em comentário
- Não substituir comentário por comentário mais longo
- Não adicionar PHPDoc “só para o IDE” — use tipagem nativa

## Exceções

- Arquivos em `vendor/`, `node_modules/` e código gerado automaticamente
- Licenças/cabeçalhos obrigatórios em arquivos de terceiros (não adicionar novos)
