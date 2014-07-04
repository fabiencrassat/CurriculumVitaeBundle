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
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CurriculumVitaeTest extends \PHPUnit_Framework_TestCase
{
    private $CV;

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCurriculumVitae()
    {
        $this->CV = new CurriculumVitae("abd file");
    }
}