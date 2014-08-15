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
    private $minDay = 1;
    private $maxDay = 31;
    private $minMonth = 1;
    private $actualMonth;
    private $maxMonth = 12;
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

    /**
     * @param integer $day
     */
    private function assertEqualsForAge($month, $day, $beforeBirthday = FALSE)
    {
        $assert = $this->assert;
        if($beforeBirthday) {
            $assert = $this->assert - 1;
        }

        $age = $this->calculator->getAge($this->year."-".$month."-".$day);
        
        $this->assertEquals($assert, $age);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBadFormat()
    {
        $this->calculator->getAge("0000/0/0");
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBadDate()
    {
        $this->calculator->getAge("0000-0-0");
    }
}