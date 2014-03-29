<?php

/*
 * This file is part of the Nimbusletruand\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimbusletruand\CurriculumVitaeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NimbusletruandCurriculumVitaeExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // Path to Curriculum Vitae Directory
        $vendorDir = dirname(dirname(__FILE__));
        $baseDir = dirname($vendorDir);
        if(isset($config['path_to_cv'])) {
            $path_to_cv = $baseDir.'/../../src/'.$config['path_to_cv'];
        } else {
            $path_to_cv = $baseDir.'/../'.$container->getParameter('nimbusletruand_curriculumvitae.path_to_cv');
        }
        $container->setParameter('nimbusletruand_curriculumvitae.path_to_cv',  $path_to_cv);

        // Default Curriculum Vitae
        if(isset($config['custo_default_cv'])) {
            $container->setParameter(
                'nimbusletruand_curriculumvitae.custo_default_cv',
                $config['custo_default_cv']
            );
        }

        // Twig template of the Curriculum Vitae
        if(isset($config['template'])) {
            $container->setParameter(
                'nimbusletruand_curriculumvitae.template',
                $config['template']
            );
        }
    }
}
