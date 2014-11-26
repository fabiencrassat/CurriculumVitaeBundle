<?php

/*
 * This file is part of the FabienCrassat\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabienCrassat\CurriculumVitaeBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('fabien_crassat_curriculum_vitae');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
                ->scalarNode('path_to_cv')
                    ->info('Defines the path where the bundle grabs the curriculum vitae xml files')
                    ->example('%kernel.root_dir%\..\src\Acme\HelloBundle\Resources\CV')
                ->end()
                ->scalarNode('custo_default_cv')
                    ->info('It is the default curriculum vitae xml file called without route')
                    ->example('mycv')
                ->end()
                ->scalarNode('default_lang')
                    ->info('It is the default curriculum vitae language')
                    ->example('en')
                ->end()
                ->scalarNode('template')
                    ->info('Defines your own twig template for your curriculum vitae')
                    ->example('AcmeHelloBundle:CV:index.html.twig')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
