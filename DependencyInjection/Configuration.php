<?php

namespace Nimbusletruand\CurriculumVitaeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nimbusletruand_curriculum_vitae');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
                ->scalarNode('path_to_cv')
                    ->info('Defines the path where the bundle grabs the curriculum vitae xml file')
                    ->example('@AcmeHelloBundle/Resources/curriculumvitae/')
                ->end()
                ->scalarNode('custo_default_cv')
                    ->info('Defines your default curriculum vitae')
                    ->example('example')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
