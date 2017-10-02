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

class CurriculumVitaeInvalidArgumentExceptionTest extends \PHPUnit_Framework_TestCase
{
    private $curriculumVitae;

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgumentExceptionWithBadCurriculumVitaeFile() {
        $this->curriculumVitae = new CurriculumVitae('abd file');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgumentExceptionWithNoValidXMLFile() {
        $this->curriculumVitae = new CurriculumVitae( __DIR__.'/../Resources/data/empty.xml');
        $this->curriculumVitae->getDropDownLanguages();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgumentExceptionWithFatalErrorXMLFile() {
        $this->curriculumVitae = new CurriculumVitae(__DIR__.'/../Resources/data/fatalerror.xml');
        $this->curriculumVitae->getDropDownLanguages();
    }
}
