<?php

/*
 * This file is part of the FabienCrassat\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabienCrassat\CurriculumVitaeBundle\Test\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;
use FabienCrassat\CurriculumVitaeBundle\DependencyInjection\FabienCrassatCurriculumVitaeExtension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FabienCrassatCurriculumVitaeExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * {@inheritDoc}
     */
    public function testLoad()
    {
        $configs = array();
        $container = new ContainerBuilder();

        $extension = new FabienCrassatCurriculumVitaeExtension();
        $extension->load($configs, $container);
    }

    /**
     * Creates a full configuration
     */
    protected function createFullConfiguration()
    {
        $this->configuration = new ContainerBuilder;
        $loader = new FabienCrassatCurriculumVitaeExtension;
        $config = $this->getFullConfig();
        $loader->load(array($config), $this->configuration);
        $this->assertTrue($this->configuration instanceof ContainerBuilder);
    }

    /**
     * Gets a full config
     *
     * @return array
     */
    protected function getFullConfig()
    {
        $yaml = <<<EOF
path_to_cv:       Nbs/Bundle/CVBundle/Resources/curriculumvitae
custo_default_cv: fabien
template:         NbsCVBundle:Default:index.html.twig
EOF;
        $parser = new Parser;

        return $parser->parse($yaml);
    }
}