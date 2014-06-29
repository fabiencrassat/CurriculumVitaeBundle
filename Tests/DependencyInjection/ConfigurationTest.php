<?php

/*
 * This file is part of the Nimbusletruand\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimbusletruand\CurriculumVitaeBundle\Test\DependencyInjection;

use Nimbusletruand\CurriculumVitaeBundle\DependencyInjection\Configuration;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * {@inheritDoc}
     */
    public function testGetConfigTreeBuilder()
    {
        $conf = new Configuration();
        $conf->getConfigTreeBuilder();
    }
}
