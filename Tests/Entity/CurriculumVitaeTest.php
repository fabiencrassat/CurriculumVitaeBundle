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
use FabienCrassat\CurriculumVitaeBundle\Utility\Tools;

class CurriculumVitaeTest extends \PHPUnit_Framework_TestCase
{
    private $CV;

    public function __construct()
    {
        $this->tools = new Tools();
    }

    public function testNoLanguage()
    {
        // Read the Curriculum Vitae
        $pathToFile = __DIR__.'/../Resources/data/core.xml';
        $this->CV = new CurriculumVitae($pathToFile);
        $language = $this->CV->getDropDownLanguages();
        if (is_array($language)) {
            $this->assertEquals(0, $this->tools->arraysAreSimilar(array('en' => 'en'), $language));
        }
    }

    public function testNullReturnWithNoDeclarationInCurriculumVitaeTag()
    {
        // Read the Curriculum Vitae
        $pathToFile = __DIR__.'/../Resources/data/core.xml';
        $this->CV = new CurriculumVitae($pathToFile);
        $identity = $this->CV->getIdentity();
        $this->assertNull($identity);
    }

    public function testGetAnchorsWithNoLang()
    {
        // Read the Curriculum Vitae
        $pathToFile = __DIR__.'/../Resources/data/test.xml';
        $this->CV = new CurriculumVitae($pathToFile);
        $anchors = $this->CV->getAnchors();
        if (is_array($anchors)) {
            $this->assertEquals(0, $this->tools->arraysAreSimilar(
                array('identity' => array(
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
            ));
        }
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgumentExceptionWithBadCurriculumVitaeFile()
    {
        $this->CV = new CurriculumVitae("abd file");
    }

    /**
     * @expectedException InvalidArgumentException
     */
    // public function testInvalidArgumentExceptionWithTooHighRecursivity()
    // {
    //     // Read the Curriculum Vitae
    //     $pathToFile = __DIR__.'/../Resources/data/test.xml';
    //     $this->CV = new CurriculumVitae($pathToFile);
    //     $this->CV->getSkills();
    // }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgumentExceptionWithNoValidXMLFile()
    {
        // Read the Curriculum Vitae
        $pathToFile = __DIR__.'/../Resources/data/empty.xml';
        $this->CV = new CurriculumVitae($pathToFile);
        $this->CV->getDropDownLanguages();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgumentExceptionWithFatalErrorXMLFile()
    {
        // Read the Curriculum Vitae
        $pathToFile = __DIR__.'/../Resources/data/fatalerror.xml';
        $this->CV = new CurriculumVitae($pathToFile);
        $this->CV->getDropDownLanguages();
    }
}
