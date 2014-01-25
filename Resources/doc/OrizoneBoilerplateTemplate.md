# Make the curriculum vitae beautiful with OrizoneBoilerplate

## Installation
*   Install the bundle ```oryzone/boilerplate-bundle```. Googleize to know how install ;)
*   Change your config to call the new twig template and configure Assetic to scan the bundle

``` yaml
# app/config/config.yml

assetic:
    # ...
    bundles:    ["OryzoneBoilerplateBundle", "NimbusletruandCurriculumVitaeBundle"]

nimbusletruand_curriculum_vitae:
    # ...
    template:   "NimbusletruandCurriculumVitaeBundle:CurriculumVitae:OryzoneBoilerplate.html.twig"
```

## Deployment

```php app/console assetic:dump```