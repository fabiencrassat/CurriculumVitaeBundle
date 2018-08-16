# Fabien Crassat / Curriculumvitae Bundle

Welcome to the Curriculumvitae bundle - an experimental CV display
with [Symfony][1] application that you can use to display your curriculum vitae.

[1]: http://symfony.com

[![knpbundles.com](http://knpbundles.com/fabiencrassat/CurriculumVitaeBundle/badge)](http://knpbundles.com/fabiencrassat/CurriculumVitaeBundle) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/2db81c7b-4627-402f-a28e-534397d1188a/big.png)](https://insight.sensiolabs.com/projects/2db81c7b-4627-402f-a28e-534397d1188a)

[![Build Status](https://travis-ci.org/fabiencrassat/CurriculumVitaeBundle.svg?branch=master)](https://travis-ci.org/fabiencrassat/CurriculumVitaeBundle) [![Latest Stable Version](https://poser.pugx.org/fabiencrassat/curriculumvitae/v/stable.svg)](https://packagist.org/packages/fabiencrassat/curriculumvitae) [![Latest Unstable Version](https://poser.pugx.org/fabiencrassat/curriculumvitae/v/unstable.svg)](https://packagist.org/packages/fabiencrassat/curriculumvitae) [![License](https://poser.pugx.org/fabiencrassat/curriculumvitae/license.svg)](https://packagist.org/packages/fabiencrassat/curriculumvitae) [![Total Downloads](https://poser.pugx.org/fabiencrassat/curriculumvitae/downloads.svg)](https://packagist.org/packages/fabiencrassat/curriculumvitae) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fabiencrassat/CurriculumVitaeBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fabiencrassat/CurriculumVitaeBundle/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/fabiencrassat/CurriculumVitaeBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/fabiencrassat/CurriculumVitaeBundle/?branch=master) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/b63ffa51a3de4399b3dbcfe28f481632)](https://www.codacy.com/app/fabien/CurriculumVitaeBundle?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=fabiencrassat/CurriculumVitaeBundle&amp;utm_campaign=Badge_Grade)
[![Code Climate](https://codeclimate.com/github/fabiencrassat/CurriculumVitaeBundle/badges/gpa.svg)](https://codeclimate.com/github/fabiencrassat/CurriculumVitaeBundle) [![Sonar Quality Gate](https://sonarcloud.io/api/project_badges/measure?project=CurriculumVitaeBundle&metric=alert_status)](https://sonarcloud.io/dashboard?id=CurriculumVitaeBundle) [![Sonar Maintainability](https://sonarcloud.io/api/project_badges/measure?project=CurriculumVitaeBundle&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=CurriculumVitaeBundle)

## Prerequisites

This version of the bundle requires Symfony 4.1+.

### [TOCHECK] Translations

If you wish to use default texts provided in this bundle, you have to make
sure you have translator enabled in your config.

``` yaml
# app/config/config.yml

framework:
    translator: ~
```

For more information about translations, check [Symfony documentation](http://symfony.com/doc/current/book/translation.html).

## Installation

1. Install Curriculumvitae Bundle
2. Enable the bundle
3. Import the routing file

### Step 1: Install Curriculumvitae Bundle

Add the following dependency to your composer.json file:

``` json
{
    "require": {
        "_some_packages": "...",
        "fabiencrassat/curriculumvitae": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
php composer.phar update fabiencrassat/curriculumvitae
```

Composer will install the bundle to your project's `vendor/fabiencrassat` directory.

### [TOCHECK] Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new FabienCrassat\CurriculumVitaeBundle\FabienCrassatCurriculumVitaeBundle(),
    );
}
// ...
?>
```

### [TOCHECK] Step 3: Import FabienCrassat CurriculumVitae Bundle routing

Finally, now that you have activated and configured the bundle, all that is left to do is
import the routing file.

In YAML:

``` yaml
# app/config/routing.yml
fabiencrassat_curriculumvitae:
    resource: "@FabienCrassatCurriculumVitaeBundle/Resources/config/routing.yml"
    prefix:   /cv
```

Or if you prefer XML:

``` xml
<!-- app/config/routing.xml -->
<import resource="@FabienCrassatCurriculumVitaeBundle/Resources/config/routing.xml" prefix="/cv" />
```

## [TOCHECK] Usage

### [TOCHECK] Assets installation

``` bash
php app/console assets:install
```

### [TOCHECK] View the result

Go to your site and add /cv, for example: `http://localhost/app_dev.php/cv`

## Documentation

The bulk of the documentation is stored in the `Resources/doc/` directory in this bundle:

- [Expose your custom Curriculum Vitae Files](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/doc/expose_your_cv.md)
- [Custom your curriculum vitae](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/doc/custom_cv_file.md)
- [Make the curriculum vitae beautiful with OrizoneBoilerplate](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/doc/OrizoneBoilerplateTemplate.md)
- [Understand the link beetween xml file and twig variables](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/doc/xml_twig_variables.md)
- [Add an export PDF service](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/doc/export_to_PDF.md)
- [Protect your email and phone number](https://github.com/fabiencrassat/CurriculumVitaeBundle/blob/master/Resources/doc/protect_your_email_and_phone_number.md)
