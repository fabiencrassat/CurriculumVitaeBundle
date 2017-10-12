<?php

/*
 * This file is part of the FabienCrassat\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabienCrassat\CurriculumVitaeBundle\Tests\Entity;

use FabienCrassat\CurriculumVitaeBundle\Entity\CurriculumVitae;

class CurriculumVitaeGetterFromBackboneXMLFileTest extends \PHPUnit\Framework\TestCase
{
    private $curriculumVitae;
    private $lang;

    public function setUp() {
        $this->lang = 'en';
    }

    public function testGetLookingForAndExperiencesAndHumanFileName() {
        $this->curriculumVitae = new CurriculumVitae(__DIR__.'/../Resources/data/backbone.xml', $this->lang);

        $result = array();
        $result = array_merge($result, array('lookingFor' => $this->curriculumVitae->getLookingFor()));
        $result = array_merge($result, array('experiences' => $this->curriculumVitae->getExperiences()));
        $result = array_merge($result, array('pdfFile' => $this->curriculumVitae->getHumanFileName()));

        $expected = array(
            'lookingFor' => array(
                'experience'   => array(
                    'date' => 'Date',
                    'job' => 'The job',
                    'society' => array(
                        'name' => 'My Company',
                        'address' => 'The address of the company',
                        'siteurl' => 'http://www.MyCompany.com')),
                'presentation' => 'A presentation'),
            'experiences' => array(
                'LastJob' => array(
                    'date' => 'Date',
                    'job' => 'The job',
                    'society' => array(
                        'name' => 'My Company',
                        'address' => 'The address of the company',
                        'siteurl' => 'http://www.MyCompany.com'))),
            'pdfFile' => 'First Name Last Name - The job'
        );
        $this->assertEquals($expected, $result);
    }

    public function testGetAnchorsWithNoLang() {
        $this->curriculumVitae = new CurriculumVitae(__DIR__.'/../Resources/data/backbone.xml');

        $anchors = $this->curriculumVitae->getAnchors();
        if (is_array($anchors)) {
            $this->assertEquals(array('identity' => array(
                        'href' => 'identity',
                        'title' => 'identity'),
                      'followMe' => array(
                        'href' => 'followMe',
                        'title' => 'followMe'),
                      'experiences' => array(
                        'href' => 'experiences',
                        'title' => 'experiences'),
                      'skills' => array(
                        'href' => 'skills',
                        'title' => 'skills'),
                      'educations' => array(
                        'href' => 'educations',
                        'title' => 'educations'),
                      'languageSkills' => array(
                        'href' => 'languageSkills',
                        'title' => 'languageSkills'),
                      'miscellaneous' => array(
                        'href' => 'miscellaneous',
                        'title' => 'miscellaneous')
                ),
                $anchors
            );
        }
    }
}
