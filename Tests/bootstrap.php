<?php

/*
 * This file is part of the FabienCrassat\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$autoloads = [
    __DIR__.'/../vendor/autoload.php',          // travis or standalone test
    __DIR__.'/../../../../vendor/autoload.php', // symfony & composer test
];

$autoloadFile = FALSE;

foreach ($autoloads as $file) {
    if (is_file($file)) {
        $autoloadFile = $file;

        break;
    }
}

if ($autoloadFile === false) {
    die('Unable to find autoload.php file, please use composer to load dependencies:

wget http://getcomposer.org/composer.phar
php composer.phar install

Visit http://getcomposer.org/ for more information.

');
}

include $autoloadFile;
