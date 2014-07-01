<?php

/*
 * This file is part of the FabienCrassat\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabienCrassat\CurriculumVitaeBundle\Tests\Utility;

use FabienCrassat\CurriculumVitaeBundle\Utility\Calculator;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

class CalculatorTest extends \PHPUnit_Framework_TestCase
{
    public function testEarlyYear()
    {
        $calculator = new Calculator();
        $year = 2000;
        $result = $calculator->getAge("00/00/".$year);

        $assert = date('Y') - $year;

        // vérifie que votre classe a correctement calculé!
        $this->assertEquals($assert, $result);
    }

    public function testSameMonth()
    {
        $calculator = new Calculator();
        $year = 2000;
        $month = date('n');
        $result = $calculator->getAge($month."/00/".$year);

        $assert = date('Y') - $year;

        // vérifie que votre classe a correctement calculé!
        $this->assertEquals($assert, $result);
    }

    public function testEndOfTheYear()
    {
        $calculator = new Calculator();
        $year = 2000;
        $result = $calculator->getAge("13/32/".$year);

        $assert = date('Y') - $year - 1;

        // vérifie que votre classe a correctement calculé!
        $this->assertEquals($assert, $result);
    }

    public function testSameMonthAndLastDay()
    {
        $calculator = new Calculator();
        $year = 2000;
        $month = date('n');
        $result = $calculator->getAge($month."/32/".$year);

        $assert = date('Y') - $year - 1;

        // vérifie que votre classe a correctement calculé!
        $this->assertEquals($assert, $result);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDateFormatException()
    {
        $calculator = new Calculator();
        $result = $calculator->getAge("00/00/0000", "bad argument");
    }
}