<?php

namespace Nimbusletruand\CurriculumVitaeBundle\Test\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
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
