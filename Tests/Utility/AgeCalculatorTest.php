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

class AgeCalculatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var string $today Timestamp that will be returned by date()
     */
    public static $today;

    private $yearsOld;
    private $minDay = 1;
    private $maxDay;
    private $minMonth = 1;
    private $maxMonth = 12;
    private $actualMonth;
    private $actualDay;
    private $birthYear = 2000;
    private $calculator;

    function setUp()
    {
        $this->yearsOld    = date('Y') - $this->birthYear;
        $this->actualMonth = date('n');
        $this->actualDay   = date('d');
        $this->maxDay      = date('t', strtotime($this->birthYear.'-'.$this->actualMonth.'-23'));
    }

    public function testEarlyYear()
    {
        $this->assertEqualsForAge($this->minMonth, $this->minDay, $this->yearsOld);
    }

    public function testSameMonth()
    {
        $this->assertEqualsForAge($this->actualMonth, $this->minDay, $this->yearsOld);
    }

    public function testEndOfTheYear()
    {
        $this->assertEqualsForAgeBeforeBirthday($this->maxMonth, $this->maxDay);
    }

    public function testSameMonthAndLastDay()
    {
        $this->assertEqualsForAgeBeforeBirthday($this->actualMonth, $this->maxDay);
    }

    public function testFirstDayOfTheYear()
    {
        $this->assertEqualsForAge($this->minMonth, $this->minDay, $this->yearsOld);
    }

    /**
     * @param integer $month
     * @param integer $day
     */
    private function assertEqualsForAgeBeforeBirthday($month, $day)
    {
        $yearsOld = $this->yearsOld;
        if(!($day == $this->actualDay && $month == $this->actualMonth)) {
            $yearsOld = $this->yearsOld - 1;
        }

        $this->assertEqualsForAge($month, $day, $yearsOld);
    }

    /**
     * @param integer $month
     * @param integer $day
     * @param integer $yearsOld
     */
    private function assertEqualsForAge($month, $day, $yearsOld)
    {
        $this->calculator = new AgeCalculator($this->birthYear.'-'.$month.'-'.$day);

        $age = $this->calculator->age();

        $this->assertEquals($yearsOld, $age);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBadFormat()
    {
        $this->calculator = new AgeCalculator('0000/0/0');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBadDate()
    {
        $this->calculator = new AgeCalculator('0000-0-0');
    }
}
