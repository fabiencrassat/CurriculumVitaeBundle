#nimbusletruand / Curriculumvitae Bundle

Welcome to the Curriculumvitae bundle of nimbusletruand - an experimental CV display
with [Symfony2][1] application that you can use to display your curriculum vitae.

[1]: http://symfony.com

## Prerequisites

This version of the bundle requires Symfony 2.4.1+.

### Translations

If you wish to use default texts provided in this bundle, you have to make
sure you have translator enabled in your config.

``` yaml
# app/config/config.yml

framework:
    translator: ~
```

For more information about translations, check [Symfony documentation](http://symfony.com/doc/current/book/translation.html).

## Installation

1. Install Nimbusletruand Curriculumvitae Bundle
2. Enable the bundle
3. Import the routing file

### Step 1: Install Nimbusletruand Curriculumvitae Bundle

Add the following dependency to your composer.json file:
``` json
{
    "require": {
        "_some_packages": "...",
        "nimbusletruand/curriculumvitae": "dev-master"
    }
}
```
Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update nimbusletruand/curriculumvitae
```

Composer will install the bundle to your project's `vendor/nimbusletruand` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Nimbusletruand\CurriculumVitaeBundle\NimbusletruandCurriculumVitaeBundle(),
    );
}
// ...
?>
```

### Step 3: Import Nimbusletruand CurriculumVitae Bundle routing

Finally, now that you have activated and configured the bundle, all that is left to do is
import the routing file.

In YAML:

``` yaml
# app/config/routing.yml
nimbusletruand_curriculumvitae:
    resource: "@NimbusletruandCurriculumVitaeBundle/Resources/config/routing.yml"
```

Or if you prefer XML:

``` xml
<!-- app/config/routing.xml -->
<import resource="@NimbusletruandCurriculumVitaeBundle/Resources/config/routing.xml"/>
```