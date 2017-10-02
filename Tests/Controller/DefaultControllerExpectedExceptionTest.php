<?php

/*
 * This file is part of the FabienCrassat\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabienCrassat\CurriculumVitaeBundle\Tests\Controller;

use FabienCrassat\CurriculumVitaeBundle\Entity\CurriculumVitae;
use FabienCrassat\CurriculumVitaeBundle\Utility\ArrayFunctions;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class DefaultControllerExpectedExceptionTest extends WebTestCase
{
    /**
     * @expectedException(Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
     */
    public function testIfAnExportPDFServiceIsNotPresent()
    {
        $client = static::createClient();
        $client->request('GET', '/example/en/pdf');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * @expectedException(Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
     */
    public function testCVDoesNotExistIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/nofile');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * @expectedException(Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
     */
    public function testBadLanguage()
    {
        $client = static::createClient();
        $client->request('GET', '/example/XX');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
