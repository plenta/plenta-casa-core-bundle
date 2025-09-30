# config.yml

Default Config:

```yaml
    plenta_casa_core:
        alias_generation:
            parts: # DCA-Fields to be included in the alias
                - 'title'
                - 'postal'
                - 'city'
            locale: 'de' # Locale to be used for the alias generation
            validChars: '0-9a-z' # Valid chars for the alias generation
```