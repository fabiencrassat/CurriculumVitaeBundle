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

class FabienCrassatCurriculumVitaeExtensionExpectedExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
    * @var ContainerBuilder
    */
    private $configuration;
    private $loader;

    public function __construct()
    {
        $this->configuration = new ContainerBuilder;
        $this->loader        = new FabienCrassatCurriculumVitaeExtension;
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testBadDirectory()
    {
        $this->createBadConfiguration();
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
    * Gets a full config
    *
    * @return String[]
    */
    private function getBadPathToCvConfig()
    {
        $yaml   = <<<EOF
path_to_cv:
    "itIsNotADirectory"
EOF;
        $parser = new Parser;

        return (array) $parser->parse($yaml);
    }
}
