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

use FabienCrassat\CurriculumVitaeBundle\Utility\AgeCalculator;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

class AgeCalculatorTest extends \PHPUnit_Framework_TestCase
{

    private $assert;
    private $minDay = 1;
    private $maxDay;
    private $minMonth = 1;
    private $maxMonth = 12;
    private $actualMonth;
    private $actualDay;
    private $year = 2000;

    public function __construct()
    {
        $this->assert = date('Y') - $this->year;
        $this->actualMonth = date('n');
        $this->actualDay = date('d');
        $this->maxDay = date("t", strtotime($this->year."-".$this->actualMonth."-23"));
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
            if(!($day == $this->actualDay && $month == $this->actualMonth)) {
                $assert = $this->assert - 1;
            }
        }

        $this->calculator = new AgeCalculator($this->year."-".$month."-".$day);
        $age = $this->calculator->age();
        
        $this->assertEquals($assert, $age);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBadFormat()
    {
        $this->calculator = new AgeCalculator("0000/0/0");
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBadDate()
    {
        $this->calculator = new AgeCalculator("0000-0-0");
    }
}