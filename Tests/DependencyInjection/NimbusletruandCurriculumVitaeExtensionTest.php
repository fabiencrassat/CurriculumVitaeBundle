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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Parser;
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

    /**
     * Tests extension loading throws exception if captcha type is empty
     *
     * @expectedException        \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The value "" is not allowed for path "mremi_contact.form.captcha_type". Permissible values: "genemu_captcha", "genemu_recaptcha"
     */
    public function testCustoConfiguration()
    {
        $loader = new NimbusletruandCurriculumVitaeExtension;
        $config = $this->createFullConfiguration();
        $loader->load(array($config), new ContainerBuilder);
    }

    /**
     * Creates a full configuration
     */
    protected function createFullConfiguration()
    {
        $this->configuration = new ContainerBuilder;
        $loader = new NimbusletruandCurriculumVitaeExtension;
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