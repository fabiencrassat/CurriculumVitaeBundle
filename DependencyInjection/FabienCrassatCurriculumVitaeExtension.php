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

    private $config;
    private $container;

    const PATH_TO_CV   = 'fabiencrassat_curriculumvitae.path_to_cv';
    const DEFAULT_CV   = 'fabiencrassat_curriculumvitae.default_cv';
    const DEFAULT_LANG = 'fabiencrassat_curriculumvitae.default_lang';
    const TEMPLATE     = 'fabiencrassat_curriculumvitae.template';

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->container = $container;
        $this->config    = $this->processConfiguration(new Configuration(), $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->setPathToCVDirectory();
        $this->setDefaultCV();
        $this->setTemplate();
        $this->setDefaultLanguage();
    }

    private function setPathToCVDirectory()
    {
        // Path to Curriculum Vitae Directory
        $pathToCV = __DIR__.'/../'.$this->container->getParameter('fabiencrassat_curriculumvitae.path_to_cv');
        if (isset($this->config['path_to_cv'])) {
            $pathToCV = $this->config['path_to_cv'];
        }

        if (!is_dir($pathToCV)) {
            throw new NotFoundHttpException('There is no directory defined here ('.$pathToCV.').');
        }
        $this->container->setParameter('fabiencrassat_curriculumvitae.path_to_cv', $pathToCV);
    }

    private function setDefaultCV()
    {
        // Default Curriculum Vitae
        if (isset($this->config['custo_default_cv'])) {
            $this->container->setParameter(
                'fabiencrassat_curriculumvitae.default_cv',
                $this->config['custo_default_cv']
            );
        }
    }

    private function setTemplate()
    {
        // Twig template of the Curriculum Vitae
        if (isset($this->config['template'])) {
            $this->container->setParameter(
                'fabiencrassat_curriculumvitae.template',
                $this->config['template']
            );
        }
    }

    private function setDefaultLanguage()
    {
        // default_lang of the Curriculum Vitae
        if (isset($this->config['default_lang'])) {
            $this->container->setParameter(
                'fabiencrassat_curriculumvitae.default_lang',
                $this->config['default_lang']
            );
        }
    }
}
