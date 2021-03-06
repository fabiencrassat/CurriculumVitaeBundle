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

class DefaultControllerTest extends WebTestCase
{

    private $curriculumVitae;
    private $client;
    private $pathToFile;
    private $langs;

    public function setUp()
    {
        $this->pathToFile = __DIR__.'/../../Resources/data/example.xml';
        $this->langs      = ['en', 'fr'];
    }

    public function testIndex()
    {
        $clientForTestIndex = static::createClient();
        $clientForTestIndex->request('GET', '/');
        $this->assertEquals(301, $clientForTestIndex->getResponse()->getStatusCode());
    }

    public function testDisplay()
    {
        $clientForTestDisplay = static::createClient();
        $crawler = $clientForTestDisplay->request('GET', '/example');
        $this->assertEquals(200, $clientForTestDisplay->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("First Name Last Name")')->count());
    }

    public function testOutputHtmlXmlComparaison()
    {
        $this->client = static::createClient();

        foreach ($this->langs as $lang) {
            $this->outputHtmlXmlComparaison($lang);
        }
    }

    public function testOutputJSONXmlComparaison()
    {
        $this->client = static::createClient();

        foreach ($this->langs as $lang) {
            $this->client->request('GET', '/example/'.$lang.'.json');

            $response = $this->client->getResponse();
            $data     = json_decode($response->getContent(), TRUE);

            // Read the Curriculum Vitae
            $this->curriculumVitae = new CurriculumVitae($this->pathToFile, $lang);

            $this->assertSame(
                $this->curriculumVitae->getCurriculumVitaeArray(),
                $data);
        }
    }

    public function testOutputXmlXmlComparaison()
    {
        $this->client = static::createClient();

        foreach ($this->langs as $lang) {
            $this->client->request('GET', '/example/'.$lang.'.xml');
            $response = $this->client->getResponse();
            $response->headers->set('Content-Type', 'application/xml');
            $data = $response->getContent();

            // Read the Curriculum Vitae
            $this->curriculumVitae = new CurriculumVitae($this->pathToFile, $lang);

            $this->assertSame(
                $this->initSerializer()->serialize(
                    $this->curriculumVitae->getCurriculumVitaeArray(),
                    'xml'),
                $data);
        }
    }

    private function initSerializer()
    {
        //initialisation du serializer
        $encoders    = [new XmlEncoder('CurriculumVitae')];
        $normalizers = [new GetSetMethodNormalizer()];
        return new Serializer($normalizers, $encoders);
    }

    public function testOutputFollowMeLink()
    {
        $result         = [];
        $arrayFunctions = new ArrayFunctions();

        $this->client = static::createClient();

        foreach ($this->langs as $lang) {
            $crawler = $this->client->request('GET', '/example/'.$lang);

            // Read the Curriculum Vitae
            $this->curriculumVitae = new CurriculumVitae($this->pathToFile, $lang);

            $cvXml = ['followMe' => $this->curriculumVitae->getFollowMe()];

            $testValue = $arrayFunctions->arrayValuesRecursive($cvXml);
            foreach ($testValue as $value) {
                $alt  = 0;
                $alt += $crawler->filter('img[alt="'.$value.'"]')->count();
                $alt += $crawler->filter('img[title="'.$value.'"]')->count();
                $alt += $crawler->filter('img[src="/'.$value.'"]')->count();
                $alt += $crawler->filter('a[href="'.$value.'"]')->count();

                if ($alt == 0) {
                    $result[] = 'The value '.$value.' is not diplay for language '.$lang;
                }
            }
        }
        $this->assertEquals(0, count($result),
            implode("\n", $result)
        );
    }

    private function outputHtmlXmlComparaison($lang = 'en')
    {
        $arrayFunctions = new ArrayFunctions();
        $crawler        = $this->client->request('GET', '/example/'.$lang);

        // Read the Curriculum Vitae
        $this->curriculumVitae = new CurriculumVitae($this->pathToFile, $lang);

        $cvXml = [
                'identity'                   => $this->curriculumVitae->getIdentity(),
                'lookingFor'                 => $this->curriculumVitae->getLookingFor(),
                CurriculumVitae::EXPERIENCES => $this->curriculumVitae->getExperiences(),
                'skills'                     => $this->curriculumVitae->getSkills(),
                'educations'                 => $this->curriculumVitae->getEducations(),
                'languageSkills'             => $this->curriculumVitae->getLanguageSkills(),
                'miscellaneous'              => $this->curriculumVitae->getMiscellaneous()
        ];
        // Remove all no visible elements
        $cvXml = $this->removeNoVisibleElementDependingOnLanguages($lang, $cvXml);
        $cvXml = $this->removeNoVisibleElementForAllLanguages($cvXml);

        $testValue = $arrayFunctions->arrayValuesRecursive($cvXml);
        $result    = [];
        foreach ($testValue as $value) {
            if ($crawler->filter('html:contains("'.$value.'")')->count() == 0) {
                $result[] = 'The value '.$value.' is not diplay for language '.$lang;
            }
        }
        $this->assertEquals(0, count($result),
            implode("\n", $result)
        );
    }

    /**
     * @param string $lang
     * @param array $cvXml
     *
     * @return array
     */
    private function removeNoVisibleElementDependingOnLanguages($lang, $cvXml)
    {
        if ($lang === 'en') {
            unset($cvXml['identity'][CurriculumVitae::IDENTITY_MYSELF]['birthday']);
        }
        return $cvXml;
    }

    /**
     * @param array $cvXml
     *
     * @return array
     */
    private function removeNoVisibleElementForAllLanguages($cvXml)
    {
        unset($cvXml['identity'][CurriculumVitae::IDENTITY_MYSELF]['picture']);
        unset($cvXml['identity']['address']['street']);
        unset($cvXml['identity']['address']['postalcode']);
        unset($cvXml['identity']['address']['googlemap']);
        unset($cvXml['identity']['contact']['mobile']);
        unset($cvXml['identity']['contact']['email']);
        unset($cvXml[CurriculumVitae::EXPERIENCES]['FirstExperience']['society']['society']['ref']);
        unset($cvXml[CurriculumVitae::EXPERIENCES]['FirstExperience']['society']['siteurl']);
        unset($cvXml[CurriculumVitae::EXPERIENCES]['SecondExperience']['collapse']);
        unset($cvXml[CurriculumVitae::EXPERIENCES]['SecondExperience']['society']['society']['ref']);
        unset($cvXml[CurriculumVitae::EXPERIENCES]['SecondExperience']['society']['siteurl']);
        unset($cvXml[CurriculumVitae::EXPERIENCES]['ThirdExperience']['society']['society']['ref']);
        unset($cvXml[CurriculumVitae::EXPERIENCES]['FourthExperience']['collapse']);
        unset($cvXml['skills']['Functional']['lines']['success']['percentage']);
        unset($cvXml['skills']['Functional']['lines']['success']['class']);
        unset($cvXml['skills']['Functional']['lines']['success']['striped']);
        unset($cvXml['skills']['Functional']['lines']['otherSucess']['percentage']);
        unset($cvXml['skills']['Functional']['lines']['otherSucess']['class']);
        unset($cvXml['skills']['Functional']['lines']['info']['percentage']);
        unset($cvXml['skills']['Functional']['lines']['info']['class']);
        unset($cvXml['skills']['Functional']['lines']['info']['striped']);
        unset($cvXml['skills']['Functional']['lines']['warning']['percentage']);
        unset($cvXml['skills']['Functional']['lines']['warning']['class']);
        unset($cvXml['skills']['Functional']['lines']['danger']['percentage']);
        unset($cvXml['skills']['Functional']['lines']['danger']['class']);
        unset($cvXml['skills']['Functional']['lines']['noClass']['percentage']);
        unset($cvXml['skills']['OtherSkill']['lines']['success']['percentage']);
        unset($cvXml['skills']['OtherSkill']['lines']['success']['class']);
        unset($cvXml['skills']['OtherSkill']['lines']['success']['striped']);
        unset($cvXml['skills']['OtherSkill']['lines']['info']['percentage']);
        unset($cvXml['skills']['OtherSkill']['lines']['info']['class']);
        unset($cvXml['skills']['OtherSkill']['lines']['info']['striped']);
        unset($cvXml['skills']['OtherSkill']['lines']['warning']['percentage']);
        unset($cvXml['skills']['OtherSkill']['lines']['warning']['class']);
        unset($cvXml['skills']['OtherSkill']['lines']['warning']['striped']);
        unset($cvXml['skills']['OtherSkill']['lines']['danger']['percentage']);
        unset($cvXml['skills']['OtherSkill']['lines']['danger']['class']);
        unset($cvXml['skills']['OtherSkill']['lines']['danger']['striped']);
        unset($cvXml['educations']['HighSchool']['collapse']);
        unset($cvXml['educations']['FirstSchool']['collapse']);
        unset($cvXml['languageSkills']['French']['icon']);
        unset($cvXml['languageSkills']['English']['icon']);

        return $cvXml;
    }
}
