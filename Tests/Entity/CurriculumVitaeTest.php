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
        $this->CV = new CurriculumVitae(__DIR__.'/../Resources/data/core.xml');
        $language = $this->CV->getDropDownLanguages();
        if (is_array($language)) {
            $this->assertEquals(0,
                $this->tools->arraysAreSimilar(array('en' => 'en'), $language)
            );
        }
    }

    public function testSimpleHumanFileName()
    {
        $this->CV = new CurriculumVitae(__DIR__.'/../Resources/data/core.xml');
        $this->assertSame("core", $this->CV->getHumanFileName());
    }

    public function testHumanFileNameWithExperience()
    {
        $this->CV = new CurriculumVitae(__DIR__.'/../../Resources/data/example.xml');
        $this->assertSame("First Name Last Name - Curriculum Vitae Title",
            $this->CV->getHumanFileName()
        );
    }

    public function testHumanFileNameWithJob()
    {
        $this->CV = new CurriculumVitae(__DIR__.'/../Resources/data/test.xml');
        $this->assertSame("First Name Last Name - The job", $this->CV->getHumanFileName());
    }

    public function testHumanFileNameWithOnLyName()
    {
        $this->CV = new CurriculumVitae(__DIR__.'/../Resources/data/justIdentityMySelf.xml');
        $this->assertSame("First Name Last Name", $this->CV->getHumanFileName());
    }

    public function testNullReturnWithNoDeclarationInCurriculumVitaeTag()
    {
        $this->CV = new CurriculumVitae(__DIR__.'/../Resources/data/core.xml');
        $this->assertNull($this->CV->getIdentity());
    }

    public function testGetAnchorsWithNoLang()
    {
        $this->CV = new CurriculumVitae(__DIR__.'/../Resources/data/test.xml');
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
    public function testInvalidArgumentExceptionWithNoValidXMLFile()
    {
        $this->CV = new CurriculumVitae( __DIR__.'/../Resources/data/empty.xml');
        $this->CV->getDropDownLanguages();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgumentExceptionWithFatalErrorXMLFile()
    {
        $this->CV = new CurriculumVitae(__DIR__.'/../Resources/data/fatalerror.xml');
        $this->CV->getDropDownLanguages();
    }
}