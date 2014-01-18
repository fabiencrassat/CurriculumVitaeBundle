<?php

namespace Nimbusletruand\CurriculumVitaeBundle\Test\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Nimbusletruand\CurriculumVitaeBundle\DependencyInjection\NimbusletruandCurriculumVitaeExtension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class testNimbusletruandCurriculumVitaeExtension extends \PHPUnit_Framework_TestCase
{
    /**
     * {@inheritDoc}
     */
    public function testLoad()
    {
        $configs = array();
        $container = new ContainerBuilder();

        $extension = new NimbusletruandCurriculumVitaeExtension();
        $extension->load($configs, $container);
    }
}