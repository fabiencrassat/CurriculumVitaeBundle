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

    private $assert;
    private $minDay = 0;
    private $maxDay = 32;
    private $minMonth = 0;
    private $actualMonth;
    private $maxMonth = 13;
    private $year = 2000;

    public function __construct()
    {
        $this->calculator = new Calculator();
        $this->assert = date('Y') - $this->year;
        $this->actualMonth = date('n');
    }

    public function testEarlyYear()
    {
        $this->assertEqualsForAge($this->minMonth, $this->minDay);
    }

    public function testSameMonth()
    {
        $this->assertEqualsForAge($this->actualMonth, $this->minDay);
    }

    public function testEndOfTheYear()
    {
        $this->assertEqualsForAge($this->maxMonth, $this->maxDay, TRUE);
    }

    public function testSameMonthAndLastDay()
    {
        $this->assertEqualsForAge($this->actualMonth, $this->maxDay, TRUE);
    }

    private function assertEqualsForAge($month, $day, $beforeBirthday = FALSE)
    {
        $assert = $this->assert;
        if($beforeBirthday) {
            $assert = $this->assert - 1;
        }

        $age = $this->calculator->getAge($month."/".$day."/".$this->year);
        
        $this->assertEquals($assert, $age);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDateFormatException()
    {
        $this->calculator->getAge($this->minMonth."/".$this->minDay."/0000", "bad argument");
    }
}