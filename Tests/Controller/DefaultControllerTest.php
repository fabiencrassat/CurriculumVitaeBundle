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
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testDisplay()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/example');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("First Name Last Name")')->count());
    }

    /**
     * @expectedException(Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
     */
    public function testSnappyPDFisNotPresent()
    {
        $client = static::createClient();
        $client->request('GET', '/example/en/pdf');
    }

    /**
     * @expectedException(Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
     */
    public function testCVDoesNotExistIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/nofile');
    }

    /**
     * @expectedException(Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
     */
    public function testBadLanguage()
    {
        $client = static::createClient();
        $client->request('GET', '/example/XX');
    }

    private $ReadCVXml;
    private $client;

    public function testOutputHtmlXmlComparaison()
    {
        $this->client = static::createClient();

        $langs = array('en', 'fr');
        foreach ($langs as $key => $value) {
            $this->OutputHtmlXmlComparaison($value);
        }
    }

    private function OutputHtmlXmlComparaison($lang = 'en')
    {
        $crawler = $this->client->request('GET', '/example/'.$lang);

        // Read the Curriculum Vitae
        $pathToFile = __DIR__.'/../../Resources/data/example.xml';
        $this->ReadCVXml = new CurriculumVitae($pathToFile, $lang);

        $CVXml = array(
                'identity'          => $this->ReadCVXml->getIdentity(),
                'followMe'          => $this->ReadCVXml->getFollowMe(),
                'lookingFor'        => $this->ReadCVXml->getLookingFor(),
                'experiences'       => $this->ReadCVXml->getExperiences(),
                'skills'            => $this->ReadCVXml->getSkills(),
                'educations'        => $this->ReadCVXml->getEducations(),
                'languageSkills'    => $this->ReadCVXml->getLanguageSkills(),
                'miscellaneous'     => $this->ReadCVXml->getMiscellaneous()
        );

        $testValue = $this->array_values_recursive($CVXml);
        foreach ($testValue as $key => $value) {
            $this->assertGreaterThan(0,
                $crawler->filter('html:contains("'.$value.'")')->count(),
                'The value '.$value.' is not diplay for language '.$lang
            );
        }
    }

    private function array_values_recursive($array)
    {
        $return = array();
        foreach($array as $key => $value)
            if(is_array($value)) {
                $return = array_merge($return, $this->array_values_recursive($value));
            } else {
                $return = array_merge($return, array($value));
            }
        return $return;
    }
}