<?php

/*
 * This file is part of the FabienCrassat\CurriculumVitaeBundle Symfony bundle.
 *
 * (c) Fabien Crassat <fabien@crassat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabienCrassat\CurriculumVitaeBundle\Utility;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;

class AgeCalculator
{
    private $birthdayDate;
    private $birthday;
    private $age = 0;
    private $birth;
    private $today;

    /**
     * @param string $birthday
     */
    public function __construct($birthday)
    {
        $this->birthday = $birthday;
        $this->birth    = new \stdClass();
        $this->setBirthday();

        $this->today = new \stdClass();
        $this->setToday();
    }

    public function age()
    {
        // The calculator of the age
        $this->setAgeByYear();

        if ($this->birth->month >= $this->today->month) {
            if ($this->birth->month == $this->today->month) {
                if ($this->birth->day > $this->today->day) {
                    $this->setAgeMinusOne();
                    return $this->getAge();
                }

                return $this->getAge();
            }

            $this->setAgeMinusOne();
            return $this->getAge();
        }

        return $this->getAge();
    }

    private function setBirthday()
    {
        $this->birthdayDate = date_parse_from_format('Y-m-d', $this->birthday);
        if ($this->birthdayDate['error_count'] > 0) {
            throw new InvalidArgumentException('The date ('. $this->birthday .') is bad formatted, expected Y-m-d.');
        }
        // Retreive the date and transform it to integer
        $this->birth->day   = (int) $this->birthdayDate['day'];
        $this->birth->month = (int) $this->birthdayDate['month'];
        $this->birth->year  = (int) $this->birthdayDate['year'];

        if (!checkdate($this->birth->month, $this->birth->day, $this->birth->year)) {
            throw new InvalidArgumentException('The date ('. $this->birthday .') is unknown.');
        }
    }

    private function setToday()
    {
        // Retreive today and transform it to integer
        $this->today->day   = (int) date('j');
        $this->today->month = (int) date('n');
        $this->today->year  = (int) date('Y');
    }

    private function getAge()
    {
        return $this->age;
    }

    private function setAgeMinusOne()
    {
        $this->age = $this->getAge() - 1;
    }

    private function setAgeByYear()
    {
        $this->age = (int) ($this->today->year - $this->birth->year);
    }
}
