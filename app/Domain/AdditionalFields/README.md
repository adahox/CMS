# Additional Fields for Laravel

Campos extras genéricos como **relation Eloquent** plugável.

Documentação completa: [documentation.md](documentation.md)

## Quick start

```php
#[AdditionalFieldsPath('category')]
class Post extends Model
{
    use HasAdditionalFields;
}

$post->additionalFields()->sync([
    ['additional_field_uuid' => '...', 'value' => 'answer'],
]);
```

## License

MIT
