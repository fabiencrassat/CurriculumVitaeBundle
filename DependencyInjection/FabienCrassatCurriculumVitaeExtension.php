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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FabienCrassatCurriculumVitaeExtension extends Extension
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
            $path_to_cv = $config['path_to_cv'];
        } else {
            $path_to_cv = $baseDir.'/../'.$container->getParameter('fabiencrassat_curriculumvitae.path_to_cv');
        }
        if (!is_dir($path_to_cv)) {
            throw new NotFoundHttpException('There is no directory defined here ('.$path_to_cv.').');
        }
        $container->setParameter('fabiencrassat_curriculumvitae.path_to_cv',  $path_to_cv);

        // Default Curriculum Vitae
        if(isset($config['custo_default_cv'])) {
            $container->setParameter(
                'fabiencrassat_curriculumvitae.custo_default_cv',
                $config['custo_default_cv']
            );
        }

        // Twig template of the Curriculum Vitae
        if(isset($config['template'])) {
            $container->setParameter(
                'fabiencrassat_curriculumvitae.template',
                $config['template']
            );
        }
    }
}
