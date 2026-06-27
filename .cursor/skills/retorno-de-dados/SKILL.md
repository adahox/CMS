---
name: retorno-de-dado
description: Entender melhor os dados trabalhados com relação a model/banco
---

# Retorno de Dado

Toda vez que você for trabalhar os dados de model seja num select ou filter ou algo assim seja em comparações ou retornos ou Scopes, Adapters ou algo assim, não faça converções diretas.
valide o dado específico que está trabalhando na migration para não ficar fazendo converções errôneas tipo (array), (int), (string)

## When to Use

- Quando receber dados do request ou buscar informaçẽos da model ou trabalhar com enums, adapters.
- Essa skill é boa para ajudar o codigo ficar mais limpo e organizado.

## Instructions

- valide o campo da request ou da model com o migration