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
    private $CV;

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
    public function testInvalidArgumentExceptionWithTooHighRecursivity()
    {
        // Read the Curriculum Vitae
        $pathToFile = __DIR__.'/../Resources/data/test.xml';
        $this->CV = new CurriculumVitae($pathToFile);
        $this->CV->getSociety();
    }

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
}
