<?php

/*
 * This file is part of the FabienCrassat\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabienCrassat\CurriculumVitaeBundle\Tests\DependencyInjection;

use FabienCrassat\CurriculumVitaeBundle\DependencyInjection\FabienCrassatCurriculumVitaeExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

class FabienCrassatCurriculumVitaeExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
    * @var ContainerBuilder
    */
    private $configuration;
    private $loader;

    public function __construct()
    {
        $this->configuration = new ContainerBuilder;
        $this->loader = new FabienCrassatCurriculumVitaeExtension;
    }

    /**
    * Tests services existence
    */
    public function testLoadDefaultParameters()
    {
        $this->createEmptyConfiguration();

        $this->assertHasParameters();

        $this->assertStringEndsWith(
            'Resources/data',
            $this->configuration->getParameter('fabiencrassat_curriculumvitae.path_to_cv')
        );

        $parameters = array(
            'fabiencrassat_curriculumvitae.default_cv' => 'example',
            'fabiencrassat_curriculumvitae.default_lang' => 'en',
            'fabiencrassat_curriculumvitae.template' => 'FabienCrassatCurriculumVitaeBundle:CurriculumVitae:index.html.twig',
        );
        $this->compareParameters($parameters);
    }

    /**
    * Tests custom mailer
    */
    public function testLoadCustomParameters()
    {
        $this->createFullConfiguration();

        $this->assertHasParameters();

        $parameters = array(
            'fabiencrassat_curriculumvitae.path_to_cv' => './Tests/Resources/data',
            'fabiencrassat_curriculumvitae.default_cv' => 'mycv',
            'fabiencrassat_curriculumvitae.default_lang' => 'fr',
            'fabiencrassat_curriculumvitae.template' => 'AcmeHelloBundle:CV:index.html.twig',
        );
        $this->compareParameters($parameters);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testBadDirectory()
    {
        $this->createBadConfiguration();
    }

    /**
    * Creates an empty configuration
    */
    private function createEmptyConfiguration()
    {
        $this->createConfiguration($this->getEmptyConfig());
    }

    /**
    * Creates a full configuration
    */
    private function createFullConfiguration()
    {
        $this->createConfiguration($this->getFullConfig());
    }

    /**
    * Creates a bad configuration
    */
    private function createBadConfiguration()
    {
        $this->createConfiguration($this->getBadPathToCvConfig());
    }

    /**
    * Creates a configuration
    */
    private function createConfiguration($config)
    {
        $this->loader->load(array($config), $this->configuration);
        $this->assertTrue($this->configuration instanceof ContainerBuilder);
    }

    /**
    * Gets an empty config
    *
    * @return array
    */
    private function getEmptyConfig()
    {
        $yaml = <<<EOF
EOF;
        $parser = new Parser;

        return $parser->parse($yaml);
    }

    /**
    * Gets a full config
    *
    * @return array
    */
    private function getFullConfig()
    {
        $yaml = <<<EOF
path_to_cv:
    "./Tests/Resources/data"
custo_default_cv:
    "mycv"
default_lang:
    "fr"
template:
    "AcmeHelloBundle:CV:index.html.twig"
EOF;
        $parser = new Parser;

        return $parser->parse($yaml);
    }

    /**
    * Gets a full config
    *
    * @return array
    */
    private function getBadPathToCvConfig()
    {
        $yaml = <<<EOF
path_to_cv:
    "itIsNotADirectory"
EOF;
        $parser = new Parser;

        return $parser->parse($yaml);
    }

    /**
    * Asserts the identifiers matched parameters
    */
    private function assertHasParameters()
    {
        $this->assertHasParameter('fabiencrassat_curriculumvitae.path_to_cv');
        $this->assertHasParameter('fabiencrassat_curriculumvitae.default_cv');
        $this->assertHasParameter('fabiencrassat_curriculumvitae.default_lang');
        $this->assertHasParameter('fabiencrassat_curriculumvitae.template');
    }

    /**
    * Compare the identifiers matched parameters
    */
    private function compareParameters($parameters)
    {
        foreach ($parameters as $parameter => $valueToCompare) {
            $this->assertEquals(
                $this->configuration->getParameter($parameter),
                $valueToCompare
            );
        }
    }

    /**
    * Asserts the given identifier matched a parameter
    *
    * @param string $id
    */
    private function assertHasParameter($id)
    {
        $this->assertTrue($this->configuration->hasParameter($id));
    }
}