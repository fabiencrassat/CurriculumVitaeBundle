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
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

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
        $client  = static::createClient();
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

    private $curriculumVitae;
    private $client;

    public function testOutputHtmlXmlComparaison()
    {
        $this->client = static::createClient();

        $langs = array('en', 'fr');
        foreach ($langs as $value) {
            $this->outputHtmlXmlComparaison($value);
        }
    }

    public function testOutputJSONXmlComparaison()
    {
        $this->client = static::createClient();

        $langs = array('en', 'fr');
        foreach ($langs as $value) {
            $this->client->request('GET', '/example/'.$value.'.json');

            $response = $this->client->getResponse();
            $data     = json_decode($response->getContent(), TRUE);

            // Read the Curriculum Vitae
            $pathToFile            = __DIR__.'/../../Resources/data/example.xml';
            $this->curriculumVitae = new CurriculumVitae($pathToFile, $value);

            $cvXml = array(
                'identity'          => $this->curriculumVitae->getIdentity(),
                'followMe'          => $this->curriculumVitae->getFollowMe(),
                'lookingFor'        => $this->curriculumVitae->getLookingFor(),
                'experiences'       => $this->curriculumVitae->getExperiences(),
                'skills'            => $this->curriculumVitae->getSkills(),
                'educations'        => $this->curriculumVitae->getEducations(),
                'languageSkills'    => $this->curriculumVitae->getLanguageSkills(),
                'miscellaneous'     => $this->curriculumVitae->getMiscellaneous()
            );

            $this->assertSame($cvXml, $data);
        }
    }

    public function testOutputXmlXmlComparaison()
    {
        $this->client = static::createClient();

        $langs = array('en', 'fr');
        foreach ($langs as $value) {
            $this->client->request('GET', '/example/'.$value.'.xml');
            $response = $this->client->getResponse();
            $response->headers->set('Content-Type', 'application/xml');
            $data = $response->getContent();

            // Read the Curriculum Vitae
            $pathToFile            = __DIR__.'/../../Resources/data/example.xml';
            $this->curriculumVitae = new CurriculumVitae($pathToFile, $value);

            $cvXml = array(
                'identity'          => $this->curriculumVitae->getIdentity(),
                'followMe'          => $this->curriculumVitae->getFollowMe(),
                'lookingFor'        => $this->curriculumVitae->getLookingFor(),
                'experiences'       => $this->curriculumVitae->getExperiences(),
                'skills'            => $this->curriculumVitae->getSkills(),
                'educations'        => $this->curriculumVitae->getEducations(),
                'languageSkills'    => $this->curriculumVitae->getLanguageSkills(),
                'miscellaneous'     => $this->curriculumVitae->getMiscellaneous()
            );
            //initialisation du serializer
            $encoders    = array(new XmlEncoder('CurriculumVitae'));
            $normalizers = array(new GetSetMethodNormalizer());
            $serializer  = new Serializer($normalizers, $encoders);

            $this->assertSame($serializer->serialize($cvXml, 'xml'), $data);
        }
    }

    public function testOutputFollowMeLink()
    {
        $this->client = static::createClient();

        $langs = array('en', 'fr');
        foreach ($langs as $lang) {
            $crawler = $this->client->request('GET', '/example/'.$lang);

            // Read the Curriculum Vitae
            $pathToFile            = __DIR__.'/../../Resources/data/example.xml';
            $this->curriculumVitae = new CurriculumVitae($pathToFile, $lang);

            $cvXml = array('followMe' => $this->curriculumVitae->getFollowMe());

            $testValue = $this->arrayValuesRecursive($cvXml);
            foreach ($testValue as $value) {
                $alt  = $crawler->filter('img[alt="'.$value.'"]')->count();
                $alt += $crawler->filter('img[title="'.$value.'"]')->count();
                $alt += $crawler->filter('img[src="/'.$value.'"]')->count();
                $alt += $crawler->filter('a[href="'.$value.'"]')->count();

                $this->assertGreaterThan(0, $alt,
                    'The value '.$value.' is not diplay for language '.$lang
                );
            }
        }
    }

    private function outputHtmlXmlComparaison($lang = 'en')
    {
        $crawler = $this->client->request('GET', '/example/'.$lang);

        // Read the Curriculum Vitae
        $pathToFile            = __DIR__.'/../../Resources/data/example.xml';
        $this->curriculumVitae = new CurriculumVitae($pathToFile, $lang);

        $cvXml = array(
                'identity'          => $this->curriculumVitae->getIdentity(),
                'lookingFor'        => $this->curriculumVitae->getLookingFor(),
                'experiences'       => $this->curriculumVitae->getExperiences(),
                'skills'            => $this->curriculumVitae->getSkills(),
                'educations'        => $this->curriculumVitae->getEducations(),
                'languageSkills'    => $this->curriculumVitae->getLanguageSkills(),
                'miscellaneous'     => $this->curriculumVitae->getMiscellaneous()
        );
        // Remove all no visible elements
        switch ($lang) {
            case 'en':
                unset($cvXml['identity']['myself']['birthday']);
                break;
            default:
                // code...
                break;
        }
        unset($cvXml['identity']['myself']['picture']);
        unset($cvXml['identity']['address']['street']);
        unset($cvXml['identity']['address']['postalcode']);
        unset($cvXml['identity']['address']['googlemap']);
        unset($cvXml['identity']['contact']['mobile']);
        unset($cvXml['experiences']['FirstExperience']['society']['society']['ref']);
        unset($cvXml['experiences']['FirstExperience']['society']['siteurl']);
        unset($cvXml['experiences']['SecondExperience']['collapse']);
        unset($cvXml['experiences']['SecondExperience']['society']['society']['ref']);
        unset($cvXml['experiences']['SecondExperience']['society']['siteurl']);
        unset($cvXml['experiences']['ThirdExperience']['society']['society']['ref']);
        unset($cvXml['experiences']['FourthExperience']['collapse']);
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

        $testValue = $this->arrayValuesRecursive($cvXml);
        foreach ($testValue as $value) {
            $this->assertGreaterThan(0,
                $crawler->filter('html:contains("'.$value.'")')->count(),
                'The value '.$value.' is not diplay for language '.$lang
            );
        }
    }

    private function arrayValuesRecursive($array)
    {
        $return = array();
        foreach($array as $value) {
            $return = $this->arrayValuesMerge($return, $value);
        }
        return $return;
    }

    private function arrayValuesMerge($return, $value)
    {
        if(is_array($value)) {
            return array_merge($return, $this->arrayValuesRecursive($value));
        }

        return array_merge($return, array($value));
    }
}
