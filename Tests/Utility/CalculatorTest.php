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

    private $year = 2000;
    private $assert;
    private $month;

    public function __construct()
    {
        $this->calculator = new Calculator();
        $this->assert = date('Y') - $this->year;
        $this->month = date('n');
    }

    public function testEarlyYear()
    {
        $result = $this->calculator->getAge("00/00/".$this->year);
        $this->assertEquals($this->assert, $result);
    }

    public function testSameMonth()
    {
        $result = $this->calculator->getAge($this->month."/00/".$this->year);
        $this->assertEquals($this->assert, $result);
    }

    public function testEndOfTheYear()
    {
        $result = $this->calculator->getAge("13/32/".$this->year);
        $this->assertEquals($this->assert - 1, $result);
    }

    public function testSameMonthAndLastDay()
    {
        $result = $this->calculator->getAge($this->month."/32/".$this->year);
        $this->assertEquals($this->assert - 1, $result);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDateFormatException()
    {
        $this->calculator->getAge("00/00/0000", "bad argument");
    }
}