# Make the curriculum vitae beautiful with OrizoneBoilerplate

## Installation

- Change your config to call the new twig template

``` yaml
# app/packages/fabiencrassat_curriculumvitae.yaml
fabien_crassat_curriculum_vitae:
    # ...
    template:   "@FabienCrassatCurriculumVitae/CurriculumVitae/OryzoneBoilerplate.html.twig"
```

## Deployment

``` sh
php bin/console assets:install
```
