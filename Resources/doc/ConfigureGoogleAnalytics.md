# Configure Google Analytics

> Prerequisite: [Make the curriculum vitae beautiful with OrizoneBoilerplate](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/doc/OrizoneBoilerplateTemplate.md)

Google analytics is disabled by default. You can easily enable it by passing your analytics id within the variable `bp_analytics_id`.
Optionally, if track subdomains or different domains, it would be good to even set the variable `bp_analytics_domain` to the current domain you are tracking.
Anyway I suggest you to set these variables directly in your configuration file among the Twig global variables. This way you have the opportunity to specify an id on the environments you prefer to: for example you may want to not use analytics on development but to use it in production, so just add the following lines on your `config/packages/fabiencrassat_curriculumvitae.yaml` file

```yaml
twig:
  globals:
    bp_analytics_id: '%env(google_analytics_id)%'
    bp_analytics_domain: '%env(google_analytics_domain)%'
```

And in `.env` file

```text
###> fabiencrassat/curriculumvitae ###
google_analytics_id=UA-XXXXXXXX
google_analytics_domain=domain.com
###< fabiencrassat/curriculumvitae ###
```
