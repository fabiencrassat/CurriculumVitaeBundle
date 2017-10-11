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

class CurriculumVitaeTest extends \PHPUnit_Framework_TestCase
{
    private $curriculumVitae;
    private $lang;
    private $interface;
    private $arrayToCompare;

    public function __construct() {
        $this->lang = 'en';
    }

    public function testNoLanguage() {
        $this->interface = 'getDropDownLanguages';

        $this->arrayToCompare = array(
            $this->lang => $this->lang
        );

        $this->assertCVInterface('/../Resources/data/core.xml');
    }

    public function testSpecialCharacters() {
        $this->interface = 'getLookingFor';

        $this->arrayToCompare = array(
            'experience'   => 'Curriculum Vitae With Special Characters',
            'presentation' => 'AZERTY keyboard'
            .' Line 1 ²é"(-è_çà)='
            .' Line 1 with shift 1234567890°+'
            .' Line 1 with Alt Gr ~#{[|`\^@]}'
            .' Line 2 azertyuiop^$'
            .' Line 2 with shift AZERTYUIOP¨£'
            .' Line 2 with Alt Gr €¤'
            .' Line 3 qsdfghjklmù*'
            .' Line 3 with shift QSDFGHJKLM%µ'
            .' Line 3 with Alt Gr '
            .' Line 4 wxcvbn,;:!'
            .' Line 4 with shift WXCVBN?./§'
            .' Line 4 with Alt Gr '
            .' Escape Characters < > &'
            .' End',
        );
        $this->assertCVInterface('/../Resources/data/specialCharacters.xml');
    }

    public function testSimpleHumanFileName() {
        $this->curriculumVitae = new CurriculumVitae(__DIR__.'/../Resources/data/core.xml');
        $this->assertSame('core', $this->curriculumVitae->getHumanFileName());
    }

    public function testHumanFileNameWithExperience() {
        $this->curriculumVitae = new CurriculumVitae(__DIR__.'/../../Resources/data/example.xml');
        $this->assertSame('First Name Last Name - Curriculum Vitae Title',
            $this->curriculumVitae->getHumanFileName()
        );

        $this->curriculumVitae = new CurriculumVitae(__DIR__.'/../Resources/data/backbone.xml');
        $this->assertSame('First Name Last Name - The job',
            $this->curriculumVitae->getHumanFileName()
        );
    }

    public function testHumanFileNameWithJob() {
        $this->curriculumVitae = new CurriculumVitae(__DIR__.'/../Resources/data/backbone.xml');
        $this->assertSame('First Name Last Name - The job', $this->curriculumVitae->getHumanFileName());
    }

    public function testHumanFileNameWithOnLyName(){
        $this->curriculumVitae = new CurriculumVitae(__DIR__.'/../Resources/data/justIdentityMySelf.xml');
        $this->assertSame('First Name Last Name', $this->curriculumVitae->getHumanFileName());
    }

    public function testNullReturnWithNoDeclarationInCurriculumVitaeTag() {
        $this->curriculumVitae = new CurriculumVitae(__DIR__.'/../Resources/data/core.xml');
        $identity              = $this->curriculumVitae->getIdentity();
        $this->assertFalse($identity === NULL);
        $this->assertTrue($identity == NULL);
    }

    private function assertCVInterface($pathToFile = '/../../Resources/data/example.xml') {
        $this->curriculumVitae = new CurriculumVitae(__DIR__.$pathToFile, $this->lang);
        $this->assertEquals($this->arrayToCompare, $this->curriculumVitae->{$this->interface}());
    }
}
