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

    public function testOutputJSONXmlComparaison()
    {
        $this->client = static::createClient();

        $langs = array('en', 'fr');
        foreach ($langs as $key => $value) {
            $this->client->request('GET', '/example/'.$value.'.json');
            $response = $this->client->getResponse();
            $data = json_decode($response->getContent(), TRUE);

            // Read the Curriculum Vitae
            $pathToFile = __DIR__.'/../../Resources/data/example.xml';
            $this->ReadCVXml = new CurriculumVitae($pathToFile, $value);

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

            $this->assertSame($CVXml, $data);
        }
    }

    public function testOutputXmlXmlComparaison()
    {
        $this->client = static::createClient();

        $langs = array('en', 'fr');
        foreach ($langs as $key => $value) {
            $this->client->request('GET', '/example/'.$value.'.xml');
            $response = $this->client->getResponse();
            $response->headers->set('Content-Type', 'application/xml');
            $data = $response->getContent();

            // Read the Curriculum Vitae
            $pathToFile = __DIR__.'/../../Resources/data/example.xml';
            $this->ReadCVXml = new CurriculumVitae($pathToFile, $value);

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
            //initialisation du serializer
            $encoders = array(new XmlEncoder('CurriculumVitae'));
            $normalizers = array(new GetSetMethodNormalizer());
            $serializer = new Serializer($normalizers, $encoders);

            $this->assertSame($serializer->serialize($CVXml, 'xml'), $data);
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
                // 'followMe'          => $this->ReadCVXml->getFollowMe(),
                'lookingFor'        => $this->ReadCVXml->getLookingFor(),
                'experiences'       => $this->ReadCVXml->getExperiences(),
                'skills'            => $this->ReadCVXml->getSkills(),
                'educations'        => $this->ReadCVXml->getEducations(),
                'languageSkills'    => $this->ReadCVXml->getLanguageSkills(),
                'miscellaneous'     => $this->ReadCVXml->getMiscellaneous()
        );
        # Remove all no visible elements
        switch ($lang) {
            case 'en':
                unset($CVXml['identity']['myself']['birthday']);
                break;
            default:
                # code...
                break;
        }
        unset($CVXml['identity']['myself']['picture']);
        unset($CVXml['identity']['address']['street']);
        unset($CVXml['identity']['address']['postalcode']);
        unset($CVXml['identity']['address']['googlemap']);
        unset($CVXml['identity']['contact']['mobile']);
        unset($CVXml['experiences']['FirstExperience']['society']['society']['ref']);
        unset($CVXml['experiences']['FirstExperience']['society']['siteurl']);
        unset($CVXml['experiences']['SecondExperience']['collapse']);
        unset($CVXml['experiences']['SecondExperience']['society']['society']['ref']);
        unset($CVXml['experiences']['SecondExperience']['society']['siteurl']);
        unset($CVXml['experiences']['ThirdExperience']['society']['society']['ref']);
        unset($CVXml['experiences']['FourthExperience']['collapse']);
        unset($CVXml['skills']['Functional']['lines']['success']['percentage']);
        unset($CVXml['skills']['Functional']['lines']['success']['class']);
        unset($CVXml['skills']['Functional']['lines']['success']['striped']);
        unset($CVXml['skills']['Functional']['lines']['otherSucess']['percentage']);
        unset($CVXml['skills']['Functional']['lines']['otherSucess']['class']);
        unset($CVXml['skills']['Functional']['lines']['info']['percentage']);
        unset($CVXml['skills']['Functional']['lines']['info']['class']);
        unset($CVXml['skills']['Functional']['lines']['info']['striped']);
        unset($CVXml['skills']['Functional']['lines']['warning']['percentage']);
        unset($CVXml['skills']['Functional']['lines']['warning']['class']);
        unset($CVXml['skills']['Functional']['lines']['danger']['percentage']);
        unset($CVXml['skills']['Functional']['lines']['danger']['class']);
        unset($CVXml['skills']['Functional']['lines']['noClass']['percentage']);
        unset($CVXml['skills']['OtherSkill']['lines']['success']['percentage']);
        unset($CVXml['skills']['OtherSkill']['lines']['success']['class']);
        unset($CVXml['skills']['OtherSkill']['lines']['success']['striped']);
        unset($CVXml['skills']['OtherSkill']['lines']['info']['percentage']);
        unset($CVXml['skills']['OtherSkill']['lines']['info']['class']);
        unset($CVXml['skills']['OtherSkill']['lines']['info']['striped']);
        unset($CVXml['skills']['OtherSkill']['lines']['warning']['percentage']);
        unset($CVXml['skills']['OtherSkill']['lines']['warning']['class']);
        unset($CVXml['skills']['OtherSkill']['lines']['warning']['striped']);
        unset($CVXml['skills']['OtherSkill']['lines']['danger']['percentage']);
        unset($CVXml['skills']['OtherSkill']['lines']['danger']['class']);
        unset($CVXml['skills']['OtherSkill']['lines']['danger']['striped']);
        unset($CVXml['educations']['HighSchool']['collapse']);
        unset($CVXml['educations']['FirstSchool']['collapse']);
        unset($CVXml['languageSkills']['French']['icon']);
        unset($CVXml['languageSkills']['English']['icon']);

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
        foreach($array as $key => $value) {
            if(is_array($value)) {
                $return = array_merge($return, $this->array_values_recursive($value));
            } else {
                $return = array_merge($return, array($value));
            }
        }
        return $return;
    }
}